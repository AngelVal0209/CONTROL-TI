<template>
  <div class="flex flex-col gap-4">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold">Mantenimientos</h1>
      <Button label="Nuevo Mantenimiento" icon="pi pi-plus" @click="router.get('/mantenimientos/create')" />
    </div>

    <div class="flex flex-wrap gap-4 items-center">
      <IconField>
        <InputIcon>
          <i class="pi pi-search" />
        </InputIcon>
        <InputText v-model="filters.global" placeholder="Buscar..." @input="applyFilters" />
      </IconField>
      <Dropdown v-model="filters.tipo" :options="tipoOptions" placeholder="Filtrar por Tipo" showClear class="w-48" @change="applyFilters" />
      <Dropdown v-model="filters.estado" :options="estadoOptions" placeholder="Filtrar por Estado" showClear class="w-48" @change="applyFilters" />
      <Calendar v-model="filters.fecha_desde" placeholder="Fecha desde" showIcon dateFormat="dd/mm/yy" class="w-40" @date-select="applyFilters" @clear="applyFilters" />
      <Calendar v-model="filters.fecha_hasta" placeholder="Fecha hasta" showIcon dateFormat="dd/mm/yy" class="w-40" @date-select="applyFilters" @clear="applyFilters" />
    </div>

    <DataTable :value="mantenimientos.data" :lazy="true" :totalRecords="mantenimientos.total" :rows="mantenimientos.per_page" :first="(mantenimientos.current_page - 1) * mantenimientos.per_page" paginator :rowsPerPageOptions="[10, 25, 50]" @page="onPage">
      <Column field="equipo.nombre_equipo" header="Equipo" sortable></Column>
      <Column field="tipo" header="Tipo" sortable>
        <template #body="{ data }">
          <Tag :value="data.tipo" :severity="data.tipo === 'preventivo' ? 'info' : 'warn'" />
        </template>
      </Column>
      <Column field="fecha_programada" header="Fecha Programada" sortable></Column>
      <Column field="fecha_realizada" header="Fecha Realizada" sortable></Column>
      <Column field="tecnico" header="Técnico" sortable></Column>
      <Column field="estado" header="Estado" sortable>
        <template #body="{ data }">
          <Tag :value="data.estado" :severity="data.estado === 'realizado' ? 'success' : data.estado === 'pendiente' ? 'warn' : 'info'" />
        </template>
      </Column>
      <Column field="costo" header="Costo" sortable>
        <template #body="{ data }">
          {{ data.costo ? `$${Number(data.costo).toFixed(2)}` : '-' }}
        </template>
      </Column>
      <Column header="Acciones" style="width: 150px">
        <template #body="{ data }">
          <div class="flex gap-1">
            <Button icon="pi pi-eye" severity="info" text @click="router.get(`/mantenimientos/${data.id}`)" />
            <Button icon="pi pi-pencil" severity="secondary" text @click="router.get(`/mantenimientos/${data.id}/edit`)" />
            <Button icon="pi pi-trash" severity="danger" text @click="confirmDelete(data)" />
          </div>
        </template>
      </Column>
    </DataTable>

    <ConfirmDialog />
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import { useConfirm } from 'primevue/useconfirm'
import { useToast } from 'primevue/usetoast'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext'
import InputIcon from 'primevue/inputicon'
import IconField from 'primevue/iconfield'
import Dropdown from 'primevue/dropdown'
import Button from 'primevue/button'
import Tag from 'primevue/tag'
import Calendar from 'primevue/calendar'
import ConfirmDialog from 'primevue/confirmdialog'

defineOptions({ layout: DashboardLayout })

const props = defineProps({
  mantenimientos: Object,
})

const confirm = useConfirm()
const toast = useToast()

const tipoOptions = ['preventivo', 'correctivo']
const estadoOptions = ['programado', 'pendiente', 'realizado', 'cancelado']

const filters = reactive({
  global: '',
  tipo: null,
  estado: null,
  fecha_desde: null,
  fecha_hasta: null,
})

function applyFilters() {
  router.get('/mantenimientos', filters, { preserveState: true, replace: true })
}

function onPage(event) {
  router.get('/mantenimientos', { page: event.page + 1, ...filters }, { preserveState: true, replace: true })
}

function confirmDelete(mantenimiento) {
  confirm.require({
    message: `¿Eliminar este mantenimiento?`,
    header: 'Confirmar Eliminación',
    icon: 'pi pi-exclamation-triangle',
    rejectLabel: 'Cancelar',
    acceptLabel: 'Eliminar',
    acceptClass: 'p-button-danger',
    accept: () => {
      router.delete(`/mantenimientos/${mantenimiento.id}`, {
        onSuccess: () => toast.add({ severity: 'success', summary: 'Eliminado', detail: 'Mantenimiento eliminado correctamente', life: 3000 }),
        onError: () => toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudo eliminar el mantenimiento', life: 3000 }),
      })
    },
  })
}
</script>

