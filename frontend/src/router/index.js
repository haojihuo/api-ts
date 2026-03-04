import { createRouter, createWebHistory } from 'vue-router'
import AdminLayout from '../layout/AdminLayout.vue'
import LoginView from '../views/LoginView.vue'

const routes = [
  { path: '/login', component: LoginView, meta: { title: '登录' } },
  {
    path: '/',
    component: AdminLayout,
    redirect: '/dashboard',
    meta: { auth: true, title: '首页' },
    children: [
      { path: 'dashboard', component: () => import('../views/dashboard/index.vue'), meta: { title: '仪表盘', auth: true } },
      { path: 'departments', component: () => import('../views/departments/index.vue'), meta: { title: '部门管理', auth: true } },
      { path: 'employees', component: () => import('../views/employees/index.vue'), meta: { title: '员工管理', auth: true } },
      { path: 'courses', component: () => import('../views/courses/index.vue'), meta: { title: '课程管理', auth: true } },
      { path: 'training', component: () => import('../views/training/index.vue'), meta: { title: '培训记录', auth: true } }
    ]
  }
]

const router = createRouter({ history: createWebHistory(), routes })

router.beforeEach((to) => {
  const token = localStorage.getItem('token')
  if (to.meta.auth && !token) return '/login'
  if (to.path === '/login' && token) return '/dashboard'
})

export default router
