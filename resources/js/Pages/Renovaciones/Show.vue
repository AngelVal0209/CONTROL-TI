<template>
  <div class="flex flex-col gap-6">
    <div class="flex items-center gap-2">
      <Button icon="pi pi-arrow-left" severity="secondary" text @click="router.get('/renovaciones')" />
      <h1 class="text-2xl font-bold">{{ renovacion.nombre }}</h1>
      <Tag :value="renovacion.estado" :severity="estadoSeverity(renovacion.estado)" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
      <Card>
        <template #title>Información de la Renovación</template>
        <template #content>
          <div class="flex flex-col gap-2">
            <div class="flex justify-between"><span class="font-medium">Nombre:</span><span>{{ renovacion.nombre }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Proveedor:</span><span>{{ renovacion.proveedor || '—' }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Tipo:</span><Tag :value="renovacion.tipo" severity="info" /></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Período:</span><span>{{ renovacion.periodo || '—' }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Monto:</span><span>{{ renovacion.monto ? `${renovacion.moneda || 'PEN'} ${Number(renovacion.monto).toFixed(2)}` : '—' }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Fecha de Inicio:</span><span>{{ renovacion.fecha_inicio || '—' }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Fecha de Vencimiento:</span>
              <span :class="{ 'text-red-600 font-bold': diasRestantes <= 30 && renovacion.estado === 'activo' }">
                {{ renovacion.fecha_vencimiento }}
                <Tag v-if="diasRestantes <= 30 && renovacion.estado === 'activo'" :value="`${diasRestantes} días`" severity="warn" class="ml-2" />
              </span>
            </div>
            <Divider />
            <div v-if="renovacion.archivo" class="flex justify-between items-center">
              <span class="font-medium">Archivo:</span>
              <Button label="Descargar" icon="pi pi-download" severity="info" text @click="descargar" />
            </div>
            <Divider v-if="renovacion.archivo" />
            <div><span class="font-medium">Descripción:</span></div>
            <p class="text-gray-700 whitespace-pre-wrap">{{ renovacion.descripcion || 'Sin descripción' }}</p>
            <Divider />
            <div><span class="font-medium">Observaciones:</span></div>
            <p class="text-gray-700 whitespace-pre-wrap">{{ renovacion.observaciones || 'Sin observaciones' }}</p>
          </div>
        </template>
      </Card>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from 'primevue/card'
import Button from 'primevue/button'
import Tag from 'primevue/tag'
import Divider from 'primevue/divider'

defineOptions({ layout: DashboardLayout })

const props = defineProps({
  renovacion: Object,
})

const diasRestantes = computed(() => {
  if (!props.renovacion?.fecha_vencimiento) return 999
  const hoy = new Date()
  const ven = new Date(props.renovacion.fecha_vencimiento)
  return Math.ceil((ven - hoy) / (1000 * 60 * 60 * 24))
})

function estadoSeverity(estado) {
  const map = { activo: 'success', vencido: 'danger', renovado: 'info', cancelado: 'warn' }
  return map[estado] || null
}

function descargar() {
  window.open(`/renovaciones/${props.renovacion.id}/download`, '_blank')
}
</script>
