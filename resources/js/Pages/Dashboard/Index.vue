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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <Card class="shadow-md">
        <template #title>
          <div class="flex items-center gap-2">
            <i class="pi pi-desktop text-blue-500"></i>
            <span class="font-semibold">Estado de Equipos</span>
          </div>
        </template>
        <template #content>
          <Chart type="doughnut" :data="inventarioChart" :options="doughnutOptions" class="w-full max-h-56" />
          <div class="flex flex-wrap justify-center gap-3 mt-3 text-xs">
            <span v-for="e in (kpis?.inventario?.estados ?? [])" :key="e.label" class="flex items-center gap-1">
              <span class="w-3 h-3 rounded-full inline-block" :style="{ background: color(e.label) }"></span>
              {{ e.label }}: <strong>{{ e.value }}</strong>
            </span>
          </div>
        </template>
      </Card>

      <Card class="shadow-md">
        <template #title>
          <div class="flex items-center gap-2">
            <i class="pi pi-exclamation-triangle text-yellow-500"></i>
            <span class="font-semibold">Incidentes por Estado</span>
          </div>
        </template>
        <template #content>
          <Chart type="bar" :data="incidentesChart" :options="barOptions" class="w-full max-h-56" />
          <div class="flex justify-center gap-4 mt-3 text-xs">
            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full inline-block bg-amber-500"></span> Pendientes: <strong>{{ kpis?.incidentes?.estados?.[0]?.value ?? 0 }}</strong></span>
            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full inline-block bg-blue-500"></span> En Proceso: <strong>{{ kpis?.incidentes?.estados?.[1]?.value ?? 0 }}</strong></span>
            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full inline-block bg-green-500"></span> Resueltos: <strong>{{ kpis?.incidentes?.estados?.[2]?.value ?? 0 }}</strong></span>
          </div>
        </template>
      </Card>

      <Card class="shadow-md lg:col-span-2">
        <template #title>
          <div class="flex items-center gap-2">
            <i class="pi pi-chart-line text-blue-500"></i>
            <span class="font-semibold">Tendencias Mensuales</span>
          </div>
        </template>
        <template #content>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <h4 class="text-xs text-gray-500 mb-1">Incidentes</h4>
              <Chart type="line" :data="incidentesTrend" :options="lineOptions" class="w-full max-h-40" />
            </div>
            <div>
              <h4 class="text-xs text-gray-500 mb-1">Mantenimientos</h4>
              <Chart type="line" :data="mantenimientoTrend" :options="lineOptions" class="w-full max-h-40" />
            </div>
            <div>
              <h4 class="text-xs text-gray-500 mb-1">Respaldos</h4>
              <Chart type="line" :data="respaldoTrend" :options="lineOptions" class="w-full max-h-40" />
            </div>
          </div>
        </template>
      </Card>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'
import Card from 'primevue/card'
import Tag from 'primevue/tag'
import Button from 'primevue/button'
import Chart from 'primevue/chart'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

defineOptions({ layout: DashboardLayout })

const props = defineProps({
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

const colors = {
  'Operativos': '#22c55e',
  'Inactivos': '#ef4444',
  'En Mantenimiento': '#f59e0b',
  'De Baja': '#6b7280',
}

function color(label) {
  return colors[label] ?? '#6b7280'
}

const doughnutOptions = {
  responsive: true,
  maintainAspectRatio: true,
  plugins: { legend: { display: false } },
  cutout: '60%',
}

const barOptions = {
  responsive: true,
  maintainAspectRatio: true,
  plugins: { legend: { display: false } },
  scales: {
    y: { beginAtZero: true, ticks: { stepSize: 1 } },
  },
}

const lineOptions = {
  responsive: true,
  maintainAspectRatio: true,
  plugins: { legend: { display: false } },
  scales: {
    y: { beginAtZero: true, ticks: { stepSize: 1 } },
  },
}

const inventarioChart = computed(() => {
  const estados = props.kpis?.inventario?.estados ?? []
  return {
    labels: estados.map(e => e.label),
    datasets: [{
      data: estados.map(e => e.value),
      backgroundColor: ['#22c55e', '#ef4444', '#f59e0b', '#6b7280'],
      borderWidth: 0,
    }],
  }
})

const incidentesChart = computed(() => {
  const estados = props.kpis?.incidentes?.estados ?? []
  return {
    labels: estados.map(e => e.label),
    datasets: [{
      data: estados.map(e => e.value),
      backgroundColor: ['#f59e0b', '#3b82f6', '#22c55e'],
      borderWidth: 0,
    }],
  }
})

const incidentesTrend = computed(() => {
  const t = props.kpis?.incidentes?.tendencias ?? { labels: [], data: [] }
  return {
    labels: t.labels,
    datasets: [{
      data: t.data,
      borderColor: '#3b82f6',
      backgroundColor: 'rgba(59,130,246,0.1)',
      fill: true,
      tension: 0.4,
    }],
  }
})

const mantenimientoTrend = computed(() => {
  const t = props.kpis?.mantenimiento?.tendencias ?? { labels: [], data: [] }
  return {
    labels: t.labels,
    datasets: [{
      data: t.data,
      borderColor: '#f59e0b',
      backgroundColor: 'rgba(245,158,11,0.1)',
      fill: true,
      tension: 0.4,
    }],
  }
})

const respaldoTrend = computed(() => {
  const t = props.kpis?.respaldo?.tendencias ?? { labels: [], data: [] }
  return {
    labels: t.labels,
    datasets: [{
      data: t.data,
      borderColor: '#a855f7',
      backgroundColor: 'rgba(168,85,247,0.1)',
      fill: true,
      tension: 0.4,
    }],
  }
})
</script>
