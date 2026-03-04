import request from '../utils/request'

export const getDepartmentListApi = () => request({
  url: '/api/departments',
  method: 'get'
})

export const getDepartmentTreeApi = () => request({
  url: '/api/departments/tree',
  method: 'get'
})

export const createDepartmentApi = (data) => request({
  url: '/api/departments',
  method: 'post',
  data
})

export const updateDepartmentApi = (id, data) => request({
  url: `/api/departments/${id}`,
  method: 'put',
  data
})

export const deleteDepartmentApi = (id) => request({
  url: `/api/departments/${id}`,
  method: 'delete'
})
