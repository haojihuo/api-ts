<template>
  <div>
    <el-row :gutter="16" v-loading="loading">
      <el-col :span="6" v-for="card in cards" :key="card.label">
        <el-card shadow="hover">
          <div class="card-title">{{ card.label }}</div>
          <div class="card-value">{{ card.value }}</div>
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { getDepartmentListApi } from '../../api/department'
import { getEmployeeListApi } from '../../api/employee'
import { getCourseListApi } from '../../api/course'
import { getTrainingListApi } from '../../api/training'

const loading = ref(false)
const stats = ref({ departments: 0, employees: 0, courses: 0, trainings: 0 })

const cards = computed(() => [
  { label: '部门总数', value: stats.value.departments },
  { label: '员工总数', value: stats.value.employees },
  { label: '课程总数', value: stats.value.courses },
  { label: '培训记录', value: stats.value.trainings }
])

const loadData = async () => {
  loading.value = true
  try {
    const [d, e, c, t] = await Promise.all([getDepartmentListApi(), getEmployeeListApi(), getCourseListApi(), getTrainingListApi()])
    stats.value = {
      departments: d.data?.length || 0,
      employees: e.data?.length || 0,
      courses: c.data?.length || 0,
      trainings: t.data?.length || 0
    }
  } finally {
    loading.value = false
  }
}

onMounted(loadData)
</script>

<style scoped>
.card-title { color: #666; }
.card-value { margin-top: 8px; font-size: 32px; color: #409eff; font-weight: 700; }
</style>
