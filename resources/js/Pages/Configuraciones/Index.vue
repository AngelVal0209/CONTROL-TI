<template>
  <div class="flex flex-col gap-4">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-2">
        <Button icon="pi pi-arrow-left" severity="secondary" text @click="router.get('/equipos')" />
        <h1 class="text-2xl font-bold">Configuraciones</h1>
        <span class="text-gray-500">- {{ equipo?.nombre_equipo }}</span>
      </div>
      <Button label="Nueva Configuración" icon="pi pi-plus" @click="router.get(`/equipos/${equipo.id}/configuraciones/create`)" />
    </div>

    <DataTable :value="configuraciones" paginator :rows="10" :rowsPerPageOptions="[10, 25, 50]">
      <Column field="sistema_operativo" header="SO" sortable></Column>
      <Column field="version" header="Versión" sortable></Column>
      <Column field="office" header="Office" sortable></Column>
      <Column field="antivirus" header="Antivirus" sortable></Column>
      <Column field="cpu" header="CPU" sortable></Column>
      <Column field="ram" header="RAM" sortable></Column>
      <Column field="disco" header="Disco" sortable></Column>
      <Column field="fecha_actualizacion" header="Fecha Actualización" sortable></Column>
      <Column header="Acciones" style="width: 150px">
        <template #body="{ data }">
          <div class="flex gap-1">
            <Button icon="pi pi-pencil" severity="secondary" text @click="router.get(`/equipos/${equipo.id}/configuraciones/${data.id}/edit`)" />
            <Button icon="pi pi-trash" severity="danger" text @click="confirmDelete(data)" />
          </div>
        </template>
      </Column>
    </DataTable>

    <ConfirmDialog />
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { useConfirm } from 'primevue/useconfirm'
import { useToast } from 'primevue/usetoast'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Button from 'primevue/button'
import ConfirmDialog from 'primevue/confirmdialog'

defineOptions({ layout: DashboardLayout })

const props = defineProps({
  configuraciones: Array,
  equipo: Object,
})

const confirm = useConfirm()
const toast = useToast()

function confirmDelete(configuracion) {
  confirm.require({
    message: `¿Eliminar esta configuración?`,
    header: 'Confirmar Eliminación',
    icon: 'pi pi-exclamation-triangle',
    rejectLabel: 'Cancelar',
    acceptLabel: 'Eliminar',
    acceptClass: 'p-button-danger',
    accept: () => {
      router.delete(`/equipos/${props.equipo.id}/configuraciones/${configuracion.id}`, {
        onSuccess: () => toast.add({ severity: 'success', summary: 'Eliminado', detail: 'Configuración eliminada correctamente', life: 3000 }),
        onError: () => toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudo eliminar la configuración', life: 3000 }),
      })
    },
  })
}
</script>

