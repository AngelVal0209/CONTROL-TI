<template>
  <div class="flex h-screen bg-gray-50">
    <Toast position="top-right" />
    <Sidebar v-model:visible="sidebarVisible" class="w-64 border-r surface-ground">
      <template #header>
        <div class="flex items-center gap-3 px-2 py-3">
          <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center shadow">
            <i class="pi pi-cog text-xl text-white"></i>
          </div>
          <div>
            <span class="font-bold text-lg text-gray-800">TI Control</span>
            <p class="text-xs text-gray-500">Gestión de Activos</p>
          </div>
        </div>
      </template>
      <PanelMenu :model="menuItems" class="border-none" />
    </Sidebar>
    <div class="flex-1 flex flex-col min-h-screen">
      <Toolbar class="rounded-none border-b surface-ground shadow-sm">
        <template #start>
          <div class="flex items-center gap-3">
            <Button icon="pi pi-bars" severity="secondary" text @click="sidebarVisible = !sidebarVisible" />
            <span class="text-sm text-gray-500 hidden md:inline">TI Control v2.0</span>
          </div>
        </template>
        <template #end>
          <div class="flex items-center gap-4">
            <Tag v-if="user?.roles?.[0]" :value="user.roles[0]" severity="info" />
            <Avatar :label="user?.name?.charAt(0)?.toUpperCase()" shape="circle" size="small" class="bg-primary text-white" />
            <div class="hidden md:block">
              <span class="text-sm font-medium block leading-tight">{{ user?.name }}</span>
              <span class="text-xs text-gray-500">{{ user?.documento }}</span>
            </div>
            <Button label="Salir" icon="pi pi-sign-out" severity="secondary" text plain @click="logout" />
          </div>
        </template>
      </Toolbar>
      <div class="flex-1 p-6 overflow-auto">
        <slot />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import Sidebar from 'primevue/sidebar'
import PanelMenu from 'primevue/panelmenu'
import Toast from 'primevue/toast'
import Toolbar from 'primevue/toolbar'
import Button from 'primevue/button'
import Avatar from 'primevue/avatar'
import Tag from 'primevue/tag'

const sidebarVisible = ref(true)
const user = computed(() => usePage().props.auth?.user)

const isAdmin = computed(() => {
  if (!user.value?.roles) return false
  return user.value.roles.some(r => (typeof r === 'string' ? r : r.name) === 'Administrador')
})

const menuItems = computed(() => {
  const items = [
    {
      label: 'Dashboard',
      icon: 'pi pi-home',
      to: '/dashboard',
    },
    {
      label: 'Equipos',
      icon: 'pi pi-desktop',
      to: '/equipos',
    },
    {
      label: 'Incidentes',
      icon: 'pi pi-exclamation-triangle',
      to: '/incidentes',
    },
    {
      label: 'Mantenimientos',
      icon: 'pi pi-wrench',
      to: '/mantenimientos',
    },
    {
      label: 'Respaldos',
      icon: 'pi pi-server',
      to: '/respaldos',
    },
    {
      label: 'Resp. Correos',
      icon: 'pi pi-envelope',
      to: '/respaldos/correos',
    },
    {
      label: 'Resp. Base Datos',
      icon: 'pi pi-database',
      to: '/respaldos/bd',
    },
    {
      label: 'Reportes',
      icon: 'pi pi-file-pdf',
      to: '/reportes',
    },
    {
      label: 'KPIs',
      icon: 'pi pi-chart-bar',
      to: '/kpis',
    },
    {
      label: 'Auditoría',
      icon: 'pi pi-history',
      to: '/auditoria',
    },
  ]

  if (isAdmin.value) {
    items.push({
      label: 'Usuarios',
      icon: 'pi pi-users',
      to: '/usuarios',
    })
    items.push({
      label: 'Copia de Seguridad',
      icon: 'pi pi-shield',
      to: '/backup',
    })
  }

  return items.map(item => ({
    label: item.label,
    icon: item.icon,
    command: () => router.get(item.to),
  }))
})

function logout() {
  router.post('/logout')
}
</script>
