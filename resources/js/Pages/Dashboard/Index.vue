<template>
  <div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-gray-500 text-sm">Panel de control del sistema TI</p>
      </div>
      <div class="flex gap-2">
        <Button label="KPIs" icon="pi pi-chart-bar" severity="info" text @click="router.get('/kpis')" />
        <Button label="Reportes" icon="pi pi-file-pdf" severity="secondary" text @click="router.get('/reportes')" />
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
      <Card class="shadow-md border-l-4 border-l-blue-500">
        <template #content>
          <div class="flex items-center justify-between">
            <div>
              <span class="text-gray-500 text-sm font-medium">Total Equipos</span>
              <p class="text-3xl font-bold text-gray-800 mt-1">{{ totalEquipos ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
              <i class="pi pi-desktop text-blue-500 text-xl"></i>
            </div>
          </div>
          <div class="flex gap-2 mt-3 text-xs">
            <Tag :value="`${equiposActivos ?? 0} activos`" severity="success" />
            <Tag :value="`${equiposInactivos ?? 0} inactivos`" severity="danger" />
          </div>
        </template>
      </Card>

      <Card class="shadow-md border-l-4 border-l-yellow-500">
        <template #content>
          <div class="flex items-center justify-between">
            <div>
              <span class="text-gray-500 text-sm font-medium">Incidentes</span>
              <p class="text-3xl font-bold text-gray-800 mt-1">{{ incidentesPendientes ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
              <i class="pi pi-exclamation-triangle text-yellow-500 text-xl"></i>
            </div>
          </div>
          <div class="flex gap-2 mt-3 text-xs">
            <Tag :value="`${incidentesResueltos ?? 0} resueltos`" severity="success" />
            <Tag :value="`${incidentesEnProceso ?? 0} en proceso`" severity="warn" />
          </div>
        </template>
      </Card>

      <Card class="shadow-md border-l-4 border-l-green-500">
        <template #content>
          <div class="flex items-center justify-between">
            <div>
              <span class="text-gray-500 text-sm font-medium">Mtto. Realizados</span>
              <p class="text-3xl font-bold text-gray-800 mt-1">{{ mantenimientos ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
              <i class="pi pi-wrench text-green-500 text-xl"></i>
            </div>
          </div>
          <div class="flex gap-2 mt-3 text-xs">
            <Tag :value="`${kpis?.mantenimiento?.porcentaje ?? 0}% efectividad`" :severity="(kpis?.mantenimiento?.porcentaje ?? 0) >= 80 ? 'success' : 'warn'" />
          </div>
        </template>
      </Card>

      <Card class="shadow-md border-l-4 border-l-purple-500">
        <template #content>
          <div class="flex items-center justify-between">
            <div>
              <span class="text-gray-500 text-sm font-medium">Respaldos</span>
              <p class="text-3xl font-bold text-gray-800 mt-1">{{ respaldos ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
              <i class="pi pi-database text-purple-500 text-xl"></i>
            </div>
          </div>
          <div class="flex gap-2 mt-3 text-xs">
            <Tag :value="`${kpis?.respaldo?.por_mes ?? 0} este mes`" severity="info" />
          </div>
        </template>
      </Card>
    </div>

    <h2 class="text-xl font-bold text-gray-800 mt-2">Indicadores Clave (KPIs)</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <Card class="shadow-md">
        <template #title>
          <div class="flex items-center gap-2">
            <i class="pi pi-desktop text-blue-500"></i>
            <span class="font-semibold">KPI Inventario</span>
          </div>
        </template>
        <template #content>
          <div class="flex flex-col gap-3">
            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
              <span class="text-gray-700">Total Equipos</span>
              <Tag :value="kpis?.inventario?.total ?? 0" severity="info" />
            </div>
            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
              <span class="text-gray-700">Operativos</span>
              <Tag :value="kpis?.inventario?.operativos ?? 0" severity="success" />
            </div>
            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
              <span class="text-gray-700">Inactivos</span>
              <Tag :value="kpis?.inventario?.inactivos ?? 0" severity="danger" />
            </div>
            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
              <span class="text-gray-700">% Activos</span>
              <Tag :value="`${kpis?.inventario?.porcentaje_activo ?? 0}%`" :severity="(kpis?.inventario?.porcentaje_activo ?? 0) >= 70 ? 'success' : 'warn'" />
            </div>
          </div>
        </template>
      </Card>

      <Card class="shadow-md">
        <template #title>
          <div class="flex items-center gap-2">
            <i class="pi pi-exclamation-triangle text-yellow-500"></i>
            <span class="font-semibold">KPI Incidentes</span>
          </div>
        </template>
        <template #content>
          <div class="flex flex-col gap-3">
            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
              <span class="text-gray-700">Tiempo Prom. Resolución</span>
              <Tag :value="kpis?.incidentes?.tiempo_promedio ?? 'N/A'" severity="warn" />
            </div>
            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
              <span class="text-gray-700">Incidentes por Mes</span>
              <Tag :value="kpis?.incidentes?.por_mes ?? 0" severity="info" />
            </div>
            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
              <span class="text-gray-700">Incidentes por Área</span>
              <Tag :value="kpis?.incidentes?.por_area ?? 0" severity="info" />
            </div>
            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
              <span class="text-gray-700">Tasa de Resolución</span>
              <Tag :value="`${kpis?.incidentes?.tasa_resolucion ?? 0}%`" :severity="(kpis?.incidentes?.tasa_resolucion ?? 0) >= 70 ? 'success' : 'danger'" />
            </div>
          </div>
        </template>
      </Card>

      <Card class="shadow-md">
        <template #title>
          <div class="flex items-center gap-2">
            <i class="pi pi-wrench text-orange-500"></i>
            <span class="font-semibold">KPI Mantenimiento</span>
          </div>
        </template>
        <template #content>
          <div class="flex flex-col gap-3">
            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
              <span class="text-gray-700">% Equipos Mantenidos</span>
              <Tag :value="`${kpis?.mantenimiento?.porcentaje ?? 0}%`" :severity="(kpis?.mantenimiento?.porcentaje ?? 0) >= 80 ? 'success' : 'warn'" />
            </div>
            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
              <span class="text-gray-700">Realizados</span>
              <Tag :value="kpis?.mantenimiento?.realizados ?? 0" severity="success" />
            </div>
            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
              <span class="text-gray-700">Equipos Pendientes</span>
              <Tag :value="kpis?.mantenimiento?.pendientes ?? 0" :severity="(kpis?.mantenimiento?.pendientes ?? 0) > 0 ? 'danger' : 'success'" />
            </div>
          </div>
        </template>
      </Card>

      <Card class="shadow-md">
        <template #title>
          <div class="flex items-center gap-2">
            <i class="pi pi-database text-purple-500"></i>
            <span class="font-semibold">KPI Respaldo</span>
          </div>
        </template>
        <template #content>
          <div class="flex flex-col gap-3">
            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
              <span class="text-gray-700">% Equipos Respaldados</span>
              <Tag :value="`${kpis?.respaldo?.porcentaje ?? 0}%`" :severity="(kpis?.respaldo?.porcentaje ?? 0) >= 80 ? 'success' : 'warn'" />
            </div>
            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
              <span class="text-gray-700">Último Respaldo</span>
              <Tag :value="kpis?.respaldo?.ultimo ?? 'N/A'" severity="info" />
            </div>
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
import { router } from '@inertiajs/vue3'
import Card from 'primevue/card'
import Tag from 'primevue/tag'
import Button from 'primevue/button'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

defineOptions({ layout: DashboardLayout })

defineProps({
  totalEquipos: Number,
  equiposActivos: Number,
  equiposInactivos: Number,
  equiposMantenimiento: Number,
  equiposBaja: Number,
  incidentesPendientes: Number,
  incidentesEnProceso: Number,
  incidentesResueltos: Number,
  respaldos: Number,
  mantenimientos: Number,
  kpis: Object,
})
</script>
