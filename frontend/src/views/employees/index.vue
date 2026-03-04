<template>
  <el-card>
    <template #header>
      <div class="toolbar"><span>员工管理</span><el-button type="primary" @click="openCreate">新增员工</el-button></div>
    </template>

    <el-form :inline="true" :model="filters" class="filter-bar">
      <el-form-item label="姓名"><el-input v-model="filters.name" placeholder="输入姓名" clearable /></el-form-item>
      <el-form-item label="部门"><el-select v-model="filters.dept_id" clearable placeholder="全部部门" style="width: 180px"><el-option v-for="d in departments" :key="d.dept_id" :label="d.dept_name" :value="d.dept_id" /></el-select></el-form-item>
      <el-form-item><el-button @click="page=1">筛选</el-button></el-form-item>
    </el-form>

    <el-table v-loading="loading" :data="pageData" border>
      <el-table-column prop="employee_id" label="ID" width="80" />
      <el-table-column prop="name" label="姓名" />
      <el-table-column prop="phone" label="手机号" />
      <el-table-column prop="dept_id" label="部门">
        <template #default="{ row }">{{ deptMap[row.dept_id] || '-' }}</template>
      </el-table-column>
      <el-table-column prop="job_title" label="岗位" />
      <el-table-column label="操作" width="160">
        <template #default="{ row }">
          <el-button link type="primary" @click="openEdit(row)">编辑</el-button>
          <el-button link type="danger" @click="onDelete(row)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>

    <el-empty v-if="!loading && filteredList.length===0" description="暂无数据" />

    <el-pagination v-model:current-page="page" v-model:page-size="pageSize" :total="filteredList.length" layout="total, sizes, prev, pager, next" class="pager" />
  </el-card>

  <el-dialog v-model="dialogVisible" :title="isEdit ? '编辑员工' : '新增员工'">
    <el-form :model="form" label-width="100px">
      <el-form-item label="姓名"><el-input v-model="form.name" /></el-form-item>
      <el-form-item label="手机号"><el-input v-model="form.phone" /></el-form-item>
      <el-form-item label="部门"><el-select v-model="form.dept_id"><el-option v-for="d in departments" :key="d.dept_id" :label="d.dept_name" :value="d.dept_id" /></el-select></el-form-item>
      <el-form-item label="岗位"><el-input v-model="form.job_title" /></el-form-item>
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
import { getDepartmentListApi } from '../../api/department'
import { createEmployeeApi, deleteEmployeeApi, getEmployeeListApi, updateEmployeeApi } from '../../api/employee'

const loading = ref(false)
const list = ref([])
const departments = ref([])
const filters = ref({ name: '', dept_id: null })
const page = ref(1)
const pageSize = ref(10)
const dialogVisible = ref(false)
const isEdit = ref(false)
const form = ref({ employee_id: null, name: '', phone: '', dept_id: null, job_title: '', status: 1 })

const deptMap = computed(() => Object.fromEntries(departments.value.map((d) => [d.dept_id, d.dept_name])))
const filteredList = computed(() => list.value.filter((item) => {
  const okName = !filters.value.name || item.name?.includes(filters.value.name)
  const okDept = !filters.value.dept_id || Number(item.dept_id) === Number(filters.value.dept_id)
  return okName && okDept
}))
const pageData = computed(() => {
  const start = (page.value - 1) * pageSize.value
  return filteredList.value.slice(start, start + pageSize.value)
})

const loadData = async () => {
  loading.value = true
  try {
    const [empRes, deptRes] = await Promise.all([getEmployeeListApi(), getDepartmentListApi()])
    list.value = empRes.data || []
    departments.value = deptRes.data || []
  } finally { loading.value = false }
}

const openCreate = () => { isEdit.value = false; form.value = { employee_id: null, name: '', phone: '', dept_id: null, job_title: '', status: 1 }; dialogVisible.value = true }
const openEdit = (row) => { isEdit.value = true; form.value = { ...row, dept_id: Number(row.dept_id) }; dialogVisible.value = true }

const onSubmit = async () => {
  if (!form.value.name || !form.value.dept_id) return ElMessage.warning('请填写必填项')
  if (isEdit.value) {
    try {
      await updateEmployeeApi(form.value.employee_id, form.value)
      ElMessage.success('更新成功')
      dialogVisible.value = false
      await loadData()
    } catch {
      ElMessage.warning('当前后端未开放员工编辑接口')
    }
    return
  }
  await createEmployeeApi({ ...form.value, dept_id: Number(form.value.dept_id) })
  ElMessage.success('创建成功')
  dialogVisible.value = false
  await loadData()
}

const onDelete = async (row) => {
  try {
    await deleteEmployeeApi(row.employee_id)
    ElMessage.success('删除成功')
    await loadData()
  } catch {
    ElMessage.warning('当前后端未开放员工删除接口')
  }
}

onMounted(loadData)
</script>

<style scoped>
.toolbar,.filter-bar { display: flex; justify-content: space-between; align-items: center; }
.filter-bar { margin-bottom: 8px; }
.pager { margin-top: 16px; justify-content: flex-end; display: flex; }
</style>
