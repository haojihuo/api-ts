# Industrial Internet三级教育培训系统

基于 **ThinkPHP8 + MySQL5.7 + Vue3 + Element Plus** 的多租户培训管理系统。

## 特性
- 多公司（Multi-Tenant）架构，所有核心业务表强制 `company_id`。
- JWT 登录认证。
- RBAC 权限控制（用户-角色-权限）。
- 三级组织：公司 / 部门 / 员工。
- 培训课程、培训记录、考试基础模型。
- `/install` 一键初始化数据库与超级管理员。

## 目录
- `backend/` ThinkPHP8 后端 API
- `frontend/` Vue3 前端
- `scripts/init.sql` 初始化 SQL
- `docker-compose.yml` 一键部署（开发环境）

## 快速部署
```bash
docker compose up -d --build
```

访问：
- 前端：`http://localhost:5173`
- 后端：`http://localhost:8000`
- 安装入口：`http://localhost:8000/install`

> 首次访问 `/install` 会执行数据库表创建与默认数据导入。
