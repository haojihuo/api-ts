import request from '../utils/request'

export const getTrainingListApi = () => request({
  url: '/api/training-records',
  method: 'get'
})

export const createTrainingApi = (data) => request({
  url: '/api/training-records',
  method: 'post',
  data
})

export const updateTrainingApi = (id, data) => request({
  url: `/api/training-records/${id}`,
  method: 'put',
  data
})

export const deleteTrainingApi = (id) => request({
  url: `/api/training-records/${id}`,
  method: 'delete'
})
