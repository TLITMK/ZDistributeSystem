import request from '@/utils/request'

export function getRoutes() {
  return request({
    url: '/vue-element-admin/routes',
    method: 'get'
  })
}

export function getRoles(params) {
  return request({
    url: '/role/list',
    method: 'post',
    data:params
  })
}

export function editRoles(params){
  return request({
    url:'/role/edit',
    method:'post',
    data:params
  })
}


export function deleteRole(params) {
  return request({
    url: '/role/del',
    method: 'post',
    data:params
  })
}
