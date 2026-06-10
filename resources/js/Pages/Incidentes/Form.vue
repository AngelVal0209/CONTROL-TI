<template>
  <div class="flex flex-col gap-4 max-w-3xl">
    <div class="flex items-center gap-2">
      <Button icon="pi pi-arrow-left" severity="secondary" text @click="router.get('/incidentes')" />
      <h1 class="text-2xl font-bold">{{ incidente ? 'Editar Incidente' : 'Nuevo Incidente' }}</h1>
    </div>
    <form @submit.prevent="submit">
      <Card>
        <template #content>
          <div class="flex flex-col gap-4">
            <div>
              <label for="equipo_id" class="block text-sm font-medium mb-1">Equipo</label>
              <Dropdown id="equipo_id" v-model="form.equipo_id" :options="equipos" optionLabel="nombre_equipo" optionValue="id" placeholder="Seleccione equipo" filter class="w-full" :class="{ 'p-invalid': form.errors.equipo_id }" :disabled="!!incidente" />
              <small v-if="form.errors.equipo_id" class="p-error">{{ form.errors.equipo_id }}</small>
            </div>
            <div>
              <label for="titulo" class="block text-sm font-medium mb-1">Título</label>
              <InputText id="titulo" v-model="form.titulo" class="w-full" :class="{ 'p-invalid': form.errors.titulo }" />
              <small v-if="form.errors.titulo" class="p-error">{{ form.errors.titulo }}</small>
            </div>
            <div>
              <label for="descripcion" class="block text-sm font-medium mb-1">Descripción</label>
              <Textarea id="descripcion" v-model="form.descripcion" rows="5" class="w-full" :class="{ 'p-invalid': form.errors.descripcion }" />
              <small v-if="form.errors.descripcion" class="p-error">{{ form.errors.descripcion }}</small>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-1">Prioridad</label>
                <SelectButton v-model="form.prioridad" :options="prioridadOptions" class="w-full" :class="{ 'p-invalid': form.errors.prioridad }" />
                <small v-if="form.errors.prioridad" class="p-error">{{ form.errors.prioridad }}</small>
              </div>
              <div>
                <label for="fecha_reporte" class="block text-sm font-medium mb-1">Fecha del Reporte</label>
                <Calendar id="fecha_reporte" v-model="form.fecha_reporte" showIcon dateFormat="dd/mm/yy" class="w-full" :class="{ 'p-invalid': form.errors.fecha_reporte }" />
                <small v-if="form.errors.fecha_reporte" class="p-error">{{ form.errors.fecha_reporte }}</small>
              </div>
            </div>
            <div>
              <label for="evidencias" class="block text-sm font-medium mb-1">Evidencias (notas, rutas, etc.)</label>
              <Textarea id="evidencias" v-model="form.evidencias" rows="3" class="w-full" />
            </div>
            <div v-if="incidente">
              <label for="solucion" class="block text-sm font-medium mb-1">Solución</label>
              <Textarea id="solucion" v-model="form.solucion" rows="3" class="w-full" />
            </div>
          </div>
        </template>
        <template #footer>
          <div class="flex justify-end gap-2">
            <Button label="Cancelar" severity="secondary" @click="router.get('/incidentes')" />
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
import Dropdown from 'primevue/dropdown'
import SelectButton from 'primevue/selectbutton'
import Textarea from 'primevue/textarea'
import Calendar from 'primevue/calendar'
import Button from 'primevue/button'

defineOptions({ layout: DashboardLayout })

const props = defineProps({
  incidente: Object,
  equipos: Array,
})

const prioridadOptions = ['baja', 'media', 'alta']

const form = useForm({
  equipo_id: props.incidente?.equipo_id ?? null,
  titulo: props.incidente?.titulo ?? '',
  descripcion: props.incidente?.descripcion ?? '',
  prioridad: props.incidente?.prioridad ?? 'media',
  fecha_reporte: props.incidente?.fecha_reporte ?? new Date().toISOString().split('T')[0],
  evidencias: props.incidente?.evidencias ?? '',
  solucion: props.incidente?.solucion ?? '',
})

function submit() {
  if (props.incidente) {
    form.post(`/incidentes/${props.incidente.id}`, {
      _method: 'put',
    })
  } else {
    form.post('/incidentes')
  }
}
</script>
