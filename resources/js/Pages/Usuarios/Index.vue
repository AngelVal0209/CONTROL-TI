<template>
  <div class="flex flex-col gap-4">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold">Usuarios</h1>
      <Button label="Nuevo Usuario" icon="pi pi-plus" @click="openCreate" />
    </div>

    <DataTable :value="usuarios.data" :lazy="true" :totalRecords="usuarios.total" :rows="usuarios.per_page" :first="(usuarios.current_page - 1) * usuarios.per_page" paginator :rowsPerPageOptions="[10, 25, 50]" @page="onPage" class="shadow-sm">
      <Column field="name" header="Nombre" sortable></Column>
      <Column field="documento" header="Documento" sortable></Column>
      <Column field="email" header="Email" sortable></Column>
      <Column header="Roles">
        <template #body="{ data }">
          <div class="flex gap-1 flex-wrap">
            <Tag v-for="rol in data.roles" :key="rol.id || rol" :value="rol.name || rol" :severity="(rol.name || rol) === 'Administrador' ? 'danger' : 'info'" />
          </div>
        </template>
      </Column>
      <Column header="Acciones" style="width: 120px">
        <template #body="{ data }">
          <div class="flex gap-1">
            <Button icon="pi pi-pencil" severity="secondary" text @click="router.get(`/usuarios/${data.id}/edit`)" />
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
import Button from 'primevue/button'
import Tag from 'primevue/tag'
import ConfirmDialog from 'primevue/confirmdialog'

defineOptions({ layout: DashboardLayout })

const props = defineProps({
  usuarios: Object,
})

const confirm = useConfirm()
const toast = useToast()

function onPage(event) {
  router.get('/usuarios', { page: event.page + 1 }, { preserveState: true, replace: true })
}

function confirmDelete(user) {
  confirm.require({
    message: `¿Eliminar al usuario "${user.name}"?`,
    header: 'Confirmar Eliminación',
    icon: 'pi pi-exclamation-triangle',
    rejectLabel: 'Cancelar',
    acceptLabel: 'Eliminar',
    acceptClass: 'p-button-danger',
    accept: () => {
      router.delete(`/usuarios/${user.id}`, {
        onSuccess: () => toast.add({ severity: 'success', summary: 'Eliminado', detail: 'Usuario eliminado correctamente', life: 3000 }),
        onError: () => toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudo eliminar el usuario', life: 3000 }),
      })
    },
  })
}
</script>
