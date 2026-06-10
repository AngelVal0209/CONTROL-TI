<template>
  <div class="flex flex-col gap-4 max-w-3xl">
    <div class="flex items-center gap-2">
      <Button icon="pi pi-arrow-left" severity="secondary" text @click="router.get('/mantenimientos')" />
      <h1 class="text-2xl font-bold">{{ mantenimiento ? 'Editar Mantenimiento' : 'Nuevo Mantenimiento' }}</h1>
    </div>
    <form @submit.prevent="submit">
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
                <label for="fecha_programada" class="block text-sm font-medium mb-1">Fecha Programada</label>
                <Calendar id="fecha_programada" v-model="form.fecha_programada" showIcon dateFormat="dd/mm/yy" class="w-full" :class="{ 'p-invalid': form.errors.fecha_programada }" />
                <small v-if="form.errors.fecha_programada" class="p-error">{{ form.errors.fecha_programada }}</small>
              </div>
              <div>
                <label for="tecnico" class="block text-sm font-medium mb-1">Técnico</label>
                <Dropdown id="tecnico" v-model="form.tecnico" :options="tecnicoOptions" placeholder="Seleccione técnico" class="w-full" editable />
                <small v-if="form.errors.tecnico" class="p-error">{{ form.errors.tecnico }}</small>
              </div>
              <div>
                <label for="fecha_realizada" class="block text-sm font-medium mb-1">Fecha Realizada</label>
                <Calendar id="fecha_realizada" v-model="form.fecha_realizada" showIcon dateFormat="dd/mm/yy" class="w-full" />
              </div>
              <div>
                <label for="costo" class="block text-sm font-medium mb-1">Costo ($)</label>
                <InputNumber id="costo" v-model="form.costo" mode="currency" currency="PEN" locale="es-PE" class="w-full" />
              </div>
              <div>
                <label for="estado" class="block text-sm font-medium mb-1">Estado</label>
                <Dropdown id="estado" v-model="form.estado" :options="estadoOptions" placeholder="Seleccione estado" class="w-full" filter editable />
              </div>
            </div>
            <div>
              <label for="descripcion" class="block text-sm font-medium mb-1">Descripción</label>
              <Textarea id="descripcion" v-model="form.descripcion" rows="4" class="w-full" :class="{ 'p-invalid': form.errors.descripcion }" />
              <small v-if="form.errors.descripcion" class="p-error">{{ form.errors.descripcion }}</small>
            </div>
          </div>
        </template>
        <template #footer>
          <div class="flex justify-end gap-2">
            <Button label="Cancelar" severity="secondary" @click="router.get('/mantenimientos')" />
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
import Dropdown from 'primevue/dropdown'
import Textarea from 'primevue/textarea'
import SelectButton from 'primevue/selectbutton'
import Calendar from 'primevue/calendar'
import InputNumber from 'primevue/inputnumber'
import Button from 'primevue/button'

defineOptions({ layout: DashboardLayout })

const props = defineProps({
  mantenimiento: Object,
  equipos: Array,
})

const tipoOptions = ['preventivo', 'correctivo']
const estadoOptions = ['programado', 'pendiente', 'realizado', 'cancelado']
const tecnicoOptions = ['Soporte Interno', 'Soporte Externo', 'Proveedor', 'Técnico A', 'Técnico B']

const form = useForm({
  equipo_id: props.mantenimiento?.equipo_id ?? null,
  tipo: props.mantenimiento?.tipo ?? 'preventivo',
  fecha_programada: props.mantenimiento?.fecha_programada ?? null,
  fecha_realizada: props.mantenimiento?.fecha_realizada ?? null,
  tecnico: props.mantenimiento?.tecnico ?? null,
  descripcion: props.mantenimiento?.descripcion ?? '',
  costo: props.mantenimiento?.costo ?? null,
  estado: props.mantenimiento?.estado ?? 'pendiente',
})

function submit() {
  if (props.mantenimiento) {
    form.put(`/mantenimientos/${props.mantenimiento.id}`)
  } else {
    form.post('/mantenimientos')
  }
}
</script>
