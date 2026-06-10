<template>
  <div class="flex flex-col gap-4">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold">Equipos</h1>
      <div class="flex gap-2">
        <Button label="Exportar Excel" icon="pi pi-file-excel" severity="success" @click="router.get('/equipos/export/excel')" />
        <Button label="Nuevo Equipo" icon="pi pi-plus" @click="router.get('/equipos/create')" />
      </div>
    </div>

    <div class="flex flex-wrap gap-4 items-center">
      <IconField>
        <InputIcon>
          <i class="pi pi-search" />
        </InputIcon>
        <InputText v-model="filters.global" placeholder="Buscar..." @input="applyFilters" />
      </IconField>
      <Dropdown v-model="filters.area_id" :options="areas" optionLabel="nombre" optionValue="id" placeholder="Filtrar por Área" showClear class="w-48" @change="applyFilters" />
      <Dropdown v-model="filters.estado" :options="estadoOptions" placeholder="Filtrar por Estado" showClear class="w-48" @change="applyFilters" />
    </div>

    <DataTable :value="equipos.data" :lazy="true" :totalRecords="equipos.total" :rows="equipos.per_page" :first="(equipos.current_page - 1) * equipos.per_page" paginator :rowsPerPageOptions="[10, 25, 50]" @page="onPage" sortField="id" :sortOrder="-1" class="shadow-sm">
      <Column field="codigo" header="Código" sortable></Column>
      <Column field="serie" header="Serie" sortable></Column>
      <Column field="nombre_equipo" header="Nombre" sortable></Column>
      <Column field="tipo" header="Tipo" sortable></Column>
      <Column field="marca" header="Marca" sortable></Column>
      <Column field="area_model.nombre" header="Área" sortable>
        <template #body="{ data }">
          {{ data.area_model?.nombre || data.area || '—' }}
        </template>
      </Column>
      <Column field="usuario_registra.name" header="Registrado por" sortable>
        <template #body="{ data }">
          {{ data.usuario_registra?.name || '—' }}
        </template>
      </Column>
      <Column field="estado" header="Estado" sortable>
        <template #body="{ data }">
          <Tag :value="data.estado" :severity="estadoSeverity(data.estado)" />
        </template>
      </Column>
      <Column field="condicion" header="Condición" sortable>
        <template #body="{ data }">
          <Tag :value="data.condicion" :severity="condicionSeverity(data.condicion)" />
        </template>
      </Column>
      <Column header="Acciones" style="width: 150px">
        <template #body="{ data }">
          <div class="flex gap-1">
            <Button icon="pi pi-eye" severity="info" text @click="router.get(`/equipos/${data.id}`)" />
            <Button icon="pi pi-pencil" severity="secondary" text @click="router.get(`/equipos/${data.id}/edit`)" />
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
import ConfirmDialog from 'primevue/confirmdialog'

defineOptions({ layout: DashboardLayout })

const props = defineProps({
  equipos: Object,
  areas: Array,
})

const confirm = useConfirm()
const toast = useToast()

const estadoOptions = ['operativo', 'inactivo', 'en mantenimiento', 'de baja']
const condicionOptions = ['bueno', 'regular', 'malo']

const filters = reactive({
  global: '',
  area_id: null,
  estado: null,
})

function estadoSeverity(estado) {
  const map = { operativo: 'success', inactivo: 'danger', 'en mantenimiento': 'warn', 'de baja': 'info' }
  return map[estado] || null
}

function condicionSeverity(condicion) {
  const map = { bueno: 'success', regular: 'warn', malo: 'danger' }
  return map[condicion] || null
}

function applyFilters() {
  router.get('/equipos', filters, { preserveState: true, replace: true })
}

function onPage(event) {
  router.get('/equipos', { page: event.page + 1, ...filters }, { preserveState: true, replace: true })
}

function confirmDelete(equipo) {
  confirm.require({
    message: `¿Eliminar el equipo "${equipo.nombre_equipo}"?`,
    header: 'Confirmar Eliminación',
    icon: 'pi pi-exclamation-triangle',
    rejectLabel: 'Cancelar',
    acceptLabel: 'Eliminar',
    acceptClass: 'p-button-danger',
    accept: () => {
      router.delete(`/equipos/${equipo.id}`, {
        onSuccess: () => toast.add({ severity: 'success', summary: 'Eliminado', detail: 'Equipo eliminado correctamente', life: 3000 }),
        onError: () => toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudo eliminar el equipo', life: 3000 }),
      })
    },
  })
}
</script>
