<template>
  <el-card>
    <template #header>
      <div class="toolbar"><span>培训记录</span><el-button type="primary" @click="openCreate">新增记录</el-button></div>
    </template>

    <el-form inline :model="filters" class="filter-bar">
      <el-form-item label="员工">
        <el-select v-model="filters.employee_id" clearable placeholder="全部员工" style="width: 180px">
          <el-option v-for="e in employees" :key="e.employee_id" :label="e.name" :value="e.employee_id" />
        </el-select>
      </el-form-item>
      <el-form-item label="课程">
        <el-select v-model="filters.course_id" clearable placeholder="全部课程" style="width: 180px">
          <el-option v-for="c in courses" :key="c.course_id" :label="c.course_name" :value="c.course_id" />
        </el-select>
      </el-form-item>
      <el-form-item label="状态">
        <el-select v-model="filters.status" clearable style="width: 140px">
          <el-option label="pending" value="pending" />
          <el-option label="done" value="done" />
        </el-select>
      </el-form-item>
    </el-form>

    <el-table border v-loading="loading" :data="pageData">
      <el-table-column prop="training_id" label="ID" width="80" />
      <el-table-column label="员工"><template #default="{ row }">{{ empMap[row.employee_id] || row.employee_id }}</template></el-table-column>
      <el-table-column label="课程"><template #default="{ row }">{{ courseMap[row.course_id] || row.course_id }}</template></el-table-column>
      <el-table-column prop="status" label="状态" width="120" />
      <el-table-column prop="completed_at" label="完成时间" width="180" />
      <el-table-column label="操作" width="160">
        <template #default="{ row }">
          <el-button link type="primary" @click="openEdit(row)">编辑</el-button>
          <el-button link type="danger" @click="onDelete(row)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>

    <el-empty v-if="!loading && filteredList.length===0" description="暂无培训记录" />
    <el-pagination v-model:current-page="page" v-model:page-size="pageSize" :total="filteredList.length" layout="total, sizes, prev, pager, next" class="pager" />
  </el-card>

  <el-dialog v-model="dialogVisible" :title="isEdit ? '编辑培训记录' : '新增培训记录'">
    <el-form :model="form" label-width="100px">
      <el-form-item label="员工"><el-select v-model="form.employee_id"><el-option v-for="e in employees" :key="e.employee_id" :label="e.name" :value="e.employee_id" /></el-select></el-form-item>
      <el-form-item label="课程"><el-select v-model="form.course_id"><el-option v-for="c in courses" :key="c.course_id" :label="c.course_name" :value="c.course_id" /></el-select></el-form-item>
      <el-form-item label="状态"><el-select v-model="form.status"><el-option label="pending" value="pending" /><el-option label="done" value="done" /></el-select></el-form-item>
      <el-form-item label="完成时间"><el-date-picker v-model="form.completed_at" type="datetime" value-format="YYYY-MM-DD HH:mm:ss" /></el-form-item>
    </el-form>
    <template #footer>
      <el-button @click="dialogVisible=false">取消</el-button>
      <el-button type="primary" @click="onSubmit">确定</el-button>
    </template>
  </el-dialog>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { ElMessage } from 'element-plus'
import { getCourseListApi } from '../../api/course'
import { getEmployeeListApi } from '../../api/employee'
import { createTrainingApi, deleteTrainingApi, getTrainingListApi, updateTrainingApi } from '../../api/training'

const loading = ref(false)
const list = ref([])
const employees = ref([])
const courses = ref([])
const page = ref(1)
const pageSize = ref(10)
const filters = ref({ employee_id: null, course_id: null, status: '' })
const dialogVisible = ref(false)
const isEdit = ref(false)
const form = ref({ training_id: null, employee_id: null, course_id: null, status: 'pending', completed_at: '' })

const empMap = computed(() => Object.fromEntries(employees.value.map((e) => [e.employee_id, e.name])))
const courseMap = computed(() => Object.fromEntries(courses.value.map((c) => [c.course_id, c.course_name])))

const filteredList = computed(() => list.value.filter((item) => {
  const e = !filters.value.employee_id || Number(item.employee_id) === Number(filters.value.employee_id)
  const c = !filters.value.course_id || Number(item.course_id) === Number(filters.value.course_id)
  const s = !filters.value.status || item.status === filters.value.status
  return e && c && s
}))
const pageData = computed(() => filteredList.value.slice((page.value - 1) * pageSize.value, (page.value - 1) * pageSize.value + pageSize.value))

const loadData = async () => {
  loading.value = true
  try {
    const [tr, em, co] = await Promise.all([getTrainingListApi(), getEmployeeListApi(), getCourseListApi()])
    list.value = tr.data || []
    employees.value = em.data || []
    courses.value = co.data || []
  } finally { loading.value = false }
}

const openCreate = () => { isEdit.value = false; form.value = { training_id: null, employee_id: null, course_id: null, status: 'pending', completed_at: '' }; dialogVisible.value = true }
const openEdit = (row) => { isEdit.value = true; form.value = { ...row, employee_id: Number(row.employee_id), course_id: Number(row.course_id) }; dialogVisible.value = true }

const onSubmit = async () => {
  if (!form.value.employee_id || !form.value.course_id) return ElMessage.warning('请选择员工和课程')
  if (isEdit.value) {
    try {
      await updateTrainingApi(form.value.training_id, form.value)
      ElMessage.success('更新成功')
      dialogVisible.value = false
      await loadData()
    } catch {
      ElMessage.warning('当前后端未开放培训记录编辑接口')
    }
    return
  }
  await createTrainingApi({ ...form.value, employee_id: Number(form.value.employee_id), course_id: Number(form.value.course_id) })
  ElMessage.success('创建成功')
  dialogVisible.value = false
  await loadData()
}

const onDelete = async (row) => {
  try {
    await deleteTrainingApi(row.training_id)
    ElMessage.success('删除成功')
    await loadData()
  } catch {
    ElMessage.warning('当前后端未开放培训记录删除接口')
  }
}

onMounted(loadData)
</script>

<style scoped>
.toolbar,.filter-bar { display: flex; justify-content: space-between; align-items: center; }
.pager { margin-top: 16px; justify-content: flex-end; display: flex; }
</style>
