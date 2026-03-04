<template>
  <div style="max-width: 360px; margin: 80px auto;">
    <el-card>
      <h3>系统登录</h3>
      <el-form :model="form">
        <el-form-item label="用户名">
          <el-input v-model="form.username" />
        </el-form-item>
        <el-form-item label="密码">
          <el-input v-model="form.password" type="password" show-password />
        </el-form-item>
        <el-button type="primary" @click="submit">登录</el-button>
      </el-form>
    </el-card>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import { ElMessage } from 'element-plus'
import http from '../api/http'
import { useAuthStore } from '../store/auth'

const router = useRouter()
const auth = useAuthStore()
const form = reactive({ username: 'admin', password: 'Admin@123456' })

const submit = async () => {
  const { data } = await http.post('/api/login', form)
  if (data.code !== 0) {
    ElMessage.error(data.msg)
    return
  }
  auth.setToken(data.data.token)
  ElMessage.success('登录成功')
  router.push('/')
}
</script>
