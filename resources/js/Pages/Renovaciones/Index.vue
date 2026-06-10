<template>
  <div class="flex flex-col gap-4">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold">Renovaciones</h1>
      <Button label="Nueva Renovación" icon="pi pi-plus" @click="router.get('/renovaciones/create')" />
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
    </div>

    <DataTable :value="renovaciones.data" :lazy="true" :totalRecords="renovaciones.total" :rows="renovaciones.per_page" :first="(renovaciones.current_page - 1) * renovaciones.per_page" paginator :rowsPerPageOptions="[10, 25, 50]" @page="onPage" class="shadow-sm">
      <Column field="nombre" header="Nombre" sortable></Column>
      <Column field="proveedor" header="Proveedor" sortable></Column>
      <Column field="tipo" header="Tipo" sortable>
        <template #body="{ data }">
          <Tag :value="data.tipo" severity="info" />
        </template>
      </Column>
      <Column field="fecha_vencimiento" header="Vencimiento" sortable>
        <template #body="{ data }">
          <span :class="{ 'text-red-600 font-bold': diasRestantes(data.fecha_vencimiento) <= 30 && data.estado === 'activo' }">
            {{ data.fecha_vencimiento }}
          </span>
          <Tag v-if="diasRestantes(data.fecha_vencimiento) <= 30 && data.estado === 'activo'" :value="`${diasRestantes(data.fecha_vencimiento)} días`" severity="warn" class="ml-2" />
        </template>
      </Column>
      <Column field="monto" header="Monto" sortable>
        <template #body="{ data }">
          {{ data.monto ? `${data.moneda || 'PEN'} ${Number(data.monto).toFixed(2)}` : '—' }}
        </template>
      </Column>
      <Column field="estado" header="Estado" sortable>
        <template #body="{ data }">
          <Tag :value="data.estado" :severity="estadoSeverity(data.estado)" />
        </template>
      </Column>
      <Column header="Acciones" style="width: 200px">
        <template #body="{ data }">
          <div class="flex gap-1">
            <Button icon="pi pi-eye" severity="info" text @click="router.get(`/renovaciones/${data.id}`)" />
            <Button icon="pi pi-pencil" severity="secondary" text @click="router.get(`/renovaciones/${data.id}/edit`)" />
            <Button v-if="data.archivo" icon="pi pi-download" severity="info" text @click="descargar(data)" />
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
  renovaciones: Object,
})

const confirm = useConfirm()
const toast = useToast()

const tipoOptions = ['licencia', 'suscripcion', 'servicio', 'pago_proveedor', 'mantenimiento', 'dominio', 'hosting', 'seguro', 'otro']
const estadoOptions = ['activo', 'vencido', 'renovado', 'cancelado']

const filters = reactive({
  global: '',
  tipo: null,
  estado: null,
})

function estadoSeverity(estado) {
  const map = { activo: 'success', vencido: 'danger', renovado: 'info', cancelado: 'warn' }
  return map[estado] || null
}

function diasRestantes(fecha) {
  if (!fecha) return 999
  const hoy = new Date()
  const ven = new Date(fecha)
  return Math.ceil((ven - hoy) / (1000 * 60 * 60 * 24))
}

function applyFilters() {
  router.get('/renovaciones', filters, { preserveState: true, replace: true })
}

function onPage(event) {
  router.get('/renovaciones', { page: event.page + 1, ...filters }, { preserveState: true, replace: true })
}

function descargar(data) {
  window.open(`/renovaciones/${data.id}/download`, '_blank')
}

function confirmDelete(renovacion) {
  confirm.require({
    message: `¿Eliminar "${renovacion.nombre}"?`,
    header: 'Confirmar Eliminación',
    icon: 'pi pi-exclamation-triangle',
    rejectLabel: 'Cancelar',
    acceptLabel: 'Eliminar',
    acceptClass: 'p-button-danger',
    accept: () => {
      router.delete(`/renovaciones/${renovacion.id}`, {
        onSuccess: () => toast.add({ severity: 'success', summary: 'Eliminado', detail: 'Renovación eliminada', life: 3000 }),
        onError: () => toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudo eliminar', life: 3000 }),
      })
    },
  })
}
</script>
