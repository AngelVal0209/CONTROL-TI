<template>
  <div class="flex flex-col gap-4 max-w-3xl">
    <div class="flex items-center gap-2">
      <Button icon="pi pi-arrow-left" severity="secondary" text @click="router.get('/respaldos/bd')" />
      <h1 class="text-2xl font-bold">{{ respaldo ? 'Editar Respaldo de BD' : 'Nuevo Respaldo de BD' }}</h1>
    </div>
    <form @submit.prevent="submit" enctype="multipart/form-data">
      <Card>
        <template #content>
          <div class="flex flex-col gap-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label for="nombre" class="block text-sm font-medium mb-1">Nombre</label>
                <InputText id="nombre" v-model="form.nombre" class="w-full" :class="{ 'p-invalid': form.errors.nombre }" />
                <small v-if="form.errors.nombre" class="p-error">{{ form.errors.nombre }}</small>
              </div>
              <div>
                <label for="fecha_respaldo" class="block text-sm font-medium mb-1">Fecha del Respaldo</label>
                <Calendar id="fecha_respaldo" v-model="form.fecha_respaldo" showIcon dateFormat="dd/mm/yy" class="w-full" :class="{ 'p-invalid': form.errors.fecha_respaldo }" />
                <small v-if="form.errors.fecha_respaldo" class="p-error">{{ form.errors.fecha_respaldo }}</small>
              </div>
              <div>
                <label for="responsable" class="block text-sm font-medium mb-1">Responsable</label>
                <InputText id="responsable" v-model="form.responsable" class="w-full" />
              </div>
              <div>
                <label for="archivo" class="block text-sm font-medium mb-1">Archivo</label>
                <FileUpload mode="basic" name="archivo" accept="*" :auto="false" :chooseLabel="form.archivo_name || 'Seleccionar archivo'" class="w-full" @select="onFileSelect" />
                <small v-if="respaldo?.archivo" class="text-gray-500 block mt-1">Archivo actual: {{ respaldo.archivo.split('/').pop() }}</small>
              </div>
            </div>
            <div>
              <label for="descripcion" class="block text-sm font-medium mb-1">Descripción</label>
              <Textarea id="descripcion" v-model="form.descripcion" rows="2" class="w-full" />
            </div>
            <div>
              <label for="observaciones" class="block text-sm font-medium mb-1">Observaciones</label>
              <Textarea id="observaciones" v-model="form.observaciones" rows="3" class="w-full" />
            </div>
          </div>
        </template>
        <template #footer>
          <div class="flex justify-end gap-2">
            <Button label="Cancelar" severity="secondary" @click="router.get('/respaldos/bd')" />
            <Button type="submit" label="Guardar" icon="pi pi-check" :loading="form.processing" />
          </div>
        </template>
      </Card>
    </form>
  </div>
</template>

<script setup>
import { useForm, router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from 'primevue/card'
import InputText from 'primevue/inputtext'
import Textarea from 'primevue/textarea'
import Calendar from 'primevue/calendar'
import FileUpload from 'primevue/fileupload'
import Button from 'primevue/button'

defineOptions({ layout: DashboardLayout })

const props = defineProps({
  respaldo: Object,
})

const form = useForm({
  nombre: props.respaldo?.nombre ?? '',
  descripcion: props.respaldo?.descripcion ?? '',
  fecha_respaldo: props.respaldo?.fecha_respaldo ?? null,
  responsable: props.respaldo?.responsable ?? '',
  observaciones: props.respaldo?.observaciones ?? '',
  archivo: null,
  archivo_name: props.respaldo?.archivo ? props.respaldo.archivo.split('/').pop() : null,
})

function onFileSelect(event) {
  form.archivo = event.files[0]
  form.archivo_name = event.files[0].name
}

function submit() {
  if (props.respaldo) {
    form.post(`/respaldos/bd/${props.respaldo.id}`, {
      _method: 'PUT',
      forceFormData: true,
    })
  } else {
    form.post('/respaldos/bd', {
      forceFormData: true,
    })
  }
}
</script>
