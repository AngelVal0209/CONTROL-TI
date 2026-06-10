<template>
  <div class="flex flex-col gap-6">
    <div class="flex items-center gap-2">
      <Button icon="pi pi-arrow-left" severity="secondary" text @click="router.get('/respaldos')" />
      <h1 class="text-2xl font-bold">Detalle de Respaldo</h1>
      <Tag :value="respaldo.tipo" :severity="respaldo.tipo === 'completo' ? 'success' : respaldo.tipo === 'incremental' ? 'info' : 'warn'" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
      <Card>
        <template #title>Información del Respaldo</template>
        <template #content>
          <div class="flex flex-col gap-2">
            <div class="flex justify-between"><span class="font-medium">Tipo:</span><Tag :value="respaldo.tipo" :severity="respaldo.tipo === 'completo' ? 'success' : respaldo.tipo === 'incremental' ? 'info' : 'warn'" /></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Ubicación:</span><span>{{ respaldo.ubicacion || '—' }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Fecha:</span><span>{{ respaldo.fecha_respaldo || '—' }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Tamaño:</span><span>{{ respaldo.tamano || '—' }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Responsable:</span><span>{{ respaldo.responsable || '—' }}</span></div>
            <Divider />
            <div v-if="respaldo.archivo" class="flex justify-between items-center">
              <span class="font-medium">Archivo:</span>
              <Button label="Descargar" icon="pi pi-download" severity="info" text @click="download(respaldo)" />
            </div>
            <Divider v-if="respaldo.archivo" />
            <div><span class="font-medium">Observaciones:</span></div>
            <p class="text-gray-700 whitespace-pre-wrap">{{ respaldo.observaciones || 'Sin observaciones' }}</p>
          </div>
        </template>
      </Card>

      <Card>
        <template #title>Equipo Asociado</template>
        <template #content>
          <div class="flex flex-col gap-2">
            <div class="flex justify-between"><span class="font-medium">Nombre:</span><span>{{ respaldo.equipo?.nombre_equipo || '—' }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Código:</span><span>{{ respaldo.equipo?.codigo || '—' }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Área:</span><span>{{ respaldo.equipo?.area || '—' }}</span></div>
          </div>
        </template>
        <template #footer>
          <Button label="Ver Equipo" icon="pi pi-external-link" class="w-full" severity="secondary" @click="router.get(`/equipos/${respaldo.equipo.id}`)" />
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

const props = defineProps({
  respaldo: Object,
})

function download(respaldo) {
  window.open(`/respaldos/${respaldo.id}/download`, '_blank')
}
</script>

