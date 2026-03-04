<template>
  <el-container class="layout-wrapper">
    <el-aside width="220px" class="sidebar">
      <div class="logo">培训管理系统</div>
      <el-menu router :default-active="$route.path" class="menu" background-color="#001529" text-color="#bfcbd9" active-text-color="#409eff">
        <el-menu-item index="/dashboard">仪表盘</el-menu-item>
        <el-menu-item index="/departments">部门管理</el-menu-item>
        <el-menu-item index="/employees">员工管理</el-menu-item>
        <el-menu-item index="/courses">课程管理</el-menu-item>
        <el-menu-item index="/training">培训记录</el-menu-item>
      </el-menu>
    </el-aside>
    <el-container>
      <el-header class="topbar">
        <BreadcrumbBar />
        <div class="user-box">
          <span>{{ auth.userInfo.username || '用户' }}</span>
          <el-button link type="danger" @click="onLogout">退出</el-button>
        </div>
      </el-header>
      <el-main class="main-content">
        <router-view />
      </el-main>
    </el-container>
  </el-container>
</template>

<script setup>
import BreadcrumbBar from '../components/BreadcrumbBar.vue'
import { useAuthStore } from '../store/auth'
import { useRouter } from 'vue-router'

const auth = useAuthStore()
const router = useRouter()

const onLogout = () => {
  auth.logout()
  router.push('/login')
}
</script>

<style scoped>
.layout-wrapper { min-height: 100vh; }
.sidebar { background: #001529; }
.logo { color: #fff; font-size: 18px; font-weight: 600; padding: 16px; text-align: center; border-bottom: 1px solid #0b2747; }
.menu { border-right: none; }
.topbar { background: #fff; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; }
.main-content { background: #f5f7fa; }
.user-box { display: flex; align-items: center; gap: 12px; }
@media (max-width: 900px) {
  .sidebar { width: 70px !important; }
  .logo { font-size: 12px; padding: 12px 4px; }
}
</style>
