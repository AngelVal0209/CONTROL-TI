<template>
  <div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">Indicadores KPIs</h1>
        <p class="text-gray-500 text-sm">Métricas de rendimiento del sistema</p>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <Card>
        <template #title>
          <div class="flex items-center gap-2">
            <i class="pi pi-desktop text-blue-500"></i>
            <span class="font-semibold">Inventario de Equipos</span>
          </div>
        </template>
        <template #content>
          <div class="flex flex-col items-center">
            <Chart type="doughnut" :data="inventarioChart" :options="doughnutOptions" class="w-full max-h-64" />
            <div class="flex justify-center gap-4 mt-3 text-sm">
              <span v-for="e in (kpis?.inventario?.estados ?? [])" :key="e.label" class="flex items-center gap-1">
                <span class="w-3 h-3 rounded-full inline-block" :style="{ background: color(e.label) }"></span>
                {{ e.label }}: <strong>{{ e.value }}</strong>
              </span>
            </div>
            <Divider />
            <div class="flex justify-between w-full text-sm">
              <span>Total Equipos: <strong>{{ kpis?.inventario?.total ?? 0 }}</strong></span>
              <Tag :value="`${kpis?.inventario?.porcentaje_activo ?? 0}% activos`" :severity="(kpis?.inventario?.porcentaje_activo ?? 0) >= 70 ? 'success' : 'warn'" />
            </div>
          </div>
        </template>
      </Card>

      <Card>
        <template #title>
          <div class="flex items-center gap-2">
            <i class="pi pi-exclamation-triangle text-yellow-500"></i>
            <span class="font-semibold">Incidentes</span>
          </div>
        </template>
        <template #content>
          <div class="flex flex-col items-center">
            <Chart type="bar" :data="incidentesChart" :options="barOptions" class="w-full max-h-64" />
            <Divider />
            <div class="grid grid-cols-2 gap-2 w-full text-sm">
              <div class="p-2 bg-gray-50 rounded flex justify-between">
                <span>Tasa Resolución</span>
                <Tag :value="`${kpis?.incidentes?.tasa_resolucion ?? 0}%`" :severity="(kpis?.incidentes?.tasa_resolucion ?? 0) >= 70 ? 'success' : 'danger'" />
              </div>
              <div class="p-2 bg-gray-50 rounded flex justify-between">
                <span>Tiempo Prom.</span>
                <Tag :value="kpis?.incidentes?.tiempo_promedio ?? 'N/A'" severity="warn" />
              </div>
            </div>
          </div>
        </template>
      </Card>

      <Card>
        <template #title>
          <div class="flex items-center gap-2">
            <i class="pi pi-wrench text-orange-500"></i>
            <span class="font-semibold">Mantenimiento</span>
          </div>
        </template>
        <template #content>
          <Chart type="doughnut" :data="mantenimientoChart" :options="doughnutOptions" class="w-full max-h-64" />
          <Divider />
          <div class="flex justify-between w-full text-sm">
            <span>Realizados: <strong>{{ kpis?.mantenimiento?.realizados ?? 0 }}</strong></span>
            <Tag :value="`${kpis?.mantenimiento?.porcentaje ?? 0}% efectividad`" :severity="(kpis?.mantenimiento?.porcentaje ?? 0) >= 80 ? 'success' : 'warn'" />
          </div>
        </template>
      </Card>

      <Card>
        <template #title>
          <div class="flex items-center gap-2">
            <i class="pi pi-database text-purple-500"></i>
            <span class="font-semibold">Respaldos</span>
          </div>
        </template>
        <template #content>
          <Chart type="doughnut" :data="respaldoChart" :options="doughnutOptions" class="w-full max-h-64" />
          <Divider />
          <div class="grid grid-cols-2 gap-2 w-full text-sm">
            <div class="p-2 bg-gray-50 rounded flex justify-between">
              <span>% Respaldados</span>
              <Tag :value="`${kpis?.respaldo?.porcentaje ?? 0}%`" :severity="(kpis?.respaldo?.porcentaje ?? 0) >= 80 ? 'success' : 'warn'" />
            </div>
            <div class="p-2 bg-gray-50 rounded flex justify-between">
              <span>Último</span>
              <Tag :value="kpis?.respaldo?.ultimo ?? 'N/A'" severity="info" />
            </div>
          </div>
        </template>
      </Card>

      <Card class="lg:col-span-2">
        <template #title>
          <div class="flex items-center gap-2">
            <i class="pi pi-chart-line text-blue-500"></i>
            <span class="font-semibold">Tendencias Mensuales</span>
          </div>
        </template>
        <template #content>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <h4 class="text-sm text-gray-500 mb-2">Incidentes por Mes</h4>
              <Chart type="line" :data="incidentesTrend" :options="lineOptions" class="w-full max-h-48" />
            </div>
            <div>
              <h4 class="text-sm text-gray-500 mb-2">Mantenimientos por Mes</h4>
              <Chart type="line" :data="mantenimientoTrend" :options="lineOptions" class="w-full max-h-48" />
            </div>
            <div>
              <h4 class="text-sm text-gray-500 mb-2">Respaldos por Mes</h4>
              <Chart type="line" :data="respaldoTrend" :options="lineOptions" class="w-full max-h-48" />
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
import Chart from 'primevue/chart'
import { computed } from 'vue'

defineOptions({ layout: DashboardLayout })

const props = defineProps({
  kpis: Object,
})

const colors = {
  'Operativos': '#22c55e',
  'Inactivos': '#ef4444',
  'En Mantenimiento': '#f59e0b',
  'De Baja': '#6b7280',
  'Pendientes': '#f59e0b',
  'En Proceso': '#3b82f6',
  'Resueltos': '#22c55e',
  'Realizados': '#22c55e',
}

function color(label) {
  return colors[label] ?? '#6b7280'
}

const chartColors = ['#22c55e', '#ef4444', '#f59e0b', '#6b7280']

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
      backgroundColor: chartColors.slice(0, estados.length),
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

const mantenimientoChart = computed(() => {
  const estados = props.kpis?.mantenimiento?.estados ?? []
  return {
    labels: estados.map(e => e.label),
    datasets: [{
      data: estados.map(e => e.value),
      backgroundColor: ['#22c55e', '#ef4444'],
      borderWidth: 0,
    }],
  }
})

const respaldoChart = computed(() => {
  const porc = props.kpis?.respaldo?.porcentaje ?? 0
  return {
    labels: ['Respaldados', 'Sin Respaldo'],
    datasets: [{
      data: [porc, 100 - porc],
      backgroundColor: ['#a855f7', '#e5e7eb'],
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
