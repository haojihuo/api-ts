<template>
  <div style="padding: 16px;">
    <el-page-header content="工业互联网三级教育培训系统" />
    <el-tabs>
      <el-tab-pane label="部门管理">
        <el-space>
          <el-input v-model="deptForm.dept_name" placeholder="部门名称" style="width:220px" />
          <el-button type="primary" @click="createDepartment">新增</el-button>
        </el-space>
        <el-table :data="departments" style="margin-top: 12px">
          <el-table-column prop="dept_id" label="ID" />
          <el-table-column prop="dept_name" label="部门" />
          <el-table-column prop="parent_id" label="父级" />
        </el-table>
      </el-tab-pane>
      <el-tab-pane label="员工管理">
        <el-space wrap>
          <el-input v-model="empForm.name" placeholder="姓名" style="width:120px" />
          <el-input v-model="empForm.phone" placeholder="手机号" style="width:150px" />
          <el-input v-model="empForm.dept_id" placeholder="部门ID" style="width:120px" />
          <el-input v-model="empForm.job_title" placeholder="岗位" style="width:120px" />
          <el-button type="primary" @click="createEmployee">新增</el-button>
        </el-space>
        <el-table :data="employees" style="margin-top: 12px">
          <el-table-column prop="employee_id" label="ID" />
          <el-table-column prop="name" label="姓名" />
          <el-table-column prop="dept_id" label="部门" />
          <el-table-column prop="job_title" label="岗位" />
        </el-table>
      </el-tab-pane>
      <el-tab-pane label="课程管理">
        <el-space>
          <el-input v-model="courseForm.course_name" placeholder="课程名称" style="width:220px" />
          <el-input v-model="courseForm.course_type" placeholder="课程类型" style="width:120px" />
          <el-button type="primary" @click="createCourse">新增</el-button>
        </el-space>
        <el-table :data="courses" style="margin-top: 12px">
          <el-table-column prop="course_id" label="ID" />
          <el-table-column prop="course_name" label="课程" />
          <el-table-column prop="course_type" label="类型" />
        </el-table>
      </el-tab-pane>
      <el-tab-pane label="培训记录">
        <el-space wrap>
          <el-input v-model="trainingForm.employee_id" placeholder="员工ID" style="width:120px" />
          <el-input v-model="trainingForm.course_id" placeholder="课程ID" style="width:120px" />
          <el-select v-model="trainingForm.status" style="width:130px">
            <el-option label="pending" value="pending" />
            <el-option label="done" value="done" />
          </el-select>
          <el-button type="primary" @click="createTraining">新增</el-button>
        </el-space>
        <el-table :data="trainings" style="margin-top: 12px">
          <el-table-column prop="training_id" label="ID" />
          <el-table-column prop="employee_id" label="员工" />
          <el-table-column prop="course_id" label="课程" />
          <el-table-column prop="status" label="状态" />
        </el-table>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue'
import { ElMessage } from 'element-plus'
import http from '../api/http'

const departments = ref([])
const employees = ref([])
const courses = ref([])
const trainings = ref([])

const deptForm = reactive({ dept_name: '', parent_id: 0 })
const empForm = reactive({ name: '', phone: '', dept_id: '', job_title: '', status: 1 })
const courseForm = reactive({ course_name: '', course_type: '公司级教育', content: '' })
const trainingForm = reactive({ employee_id: '', course_id: '', status: 'pending' })

const loadAll = async () => {
  departments.value = (await http.get('/api/departments')).data.data || []
  employees.value = (await http.get('/api/employees')).data.data || []
  courses.value = (await http.get('/api/courses')).data.data || []
  trainings.value = (await http.get('/api/training-records')).data.data || []
}

const createDepartment = async () => {
  await http.post('/api/departments', deptForm)
  ElMessage.success('部门新增成功')
  deptForm.dept_name = ''
  loadAll()
}
const createEmployee = async () => {
  await http.post('/api/employees', { ...empForm, dept_id: Number(empForm.dept_id) })
  ElMessage.success('员工新增成功')
  loadAll()
}
const createCourse = async () => {
  await http.post('/api/courses', courseForm)
  ElMessage.success('课程新增成功')
  loadAll()
}
const createTraining = async () => {
  await http.post('/api/training-records', {
    employee_id: Number(trainingForm.employee_id),
    course_id: Number(trainingForm.course_id),
    status: trainingForm.status
  })
  ElMessage.success('培训记录新增成功')
  loadAll()
}

onMounted(loadAll)
</script>
