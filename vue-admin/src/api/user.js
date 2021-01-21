import request from '@/utils/request'
import axios from 'axios'
export function login(data) {
  return request({
    url: '/login',
    method: 'post',
    data
  })
}

export function getInfo(token) {
  return request({
    url: '/user/info',
    method: 'get',
    params: { token }
  })
}

export function logout() {
  return request({
    url: '/logout',
    method: 'post'
  })
}

export function csrfCookie(){
  return request({
    url:'/sanctum/csrf-cookie',
    method:'get'
  })
}

export function user_admin_list(){
  return request({
    url:'/user/admin_list',
    method:'post'
  })
}

export function user_admin_edit(params){
  return request({
    url:'/user/admin_edit',
    method:'post',
    params:params
  })
}

export function user_admin_del(params){
  return request({
    url:'/user/admin_del',
    method:'post',
    params:params
  })
}

export function user_admin_upload_avatar(params){
  let config = {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  }
  return axios.post('/user/admin_upload_avatar',params,config)
}


