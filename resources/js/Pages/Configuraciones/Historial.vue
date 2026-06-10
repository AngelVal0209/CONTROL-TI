<template>
  <div class="flex flex-col gap-4">
    <div class="flex items-center gap-2">
      <Button icon="pi pi-arrow-left" severity="secondary" text @click="router.get(`/equipos/${configuracion.equipo.id}/configuraciones`)" />
      <h1 class="text-2xl font-bold">Historial de Configuración</h1>
      <span class="text-gray-500">- {{ configuracion.equipo?.nombre_equipo }}</span>
    </div>

    <Card>
      <template #title>Configuración Actual</template>
      <template #content>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div><span class="font-medium block text-sm text-gray-500">SO</span><span>{{ configuracion.sistema_operativo || '—' }}</span></div>
          <div><span class="font-medium block text-sm text-gray-500">Versión</span><span>{{ configuracion.version || '—' }}</span></div>
          <div><span class="font-medium block text-sm text-gray-500">Office</span><span>{{ configuracion.office || '—' }}</span></div>
          <div><span class="font-medium block text-sm text-gray-500">Antivirus</span><span>{{ configuracion.antivirus || '—' }}</span></div>
          <div><span class="font-medium block text-sm text-gray-500">CPU</span><span>{{ configuracion.cpu || '—' }}</span></div>
          <div><span class="font-medium block text-sm text-gray-500">RAM</span><span>{{ configuracion.ram || '—' }}</span></div>
          <div><span class="font-medium block text-sm text-gray-500">Disco</span><span>{{ configuracion.disco || '—' }}</span></div>
          <div><span class="font-medium block text-sm text-gray-500">Última Actualización</span><span>{{ configuracion.fecha_actualizacion || '—' }}</span></div>
        </div>
      </template>
    </Card>

    <DataTable :value="historial.data" paginator :rows="historial.per_page" :totalRecords="historial.total" :rowsPerPageOptions="[10, 25, 50]" :first="(historial.current_page - 1) * historial.per_page">
      <Column field="campo_modificado" header="Campo Modificado" sortable></Column>
      <Column field="valor_anterior" header="Valor Anterior" sortable>
        <template #body="{ data }">
          <span class="text-red-600">{{ data.valor_anterior || '—' }}</span>
        </template>
      </Column>
      <Column field="valor_nuevo" header="Valor Nuevo" sortable>
        <template #body="{ data }">
          <span class="text-green-600">{{ data.valor_nuevo || '—' }}</span>
        </template>
      </Column>
      <Column field="usuario.name" header="Modificado Por" sortable></Column>
      <Column field="created_at" header="Fecha" sortable>
        <template #body="{ data }">
          {{ new Date(data.created_at).toLocaleString('es-ES') }}
        </template>
      </Column>
    </DataTable>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Button from 'primevue/button'
import Card from 'primevue/card'

defineOptions({ layout: DashboardLayout })

defineProps({
  configuracion: Object,
  historial: Object,
})
</script>

