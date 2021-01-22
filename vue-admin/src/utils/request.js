import axios from 'axios'
import { MessageBox, Message } from 'element-ui'
import store from '@/store'
import { getToken } from '@/utils/auth'
import {Loading} from "element-ui";

//LOADING
let loading;
let loadingNum=0;
function startLoading(){
  if(loadingNum===0){
    loading=Loading.service({
      lock:true,
      text:'拼命加载中...',
      background:'rgba(255,255,255,0.5)'
    })
  }
  loadingNum++;
}

function endLoading(){
  loadingNum--;
  if(loadingNum<=0){
    loading.close();
  }
}


// create an axios instance
const service = axios.create({
  baseURL: process.env.VUE_APP_BASE_API, // url = base url + request url
  withCredentials: true, // send cookies when cross-domain requests
  timeout: 5000 // request timeout
})

// request interceptor
service.interceptors.request.use(
  config => {
    // do something before request is sent
    startLoading();
    if (store.getters.token) {
      // let each request carry token
      // ['X-Token'] is a custom headers key
      // please modify it according to the actual situation
      config.headers['X-Token'] = getToken()
    }
    return config
  },
  error => {
    // do something with request error
    console.log(error) // for debug
    return Promise.reject(error)
  }
)

// response interceptor
service.interceptors.response.use(
    /**
     * If you want to get http information such as headers or status
     * Please return  response => response
     */

    /**
     * Determine the request status by custom code
     * Here is just an example
     * You can also judge the status by HTTP Status Code
     */
    response => {
      endLoading();
      return response.data
    },
    error => {
      endLoading();
      console.log('err' , error.response) // for debug
      if (error.response && error.response.status == 401) {
        Message({
          message: error.response.data.message,
          type: 'error',
          duration: 5 * 1000
        })
        store.dispatch('logout');
        if(router.currentRoute.name != "/login"){
          router.push({
            path: '/login',
            query: {
              redirect: router.currentRoute.fullPath
            }
          });
        }else{
          router.push('/login');


        }
      } else {
        Message({
          message: error.message,
          type: 'error',
          duration: 5 * 1000
        })
        return Promise.reject(error)
      }
    }
)

export default service
