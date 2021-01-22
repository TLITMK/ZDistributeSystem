import request from '@/utils/request'

export function permission_all(){
    return request({
        url:'/permission/all',
        method:'post',
    })
}

export function permission_add(params){
    return request({
        url:'/permission/edit',
        method:'post',
        params:params
    })
}

export function permission_del(params){
    return request({
        url:'/permission/del',
        method:'post',
        params:params
    })
}