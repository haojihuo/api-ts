<template>
  <el-card>
    <template #header>
      <div class="toolbar"><span>课程管理</span><el-button type="primary" @click="openCreate">新增课程</el-button></div>
    </template>

    <el-form inline :model="filters" class="filter-bar">
      <el-form-item label="课程类型">
        <el-select v-model="filters.course_type" clearable placeholder="全部类型" style="width: 220px">
          <el-option label="company education" value="company education" />
          <el-option label="department education" value="department education" />
          <el-option label="job education" value="job education" />
        </el-select>
      </el-form-item>
    </el-form>

    <el-table border v-loading="loading" :data="pageData">
      <el-table-column prop="course_id" label="ID" width="80" />
      <el-table-column prop="course_name" label="课程名称" />
      <el-table-column prop="course_type" label="课程类型" width="220" />
      <el-table-column prop="content" label="内容" show-overflow-tooltip />
      <el-table-column label="操作" width="160">
        <template #default="{ row }">
          <el-button link type="primary" @click="openEdit(row)">编辑</el-button>
          <el-button link type="danger" @click="onDelete(row)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>

    <el-empty v-if="!loading && filteredList.length===0" description="暂无课程数据" />
    <el-pagination v-model:current-page="page" v-model:page-size="pageSize" :total="filteredList.length" layout="total, sizes, prev, pager, next" class="pager" />
  </el-card>

  <el-dialog v-model="dialogVisible" :title="isEdit ? '编辑课程' : '新增课程'">
    <el-form :model="form" label-width="100px">
      <el-form-item label="课程名称"><el-input v-model="form.course_name" /></el-form-item>
      <el-form-item label="课程类型">
        <el-select v-model="form.course_type">
          <el-option label="company education" value="company education" />
          <el-option label="department education" value="department education" />
          <el-option label="job education" value="job education" />
        </el-select>
      </el-form-item>
      <el-form-item label="课程内容"><el-input v-model="form.content" type="textarea" :rows="4" /></el-form-item>
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
import { createCourseApi, deleteCourseApi, getCourseListApi, updateCourseApi } from '../../api/course'

const loading = ref(false)
const list = ref([])
const page = ref(1)
const pageSize = ref(10)
const filters = ref({ course_type: '' })
const dialogVisible = ref(false)
const isEdit = ref(false)
const form = ref({ course_id: null, course_name: '', course_type: 'company education', content: '' })

const filteredList = computed(() => list.value.filter((item) => !filters.value.course_type || item.course_type === filters.value.course_type))
const pageData = computed(() => filteredList.value.slice((page.value - 1) * pageSize.value, (page.value - 1) * pageSize.value + pageSize.value))

const loadData = async () => {
  loading.value = true
  try {
    const res = await getCourseListApi()
    list.value = res.data || []
  } finally { loading.value = false }
}

const openCreate = () => { isEdit.value = false; form.value = { course_id: null, course_name: '', course_type: 'company education', content: '' }; dialogVisible.value = true }
const openEdit = (row) => { isEdit.value = true; form.value = { ...row }; dialogVisible.value = true }

const onSubmit = async () => {
  if (!form.value.course_name) return ElMessage.warning('请输入课程名称')
  if (isEdit.value) {
    try {
      await updateCourseApi(form.value.course_id, form.value)
      ElMessage.success('更新成功')
      dialogVisible.value = false
      await loadData()
    } catch {
      ElMessage.warning('当前后端未开放课程编辑接口')
    }
    return
  }
  await createCourseApi(form.value)
  ElMessage.success('创建成功')
  dialogVisible.value = false
  await loadData()
}

const onDelete = async (row) => {
  try {
    await deleteCourseApi(row.course_id)
    ElMessage.success('删除成功')
    await loadData()
  } catch {
    ElMessage.warning('当前后端未开放课程删除接口')
  }
}

onMounted(loadData)
</script>

<style scoped>
.toolbar,.filter-bar { display: flex; justify-content: space-between; align-items: center; }
.pager { margin-top: 16px; justify-content: flex-end; display: flex; }
</style>
