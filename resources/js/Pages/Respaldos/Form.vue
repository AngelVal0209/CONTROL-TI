<template>
  <div class="flex flex-col gap-4 max-w-3xl">
    <div class="flex items-center gap-2">
      <Button icon="pi pi-arrow-left" severity="secondary" text @click="router.get('/respaldos')" />
      <h1 class="text-2xl font-bold">{{ respaldo ? 'Editar Respaldo' : 'Nuevo Respaldo' }}</h1>
    </div>
    <form @submit.prevent="submit" enctype="multipart/form-data">
      <Card>
        <template #content>
          <div class="flex flex-col gap-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label for="equipo_id" class="block text-sm font-medium mb-1">Equipo</label>
                <Dropdown id="equipo_id" v-model="form.equipo_id" :options="equipos" optionLabel="nombre_equipo" optionValue="id" placeholder="Seleccione equipo" filter class="w-full" :class="{ 'p-invalid': form.errors.equipo_id }" />
                <small v-if="form.errors.equipo_id" class="p-error">{{ form.errors.equipo_id }}</small>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Tipo</label>
                <SelectButton v-model="form.tipo" :options="tipoOptions" class="w-full" />
              </div>
              <div>
                <label for="ubicacion" class="block text-sm font-medium mb-1">Ubicación</label>
                <Dropdown id="ubicacion" v-model="form.ubicacion" :options="ubicacionOptions" placeholder="Seleccione ubicación" class="w-full" />
              </div>
              <div>
                <label for="archivo" class="block text-sm font-medium mb-1">Archivo</label>
                <FileUpload mode="basic" name="archivo" accept=".rar,.zip" :auto="false" :chooseLabel="archivoName || 'Seleccionar archivo'" class="w-full" @select="onFileSelect" />
                <small v-if="respaldo?.archivo" class="text-gray-500 block mt-1">Archivo actual: {{ respaldo.archivo.split('/').pop() }}</small>
              </div>
              <div>
                <label for="fecha_respaldo" class="block text-sm font-medium mb-1">Fecha del Respaldo</label>
                <Calendar id="fecha_respaldo" v-model="form.fecha_respaldo" showIcon dateFormat="dd/mm/yy" class="w-full" :class="{ 'p-invalid': form.errors.fecha_respaldo }" />
                <small v-if="form.errors.fecha_respaldo" class="p-error">{{ form.errors.fecha_respaldo }}</small>
              </div>
              <div>
                <label for="tamano" class="block text-sm font-medium mb-1">Tamaño</label>
                <Dropdown id="tamano" v-model="form.tamano" :options="tamanoOptions" placeholder="Seleccione tamaño" class="w-full" editable />
              </div>
              <div>
                <label for="responsable" class="block text-sm font-medium mb-1">Responsable</label>
                <Dropdown id="responsable" v-model="form.responsable" :options="responsableOptions" placeholder="Seleccione responsable" class="w-full" editable />
                <small v-if="form.errors.responsable" class="p-error">{{ form.errors.responsable }}</small>
              </div>
            </div>
            <div>
              <label for="observaciones" class="block text-sm font-medium mb-1">Observaciones</label>
              <Textarea id="observaciones" v-model="form.observaciones" rows="3" class="w-full" />
            </div>
          </div>
        </template>
        <template #footer>
          <div class="flex justify-end gap-2">
            <Button label="Cancelar" severity="secondary" @click="router.get('/respaldos')" />
            <Button type="submit" label="Guardar" icon="pi pi-check" :loading="form.processing" />
          </div>
        </template>
      </Card>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from 'primevue/card'
import Dropdown from 'primevue/dropdown'
import Textarea from 'primevue/textarea'
import SelectButton from 'primevue/selectbutton'
import Calendar from 'primevue/calendar'
import FileUpload from 'primevue/fileupload'
import Button from 'primevue/button'

defineOptions({ layout: DashboardLayout })

const props = defineProps({
  respaldo: Object,
  equipos: Array,
})

const tipoOptions = ['completo', 'incremental', 'diferencial']
const ubicacionOptions = ['local', 'red', 'nube', 'externo']
const tamanoOptions = ['100 MB', '500 MB', '1 GB', '2 GB', '5 GB', '10 GB', '25 GB', '50 GB', '100 GB', '500 GB', '1 TB']
const responsableOptions = ['Soporte TI', 'Administrador', 'Técnico A', 'Técnico B', 'Proveedor']

const archivoName = ref(props.respaldo?.archivo ? props.respaldo.archivo.split('/').pop() : null)

const form = useForm({
  equipo_id: props.respaldo?.equipo_id ?? null,
  tipo: props.respaldo?.tipo ?? 'completo',
  ubicacion: props.respaldo?.ubicacion ?? null,
  fecha_respaldo: props.respaldo?.fecha_respaldo ?? null,
  tamano: props.respaldo?.tamano ?? null,
  responsable: props.respaldo?.responsable ?? null,
  observaciones: props.respaldo?.observaciones ?? '',
  archivo: null,
})

function onFileSelect(event) {
  form.archivo = event.files[0]
  archivoName.value = event.files[0].name
}

function submit() {
  if (props.respaldo) {
    form.post(`/respaldos/${props.respaldo.id}`, {
      _method: 'PUT',
      forceFormData: true,
    })
  } else {
    form.post('/respaldos', {
      forceFormData: true,
    })
  }
}
</script>
