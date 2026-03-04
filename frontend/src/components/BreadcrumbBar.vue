<template>
  <el-breadcrumb separator="/">
    <el-breadcrumb-item v-for="item in crumbs" :key="item.path">
      {{ item.meta?.title || 'Untitled' }}
    </el-breadcrumb-item>
  </el-breadcrumb>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const crumbs = computed(() => route.matched.filter((item) => item.meta?.title))
</script>
