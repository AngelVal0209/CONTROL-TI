<template>
  <div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">Indicadores KPIs</h1>
        <p class="text-gray-500 text-sm">Métricas de rendimiento del sistema</p>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <Card class="shadow-md border-t-4 border-t-blue-500">
        <template #title>
          <div class="flex items-center gap-2">
            <i class="pi pi-desktop text-blue-500"></i>
            <span class="font-semibold">KPI Inventario</span>
          </div>
        </template>
        <template #content>
          <div class="flex flex-col gap-3">
            <div class="flex justify-between items-center p-2 bg-blue-50 rounded">
              <span class="text-gray-700">Total Equipos</span>
              <Tag :value="kpis?.inventario?.total ?? 0" severity="info" />
            </div>
            <Divider />
            <div class="flex justify-between items-center p-2 bg-green-50 rounded">
              <span class="text-gray-700">Operativos</span>
              <Tag :value="kpis?.inventario?.operativos ?? 0" severity="success" />
            </div>
            <Divider />
            <div class="flex justify-between items-center p-2 bg-red-50 rounded">
              <span class="text-gray-700">Inactivos</span>
              <Tag :value="kpis?.inventario?.inactivos ?? 0" severity="danger" />
            </div>
            <Divider />
            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
              <span class="text-gray-700">% Activos</span>
              <Tag :value="`${kpis?.inventario?.porcentaje_activo ?? 0}%`" :severity="(kpis?.inventario?.porcentaje_activo ?? 0) >= 70 ? 'success' : 'warn'" />
            </div>
          </div>
        </template>
      </Card>

      <Card class="shadow-md border-t-4 border-t-yellow-500">
        <template #title>
          <div class="flex items-center gap-2">
            <i class="pi pi-exclamation-triangle text-yellow-500"></i>
            <span class="font-semibold">KPI Incidentes</span>
          </div>
        </template>
        <template #content>
          <div class="flex flex-col gap-3">
            <div class="flex justify-between items-center p-2 bg-yellow-50 rounded">
              <span class="text-gray-700">Tiempo Promedio Resolución</span>
              <Tag :value="kpis?.incidentes?.tiempo_promedio ?? 'N/A'" severity="warn" />
            </div>
            <Divider />
            <div class="flex justify-between items-center p-2 bg-blue-50 rounded">
              <span class="text-gray-700">Incidentes por Mes</span>
              <Tag :value="kpis?.incidentes?.por_mes ?? 0" severity="info" />
            </div>
            <Divider />
            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
              <span class="text-gray-700">Incidentes por Área</span>
              <Tag :value="kpis?.incidentes?.por_area ?? 0" severity="info" />
            </div>
            <Divider />
            <div class="flex justify-between items-center p-2 bg-green-50 rounded">
              <span class="text-gray-700">Tasa de Resolución</span>
              <Tag :value="`${kpis?.incidentes?.tasa_resolucion ?? 0}%`" :severity="(kpis?.incidentes?.tasa_resolucion ?? 0) >= 70 ? 'success' : 'danger'" />
            </div>
          </div>
        </template>
      </Card>

      <Card class="shadow-md border-t-4 border-t-orange-500">
        <template #title>
          <div class="flex items-center gap-2">
            <i class="pi pi-wrench text-orange-500"></i>
            <span class="font-semibold">KPI Mantenimiento</span>
          </div>
        </template>
        <template #content>
          <div class="flex flex-col gap-3">
            <div class="flex justify-between items-center p-2 bg-orange-50 rounded">
              <span class="text-gray-700">% Equipos Mantenidos</span>
              <Tag :value="`${kpis?.mantenimiento?.porcentaje ?? 0}%`" :severity="(kpis?.mantenimiento?.porcentaje ?? 0) >= 80 ? 'success' : 'warn'" />
            </div>
            <Divider />
            <div class="flex justify-between items-center p-2 bg-green-50 rounded">
              <span class="text-gray-700">Realizados</span>
              <Tag :value="kpis?.mantenimiento?.realizados ?? 0" severity="success" />
            </div>
            <Divider />
            <div class="flex justify-between items-center p-2 bg-red-50 rounded">
              <span class="text-gray-700">Equipos Pendientes</span>
              <Tag :value="kpis?.mantenimiento?.pendientes ?? 0" :severity="(kpis?.mantenimiento?.pendientes ?? 0) > 0 ? 'danger' : 'success'" />
            </div>
          </div>
        </template>
      </Card>

      <Card class="shadow-md border-t-4 border-t-purple-500">
        <template #title>
          <div class="flex items-center gap-2">
            <i class="pi pi-database text-purple-500"></i>
            <span class="font-semibold">KPI Respaldo</span>
          </div>
        </template>
        <template #content>
          <div class="flex flex-col gap-3">
            <div class="flex justify-between items-center p-2 bg-purple-50 rounded">
              <span class="text-gray-700">% Equipos Respaldados</span>
              <Tag :value="`${kpis?.respaldo?.porcentaje ?? 0}%`" :severity="(kpis?.respaldo?.porcentaje ?? 0) >= 80 ? 'success' : 'warn'" />
            </div>
            <Divider />
            <div class="flex justify-between items-center p-2 bg-blue-50 rounded">
              <span class="text-gray-700">Último Respaldo</span>
              <Tag :value="kpis?.respaldo?.ultimo ?? 'N/A'" severity="info" />
            </div>
            <Divider />
            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
              <span class="text-gray-700">Respaldos por Mes</span>
              <Tag :value="kpis?.respaldo?.por_mes ?? 0" severity="info" />
            </div>
          </div>
        </template>
      </Card>
    </div>
  </div>
</template>

<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from 'primevue/card'
import Tag from 'primevue/tag'
import Divider from 'primevue/divider'

defineOptions({ layout: DashboardLayout })

defineProps({
  kpis: Object,
})
</script>
