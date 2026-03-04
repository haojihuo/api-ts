import request from '../utils/request'

export const loginApi = (payload) => request({
  url: '/api/login',
  method: 'post',
  data: payload
})

export const profileApi = () => request({
  url: '/api/profile',
  method: 'get'
})

export const myPermissionsApi = () => request({
  url: '/api/permissions/my',
  method: 'get'
})
