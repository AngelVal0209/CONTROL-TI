<template>
  <div class="flex flex-col gap-4">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold">Respaldos</h1>
      <Button label="Nuevo Respaldo" icon="pi pi-plus" @click="router.get('/respaldos/create')" />
    </div>

    <div class="flex flex-wrap gap-4 items-center">
      <IconField>
        <InputIcon>
          <i class="pi pi-search" />
        </InputIcon>
        <InputText v-model="filters.global" placeholder="Buscar..." @input="applyFilters" />
      </IconField>
      <Dropdown v-model="filters.equipo_id" :options="equipos" optionLabel="nombre_equipo" optionValue="id" placeholder="Filtrar por Equipo" showClear class="w-48" @change="applyFilters" />
      <Dropdown v-model="filters.tipo" :options="tipoOptions" placeholder="Filtrar por Tipo" showClear class="w-48" @change="applyFilters" />
      <Dropdown v-model="filters.ubicacion" :options="ubicacionOptions" placeholder="Filtrar por Ubicación" showClear class="w-48" @change="applyFilters" />
    </div>

    <DataTable :value="respaldos.data" :lazy="true" :totalRecords="respaldos.total" :rows="respaldos.per_page" :first="(respaldos.current_page - 1) * respaldos.per_page" paginator :rowsPerPageOptions="[10, 25, 50]" @page="onPage">
      <Column field="equipo.nombre_equipo" header="Equipo" sortable></Column>
      <Column field="tipo" header="Tipo" sortable>
        <template #body="{ data }">
          <Tag :value="data.tipo" :severity="data.tipo === 'completo' ? 'success' : data.tipo === 'incremental' ? 'info' : 'warn'" />
        </template>
      </Column>
      <Column field="ubicacion" header="Ubicación" sortable></Column>
      <Column field="fecha_respaldo" header="Fecha" sortable></Column>
      <Column field="tamano" header="Tamaño" sortable></Column>
      <Column field="responsable" header="Responsable" sortable></Column>
      <Column header="Acciones" style="width: 200px">
        <template #body="{ data }">
          <div class="flex gap-1">
            <Button icon="pi pi-eye" severity="info" text @click="router.get(`/respaldos/${data.id}`)" />
            <Button icon="pi pi-pencil" severity="secondary" text @click="router.get(`/respaldos/${data.id}/edit`)" />
            <Button v-if="data.archivo" icon="pi pi-download" severity="info" text @click="download(data)" />
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
import ConfirmDialog from 'primevue/confirmdialog'

defineOptions({ layout: DashboardLayout })

const props = defineProps({
  respaldos: Object,
  equipos: Array,
})

const confirm = useConfirm()
const toast = useToast()

const tipoOptions = ['completo', 'incremental', 'diferencial']
const ubicacionOptions = ['local', 'red', 'nube', 'externo']

const filters = reactive({
  global: '',
  equipo_id: null,
  tipo: null,
  ubicacion: null,
})

function applyFilters() {
  router.get('/respaldos', filters, { preserveState: true, replace: true })
}

function onPage(event) {
  router.get('/respaldos', { page: event.page + 1, ...filters }, { preserveState: true, replace: true })
}

function download(respaldo) {
  window.open(`/respaldos/${respaldo.id}/download`, '_blank')
}

function confirmDelete(respaldo) {
  confirm.require({
    message: `¿Eliminar este respaldo?`,
    header: 'Confirmar Eliminación',
    icon: 'pi pi-exclamation-triangle',
    rejectLabel: 'Cancelar',
    acceptLabel: 'Eliminar',
    acceptClass: 'p-button-danger',
    accept: () => {
      router.delete(`/respaldos/${respaldo.id}`, {
        onSuccess: () => toast.add({ severity: 'success', summary: 'Eliminado', detail: 'Respaldo eliminado correctamente', life: 3000 }),
        onError: () => toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudo eliminar el respaldo', life: 3000 }),
      })
    },
  })
}
</script>

