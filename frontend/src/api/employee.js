import request from '../utils/request'

export const getEmployeeListApi = () => request({
  url: '/api/employees',
  method: 'get'
})

export const createEmployeeApi = (data) => request({
  url: '/api/employees',
  method: 'post',
  data
})

export const updateEmployeeApi = (id, data) => request({
  url: `/api/employees/${id}`,
  method: 'put',
  data
})

export const deleteEmployeeApi = (id) => request({
  url: `/api/employees/${id}`,
  method: 'delete'
})
