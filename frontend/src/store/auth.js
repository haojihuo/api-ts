import { defineStore } from 'pinia'
import { loginApi, profileApi } from '../api/auth'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('token') || '',
    userInfo: JSON.parse(localStorage.getItem('userInfo') || '{}')
  }),
  getters: {
    isLogin: (state) => !!state.token
  },
  actions: {
    async login(form) {
      const res = await loginApi(form)
      if (res.code !== 0) throw new Error(res.msg || '登录失败')
      this.token = res.data.token
      localStorage.setItem('token', this.token)
      await this.fetchProfile()
    },
    async fetchProfile() {
      const res = await profileApi()
      if (res.code === 0) {
        this.userInfo = res.data || {}
        localStorage.setItem('userInfo', JSON.stringify(this.userInfo))
      }
    },
    logout() {
      this.token = ''
      this.userInfo = {}
      localStorage.removeItem('token')
      localStorage.removeItem('userInfo')
    }
  }
})
