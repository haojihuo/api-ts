<template>
  <el-card>
    <template #header>
      <div class="toolbar">
        <span>部门管理</span>
        <el-button type="primary" @click="openCreate">新增部门</el-button>
      </div>
    </template>

    <el-empty v-if="!loading && !treeData.length" description="暂无部门数据" />
    <el-tree v-else v-loading="loading" :data="treeData" node-key="dept_id" :props="treeProps" default-expand-all>
      <template #default="{ data }">
        <span class="tree-node">
          <span>{{ data.dept_name }}</span>
          <span>
            <el-button link type="primary" @click="openEdit(data)">编辑</el-button>
            <el-popconfirm title="确认删除该部门？" @confirm="onDelete(data)">
              <template #reference>
                <el-button link type="danger">删除</el-button>
              </template>
            </el-popconfirm>
          </span>
        </span>
      </template>
    </el-tree>
  </el-card>

  <el-dialog v-model="dialogVisible" :title="isEdit ? '编辑部门' : '新增部门'" width="500px">
    <el-form :model="form" label-width="100px">
      <el-form-item label="部门名称">
        <el-input v-model="form.dept_name" />
      </el-form-item>
      <el-form-item label="上级部门">
        <el-tree-select v-model="form.parent_id" :data="treeSelectData" :props="treeProps" check-strictly :render-after-expand="false" clearable />
      </el-form-item>
    </el-form>
    <template #footer>
      <el-button @click="dialogVisible = false">取消</el-button>
      <el-button type="primary" @click="onSubmit">确定</el-button>
    </template>
  </el-dialog>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { ElMessage } from 'element-plus'
import { createDepartmentApi, deleteDepartmentApi, getDepartmentListApi, updateDepartmentApi } from '../../api/department'

const loading = ref(false)
const list = ref([])
const dialogVisible = ref(false)
const isEdit = ref(false)
const form = ref({ dept_id: null, dept_name: '', parent_id: 0 })
const treeProps = { label: 'dept_name', children: 'children' }

const buildTree = (rows, parentId = 0) => rows.filter((x) => Number(x.parent_id) === parentId).map((x) => ({ ...x, children: buildTree(rows, Number(x.dept_id)) }))
const treeData = computed(() => buildTree(list.value, 0))
const treeSelectData = computed(() => [{ dept_id: 0, dept_name: '顶级部门', children: treeData.value }])

const loadList = async () => {
  loading.value = true
  try {
    const res = await getDepartmentListApi()
    list.value = res.data || []
  } finally { loading.value = false }
}

const openCreate = () => {
  isEdit.value = false
  form.value = { dept_id: null, dept_name: '', parent_id: 0 }
  dialogVisible.value = true
}

const openEdit = (row) => {
  isEdit.value = true
  form.value = { dept_id: row.dept_id, dept_name: row.dept_name, parent_id: Number(row.parent_id) }
  dialogVisible.value = true
}

const onSubmit = async () => {
  if (!form.value.dept_name) return ElMessage.warning('请输入部门名称')
  if (isEdit.value) {
    try {
      await updateDepartmentApi(form.value.dept_id, form.value)
      ElMessage.success('更新成功')
      dialogVisible.value = false
      await loadList()
    } catch {
      ElMessage.warning('当前后端未开放部门编辑接口')
    }
    return
  }
  await createDepartmentApi({ dept_name: form.value.dept_name, parent_id: Number(form.value.parent_id || 0) })
  ElMessage.success('创建成功')
  dialogVisible.value = false
  await loadList()
}

const onDelete = async (row) => {
  try {
    await deleteDepartmentApi(row.dept_id)
    ElMessage.success('删除成功')
    await loadList()
  } catch {
    ElMessage.warning('当前后端未开放部门删除接口')
  }
}

onMounted(loadList)
</script>

<style scoped>
.toolbar { display: flex; justify-content: space-between; align-items: center; }
.tree-node { width: 100%; display: flex; justify-content: space-between; align-items: center; }
</style>
