<template>
  <div class="flex flex-col gap-6">
    <div class="flex items-center gap-2">
      <Button icon="pi pi-arrow-left" severity="secondary" text @click="router.get('/mantenimientos')" />
      <h1 class="text-2xl font-bold">Detalle de Mantenimiento</h1>
      <Tag :value="mantenimiento.estado" :severity="mantenimiento.estado === 'realizado' ? 'success' : mantenimiento.estado === 'pendiente' ? 'warn' : 'info'" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
      <Card>
        <template #title>Información del Mantenimiento</template>
        <template #content>
          <div class="flex flex-col gap-2">
            <div class="flex justify-between"><span class="font-medium">Tipo:</span><Tag :value="mantenimiento.tipo" /></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Estado:</span><Tag :value="mantenimiento.estado" :severity="mantenimiento.estado === 'realizado' ? 'success' : 'warn'" /></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Técnico:</span><span>{{ mantenimiento.tecnico || '—' }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Fecha Programada:</span><span>{{ mantenimiento.fecha_programada || '—' }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Fecha Realizada:</span><span>{{ mantenimiento.fecha_realizada || '—' }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Costo:</span><span>{{ mantenimiento.costo ? `$${mantenimiento.costo}` : '—' }}</span></div>
            <Divider />
            <div><span class="font-medium">Descripción:</span></div>
            <p class="text-gray-700 whitespace-pre-wrap">{{ mantenimiento.descripcion || 'Sin descripción' }}</p>
          </div>
        </template>
      </Card>

      <Card>
        <template #title>Equipo Asociado</template>
        <template #content>
          <div class="flex flex-col gap-2">
            <div class="flex justify-between"><span class="font-medium">Nombre:</span><span>{{ mantenimiento.equipo?.nombre_equipo || '—' }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Código:</span><span>{{ mantenimiento.equipo?.codigo || '—' }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Serie:</span><span>{{ mantenimiento.equipo?.serie || '—' }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Área:</span><span>{{ mantenimiento.equipo?.area || '—' }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Marca / Modelo:</span><span>{{ mantenimiento.equipo?.marca || '—' }} {{ mantenimiento.equipo?.modelo || '' }}</span></div>
          </div>
        </template>
        <template #footer>
          <Button label="Ver Equipo" icon="pi pi-external-link" class="w-full" severity="secondary" @click="router.get(`/equipos/${mantenimiento.equipo.id}`)" />
        </template>
      </Card>
    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from 'primevue/card'
import Button from 'primevue/button'
import Tag from 'primevue/tag'
import Divider from 'primevue/divider'

defineOptions({ layout: DashboardLayout })

defineProps({
  mantenimiento: Object,
})
</script>

