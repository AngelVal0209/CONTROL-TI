<template>
  <div class="flex flex-col gap-6">
    <div class="flex items-center gap-2">
      <Button icon="pi pi-arrow-left" severity="secondary" text @click="router.get('/respaldos/bd')" />
      <h1 class="text-2xl font-bold">Detalle de Respaldo de BD</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
      <Card>
        <template #title>Información del Respaldo</template>
        <template #content>
          <div class="flex flex-col gap-2">
            <div class="flex justify-between"><span class="font-medium">Nombre:</span><span>{{ respaldo.nombre }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Descripción:</span><span>{{ respaldo.descripcion || '—' }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Fecha:</span><span>{{ respaldo.fecha_respaldo || '—' }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Tamaño:</span><span>{{ respaldo.tamano || '—' }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Responsable:</span><span>{{ respaldo.responsable || '—' }}</span></div>
            <Divider />
            <div><span class="font-medium">Observaciones:</span></div>
            <p class="text-gray-700 whitespace-pre-wrap">{{ respaldo.observaciones || 'Sin observaciones' }}</p>
          </div>
        </template>
      </Card>

      <Card>
        <template #title>Archivo</template>
        <template #content>
          <div class="flex flex-col gap-3 items-center justify-center py-6">
            <i class="pi pi-file text-4xl text-gray-400"></i>
            <p class="text-gray-500">{{ respaldo.archivo ? respaldo.archivo.split('/').pop() : 'Sin archivo adjunto' }}</p>
          </div>
        </template>
        <template #footer>
          <Button v-if="respaldo.archivo" label="Descargar Archivo" icon="pi pi-download" class="w-full" severity="info" @click="router.get(`/respaldos/bd/${respaldo.id}/download`)" />
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
import Divider from 'primevue/divider'

defineOptions({ layout: DashboardLayout })

defineProps({
  respaldo: Object,
})
</script>
