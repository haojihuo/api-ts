import request from '../utils/request'

export const getCourseListApi = () => request({
  url: '/api/courses',
  method: 'get'
})

export const createCourseApi = (data) => request({
  url: '/api/courses',
  method: 'post',
  data
})

export const updateCourseApi = (id, data) => request({
  url: `/api/courses/${id}`,
  method: 'put',
  data
})

export const deleteCourseApi = (id) => request({
  url: `/api/courses/${id}`,
  method: 'delete'
})
