import { defineStore } from 'pinia'
import { loginApi, myPermissionsApi, profileApi } from '../api/auth'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('token') || '',
    userInfo: JSON.parse(localStorage.getItem('userInfo') || '{}'),
    permissions: JSON.parse(localStorage.getItem('permissions') || '[]')
  }),
  getters: {
    isLogin: (state) => !!state.token,
    hasPermission: (state) => (perm) => state.permissions.includes(perm)
  },
  actions: {
    async login(form) {
      const res = await loginApi(form)
      if (res.code !== 0) throw new Error(res.msg || '登录失败')
      this.token = res.data.token
      localStorage.setItem('token', this.token)
      await Promise.all([this.fetchProfile(), this.fetchPermissions()])
    },
    async fetchProfile() {
      const res = await profileApi()
      if (res.code === 0) {
        this.userInfo = res.data || {}
        localStorage.setItem('userInfo', JSON.stringify(this.userInfo))
      }
    },
    async fetchPermissions() {
      const res = await myPermissionsApi()
      this.permissions = res.code === 0 ? (res.data || []) : []
      localStorage.setItem('permissions', JSON.stringify(this.permissions))
    },
    logout() {
      this.token = ''
      this.userInfo = {}
      this.permissions = []
      localStorage.removeItem('token')
      localStorage.removeItem('userInfo')
      localStorage.removeItem('permissions')
    }
  }
})
