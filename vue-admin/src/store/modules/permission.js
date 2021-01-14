import { asyncRoutes, constantRoutes } from '@/router'
import Layout from '@/layout'


function generateMenu(routes,menus){
  menus.forEach(item=>{
    const menu={
      path:item.path,
      component:item.component === 'Layout'?Layout:resolve=>
          require([`@/views/${item.component}`],resolve),
      hidden:item.hidden,
      children:[],
      name:item.name,
      redirect:item.redirect?item.redirect:'',
      meta:{
        title:item.title,
        icon:item.icon,
        activeMenu:item.activeMenu,
        noCache:item.noCache
      }
    }
    if(item.children && item.children.length>0){
      generateMenu(menu.children,item.children)
    }else{
      delete menu.children
    }
    routes.push(menu)
  })
}



/**
 * Use meta.role to determine if the current user has permission
 * @param roles
 * @param route
 */
function hasPermission(roles, route) {
  if (route.meta && route.meta.roles) {
    return roles.some(role => route.meta.roles.includes(role))
  } else {
    return true
  }
}

/**
 * Filter asynchronous routing tables by recursion
 * @param routes asyncRoutes
 * @param roles
 */
export function filterAsyncRoutes(routes, roles) {
  const res = []

  routes.forEach(route => {
    const tmp = { ...route }
    if (hasPermission(roles, tmp)) {
      if (tmp.children) {
        tmp.children = filterAsyncRoutes(tmp.children, roles)
      }
      res.push(tmp)
    }
  })

  return res
}

const state = {
  routes: [],
  addRoutes: []
}

const mutations = {
  SET_ROUTES: (state, routes) => {
    state.addRoutes = routes
    state.routes = constantRoutes.concat(routes)
  }
}

const actions = {
  generateRoutes({ commit }, data) {
    const {roles,menus}=data;
    return new Promise(resolve => {
      let accessedRoutes=[];
      generateMenu(accessedRoutes,menus);
      accessedRoutes.push({path:'*',redirect: '/404',hidden: true});
      commit('SET_ROUTES',accessedRoutes);
      resolve(accessedRoutes);


    })
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
