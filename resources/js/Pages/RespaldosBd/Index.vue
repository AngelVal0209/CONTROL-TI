<template>
  <div class="flex flex-col gap-4">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold">Respaldos de Base de Datos</h1>
      <Button label="Nuevo Respaldo" icon="pi pi-plus" @click="router.get('/respaldos/bd/create')" />
    </div>

    <div class="flex flex-wrap gap-4 items-center">
      <IconField>
        <InputIcon>
          <i class="pi pi-search" />
        </InputIcon>
        <InputText v-model="filters.global" placeholder="Buscar..." @input="applyFilters" />
      </IconField>
    </div>

    <DataTable :value="respaldos.data" :lazy="true" :totalRecords="respaldos.total" :rows="respaldos.per_page" :first="(respaldos.current_page - 1) * respaldos.per_page" paginator :rowsPerPageOptions="[10, 25, 50]" @page="onPage">
      <Column field="nombre" header="Nombre" sortable></Column>
      <Column field="fecha_respaldo" header="Fecha" sortable></Column>
      <Column field="tamano" header="Tamaño" sortable></Column>
      <Column field="responsable" header="Responsable" sortable></Column>
      <Column header="Archivo">
        <template #body="{ data }">
          <Button v-if="data.archivo" icon="pi pi-download" severity="info" text @click="router.get(`/respaldos/bd/${data.id}/download`)" />
          <span v-else class="text-gray-400 text-sm">—</span>
        </template>
      </Column>
      <Column header="Acciones" style="width: 150px">
        <template #body="{ data }">
          <div class="flex gap-1">
            <Button icon="pi pi-eye" severity="info" text @click="router.get(`/respaldos/bd/${data.id}`)" />
            <Button icon="pi pi-pencil" severity="secondary" text @click="router.get(`/respaldos/bd/${data.id}/edit`)" />
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
import Button from 'primevue/button'
import ConfirmDialog from 'primevue/confirmdialog'

defineOptions({ layout: DashboardLayout })

const props = defineProps({
  respaldos: Object,
})

const confirm = useConfirm()
const toast = useToast()

const filters = reactive({
  global: '',
})

function applyFilters() {
  router.get('/respaldos/bd', filters, { preserveState: true, replace: true })
}

function onPage(event) {
  router.get('/respaldos/bd', { page: event.page + 1, ...filters }, { preserveState: true, replace: true })
}

function confirmDelete(respaldo) {
  confirm.require({
    message: `¿Eliminar este respaldo de BD?`,
    header: 'Confirmar Eliminación',
    icon: 'pi pi-exclamation-triangle',
    rejectLabel: 'Cancelar',
    acceptLabel: 'Eliminar',
    acceptClass: 'p-button-danger',
    accept: () => {
      router.delete(`/respaldos/bd/${respaldo.id}`, {
        onSuccess: () => toast.add({ severity: 'success', summary: 'Eliminado', detail: 'Respaldo eliminado correctamente', life: 3000 }),
        onError: () => toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudo eliminar', life: 3000 }),
      })
    },
  })
}
</script>
