<template>
  <div class="flex flex-col gap-4">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold">Auditoría</h1>
      <Tag :value="`${auditorias.total ?? 0} registros`" severity="info" />
    </div>

    <div class="flex flex-wrap gap-4 items-center">
      <Dropdown v-model="filters.modulo" :options="moduloOptions" placeholder="Filtrar por Módulo" showClear class="w-48" @change="applyFilters" />
      <Dropdown v-model="filters.usuario_id" :options="usuarios" optionLabel="name" optionValue="id" placeholder="Filtrar por Usuario" showClear class="w-48" @change="applyFilters" />
      <Calendar v-model="filters.fecha_desde" placeholder="Fecha desde" showIcon dateFormat="dd/mm/yy" class="w-40" @date-select="applyFilters" @clear="applyFilters" />
      <Calendar v-model="filters.fecha_hasta" placeholder="Fecha hasta" showIcon dateFormat="dd/mm/yy" class="w-40" @date-select="applyFilters" @clear="applyFilters" />
    </div>

    <DataTable :value="auditorias.data" :lazy="true" :totalRecords="auditorias.total" :rows="auditorias.per_page" :first="(auditorias.current_page - 1) * auditorias.per_page" paginator :rowsPerPageOptions="[10, 25, 50]" @page="onPage" class="shadow-sm">
      <Column field="usuario.name" header="Usuario" sortable></Column>
      <Column field="accion" header="Acción" sortable>
        <template #body="{ data }">
          <Tag :value="data.accion" :severity="data.accion === 'crear' ? 'success' : data.accion === 'actualizar' ? 'info' : data.accion === 'eliminar' ? 'danger' : 'warn'" />
        </template>
      </Column>
      <Column field="modulo" header="Módulo" sortable>
        <template #body="{ data }">
          <Tag :value="data.modulo" :severity="moduloSeverity(data.modulo)" />
        </template>
      </Column>
      <Column field="registro_id" header="Registro ID" sortable></Column>
      <Column field="detalle" header="Detalle" sortable>
        <template #body="{ data }">
          <span class="text-sm truncate block max-w-xs">{{ data.detalle }}</span>
        </template>
      </Column>
      <Column field="created_at" header="Fecha" sortable>
        <template #body="{ data }">
          {{ new Date(data.created_at).toLocaleString('es-ES') }}
        </template>
      </Column>
    </DataTable>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Dropdown from 'primevue/dropdown'
import Tag from 'primevue/tag'
import Calendar from 'primevue/calendar'

defineOptions({ layout: DashboardLayout })

const props = defineProps({
  auditorias: Object,
  usuarios: Array,
  modulos: Array,
})

const moduloOptions = props.modulos || ['equipos', 'incidentes', 'configuraciones', 'mantenimientos', 'respaldos', 'usuarios']

const filters = reactive({
  modulo: null,
  usuario_id: null,
  fecha_desde: null,
  fecha_hasta: null,
})

function moduloSeverity(modulo) {
  const map = {
    equipos: 'info',
    incidentes: 'warn',
    configuraciones: 'info',
    mantenimientos: 'success',
    respaldos: 'info',
    usuarios: 'danger',
  }
  return map[modulo] || null
}

function applyFilters() {
  router.get('/auditoria', filters, { preserveState: true, replace: true })
}

function onPage(event) {
  router.get('/auditoria', { page: event.page + 1, ...filters }, { preserveState: true, replace: true })
}
</script>
