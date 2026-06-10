<template>
  <div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold">Copia de Seguridad</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <Card>
        <template #title>Generar Copia</template>
        <template #content>
          <p class="text-gray-600 mb-4">Exporta todos los datos del sistema (equipos, incidentes, configuraciones, mantenimientos, respaldos, usuarios, áreas, puestos, auditoría) junto con los archivos adjuntos en un archivo .zip.</p>
          <div class="flex flex-col gap-2">
            <Button label="Descargar Copia de Seguridad" icon="pi pi-download" severity="success" class="w-full" @click="downloadBackup" :loading="downloading" />
          </div>
        </template>
      </Card>

      <Card>
        <template #title>Restaurar Copia</template>
        <template #content>
          <p class="text-gray-600 mb-4">Selecciona un archivo .zip de copia de seguridad para restaurar todos los datos en el sistema.</p>
          <form @submit.prevent="restoreBackup">
            <div class="flex flex-col gap-3">
              <FileUpload mode="basic" name="archivo" accept=".zip,.rar,.7z,.tar,.gz,.bak" :auto="false" chooseLabel="Seleccionar archivo" class="w-full" @select="onFileSelect" />
              <Button type="submit" label="Restaurar Datos" icon="pi pi-upload" severity="warn" class="w-full" :disabled="!archivo" :loading="restoring" />
            </div>
          </form>
        </template>
      </Card>
    </div>

    <Card v-if="backups.length > 0">
      <template #title>Copias Generadas</template>
      <template #content>
        <DataTable :value="backups">
          <Column field="nombre" header="Nombre"></Column>
          <Column field="tamano" header="Tamaño"></Column>
          <Column field="fecha" header="Fecha de Creación"></Column>
        </DataTable>
      </template>
    </Card>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { useToast } from 'primevue/usetoast'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from 'primevue/card'
import Button from 'primevue/button'
import FileUpload from 'primevue/fileupload'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'

defineOptions({ layout: DashboardLayout })

defineProps({
  backups: Array,
})

const toast = useToast()
const downloading = ref(false)
const restoring = ref(false)
const archivo = ref(null)

function onFileSelect(event) {
  archivo.value = event.files[0]
}

function downloadBackup() {
  downloading.value = true
  window.location.href = '/backup/export'
  setTimeout(() => { downloading.value = false }, 2000)
}

function restoreBackup() {
  if (!archivo.value) return
  restoring.value = true
  const form = new FormData()
  form.append('archivo', archivo.value)
  router.post('/backup/import', form, {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      restoring.value = false
      archivo.value = null
      toast.add({ severity: 'success', summary: 'Restaurado', detail: 'Datos restaurados correctamente', life: 5000 })
    },
    onError: () => {
      restoring.value = false
    },
  })
}
</script>
