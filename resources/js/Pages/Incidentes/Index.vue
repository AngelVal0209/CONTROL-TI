<template>
  <div class="flex flex-col gap-4">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold">Incidentes</h1>
      <Button label="Nuevo Incidente" icon="pi pi-plus" @click="router.get('/incidentes/create')" />
    </div>

    <div class="flex flex-wrap gap-4 items-center">
      <IconField>
        <InputIcon>
          <i class="pi pi-search" />
        </InputIcon>
        <InputText v-model="filters.global" placeholder="Buscar..." @input="applyFilters" />
      </IconField>
      <Dropdown v-model="filters.equipo_id" :options="equipos" optionLabel="nombre_equipo" optionValue="id" placeholder="Filtrar por Equipo" showClear class="w-48" @change="applyFilters" />
      <Dropdown v-model="filters.estado" :options="estadoOptions" placeholder="Filtrar por Estado" showClear class="w-48" @change="applyFilters" />
      <Dropdown v-model="filters.prioridad" :options="prioridadOptions" placeholder="Filtrar por Prioridad" showClear class="w-48" @change="applyFilters" />
      <Calendar v-model="filters.fecha_desde" placeholder="Fecha desde" showIcon dateFormat="dd/mm/yy" class="w-40" @date-select="applyFilters" @clear="applyFilters" />
      <Calendar v-model="filters.fecha_hasta" placeholder="Fecha hasta" showIcon dateFormat="dd/mm/yy" class="w-40" @date-select="applyFilters" @clear="applyFilters" />
    </div>

    <DataTable :value="incidentes.data" :lazy="true" :totalRecords="incidentes.total" :rows="incidentes.per_page" :first="(incidentes.current_page - 1) * incidentes.per_page" paginator :rowsPerPageOptions="[10, 25, 50]" @page="onPage">
      <Column field="titulo" header="Título" sortable></Column>
      <Column field="equipo.nombre_equipo" header="Equipo" sortable></Column>
      <Column field="estado" header="Estado" sortable>
        <template #body="{ data }">
          <Tag :value="data.estado" :severity="data.estado === 'resuelto' ? 'success' : data.estado === 'en_proceso' ? 'warn' : 'danger'" />
        </template>
      </Column>
      <Column field="prioridad" header="Prioridad" sortable>
        <template #body="{ data }">
          <Tag :value="data.prioridad" :severity="data.prioridad === 'Alta' ? 'danger' : data.prioridad === 'Media' ? 'warn' : 'info'" />
        </template>
      </Column>
      <Column field="created_at" header="Fecha" sortable>
        <template #body="{ data }">
          {{ data.created_at }}
        </template>
      </Column>
      <Column header="Acciones" style="width: 180px">
        <template #body="{ data }">
          <div class="flex gap-1">
            <Button icon="pi pi-eye" severity="info" text @click="router.get(`/incidentes/${data.id}`)" />
            <Button icon="pi pi-pencil" severity="secondary" text @click="router.get(`/incidentes/${data.id}/edit`)" />
            <Button v-if="data.estado !== 'resuelto'" icon="pi pi-check" severity="success" text @click="resolver(data)" />
            <Button icon="pi pi-trash" severity="danger" text @click="confirmDelete(data)" />
          </div>
        </template>
      </Column>
    </DataTable>

    <ConfirmDialog />
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
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
  incidentes: Object,
  equipos: Array,
})

const confirm = useConfirm()
const toast = useToast()

const estadoOptions = ['pendiente', 'en_proceso', 'resuelto']
const prioridadOptions = ['Baja', 'Media', 'Alta']

const filters = reactive({
  global: '',
  equipo_id: null,
  estado: null,
  prioridad: null,
  fecha_desde: null,
  fecha_hasta: null,
})

function applyFilters() {
  router.get('/incidentes', filters, { preserveState: true, replace: true })
}

function onPage(event) {
  router.get('/incidentes', { page: event.page + 1, ...filters }, { preserveState: true, replace: true })
}

function resolver(incidente) {
  confirm.require({
    message: `¿Resolver el incidente "${incidente.titulo}"?`,
    header: 'Resolver Incidente',
    icon: 'pi pi-check-circle',
    acceptLabel: 'Resolver',
    accept: () => {
      router.patch(`/incidentes/${incidente.id}/resolver`, {}, {
        onSuccess: () => toast.add({ severity: 'success', summary: 'Resuelto', detail: 'Incidente resuelto correctamente', life: 3000 }),
        onError: () => toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudo resolver el incidente', life: 3000 }),
      })
    },
  })
}

function confirmDelete(incidente) {
  confirm.require({
    message: `¿Eliminar el incidente "${incidente.titulo}"?`,
    header: 'Confirmar Eliminación',
    icon: 'pi pi-exclamation-triangle',
    rejectLabel: 'Cancelar',
    acceptLabel: 'Eliminar',
    acceptClass: 'p-button-danger',
    accept: () => {
      router.delete(`/incidentes/${incidente.id}`, {
        onSuccess: () => toast.add({ severity: 'success', summary: 'Eliminado', detail: 'Incidente eliminado correctamente', life: 3000 }),
        onError: () => toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudo eliminar el incidente', life: 3000 }),
      })
    },
  })
}
</script>

