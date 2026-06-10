<template>
  <div class="flex flex-col gap-4 max-w-4xl">
    <div class="flex items-center gap-2">
      <Button icon="pi pi-arrow-left" severity="secondary" text @click="router.get('/equipos')" />
      <h1 class="text-2xl font-bold">{{ equipo ? 'Editar Equipo' : 'Nuevo Equipo' }}</h1>
    </div>
    <form @submit.prevent="submit">
      <Card>
        <template #content>
          <div class="flex flex-col gap-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label for="codigo" class="block text-sm font-medium mb-1">Código *</label>
                <InputText id="codigo" v-model="form.codigo" class="w-full" :class="{ 'p-invalid': form.errors.codigo }" />
                <small v-if="form.errors.codigo" class="p-error">{{ form.errors.codigo }}</small>
              </div>
              <div>
                <label for="serie" class="block text-sm font-medium mb-1">Serie *</label>
                <InputText id="serie" v-model="form.serie" class="w-full" :class="{ 'p-invalid': form.errors.serie }" />
                <small v-if="form.errors.serie" class="p-error">{{ form.errors.serie }}</small>
              </div>
              <div>
                <label for="nombre_equipo" class="block text-sm font-medium mb-1">Nombre del Equipo *</label>
                <InputText id="nombre_equipo" v-model="form.nombre_equipo" class="w-full" :class="{ 'p-invalid': form.errors.nombre_equipo }" />
                <small v-if="form.errors.nombre_equipo" class="p-error">{{ form.errors.nombre_equipo }}</small>
              </div>
              <div>
                <label for="tipo" class="block text-sm font-medium mb-1">Tipo *</label>
                <Dropdown id="tipo" v-model="form.tipo" :options="tipos" placeholder="Seleccione tipo" class="w-full" :class="{ 'p-invalid': form.errors.tipo }" />
                <small v-if="form.errors.tipo" class="p-error">{{ form.errors.tipo }}</small>
              </div>
              <div>
                <label for="marca" class="block text-sm font-medium mb-1">Marca *</label>
                <Dropdown id="marca" v-model="form.marca" :options="marcas" placeholder="Seleccione marca" class="w-full" :class="{ 'p-invalid': form.errors.marca }" />
                <small v-if="form.errors.marca" class="p-error">{{ form.errors.marca }}</small>
              </div>
              <div>
                <label for="modelo" class="block text-sm font-medium mb-1">Modelo</label>
                <Dropdown id="modelo" v-model="form.modelo" :options="modeloOptions" placeholder="Seleccione modelo" class="w-full" />
              </div>
              <div>
                <label for="area_id" class="block text-sm font-medium mb-1">Área *</label>
                <div class="flex gap-2">
                  <Dropdown id="area_id" v-model="form.area_id" :options="areas" optionLabel="nombre" optionValue="id" placeholder="Seleccione área" class="w-full" :class="{ 'p-invalid': form.errors.area_id }" filter />
                  <Button icon="pi pi-plus" severity="secondary" @click="openAreaDialog" type="button" />
                </div>
                <small v-if="form.errors.area_id" class="p-error">{{ form.errors.area_id }}</small>
              </div>
              <div>
                <label for="puesto_id" class="block text-sm font-medium mb-1">Puesto de Trabajo</label>
                <div class="flex gap-2">
                  <Dropdown id="puesto_id" v-model="form.puesto_id" :options="filteredPuestos" optionLabel="nombre" optionValue="id" placeholder="Seleccione puesto" class="w-full" filter />
                  <Button icon="pi pi-plus" severity="secondary" @click="openPuestoDialog" type="button" />
                </div>
              </div>
              <div>
                <label for="propietario" class="block text-sm font-medium mb-1">Propietario</label>
                <Dropdown id="propietario" v-model="form.propietario" :options="propietarioOptions" placeholder="Seleccione propietario" class="w-full" />
              </div>
              <div>
                <label for="estado" class="block text-sm font-medium mb-1">Estado *</label>
                <Dropdown id="estado" v-model="form.estado" :options="estadoOptions" placeholder="Seleccione estado" class="w-full" :class="{ 'p-invalid': form.errors.estado }" />
                <small v-if="form.errors.estado" class="p-error">{{ form.errors.estado }}</small>
              </div>
              <div>
                <label for="condicion" class="block text-sm font-medium mb-1">Condición *</label>
                <Dropdown id="condicion" v-model="form.condicion" :options="condicionOptions" placeholder="Seleccione condición" class="w-full" :class="{ 'p-invalid': form.errors.condicion }" />
                <small v-if="form.errors.condicion" class="p-error">{{ form.errors.condicion }}</small>
              </div>
              <div>
                <label for="fecha_adquisicion" class="block text-sm font-medium mb-1">Fecha de Adquisición</label>
                <Calendar id="fecha_adquisicion" v-model="form.fecha_adquisicion" showIcon dateFormat="dd/mm/yy" class="w-full" />
              </div>
            </div>
            <div>
              <label for="descripcion" class="block text-sm font-medium mb-1">Descripción</label>
              <Textarea id="descripcion" v-model="form.descripcion" rows="2" class="w-full" />
            </div>
            <div>
              <label for="observaciones" class="block text-sm font-medium mb-1">Observaciones</label>
              <Textarea id="observaciones" v-model="form.observaciones" rows="2" class="w-full" />
            </div>
          </div>
        </template>
        <template #footer>
          <div class="flex justify-end gap-2">
            <Button label="Cancelar" severity="secondary" @click="router.get('/equipos')" />
            <Button type="submit" label="Guardar" icon="pi pi-check" :loading="form.processing" />
          </div>
        </template>
      </Card>
    </form>

    <Dialog v-model:visible="areaDialog" header="Nueva Área" modal class="w-full max-w-sm">
      <div class="flex flex-col gap-3">
        <div>
          <label class="block text-sm font-medium mb-1">Nombre del Área</label>
          <InputText v-model="newArea.nombre" class="w-full" autofocus />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Descripción</label>
          <Textarea v-model="newArea.descripcion" rows="2" class="w-full" />
        </div>
      </div>
      <template #footer>
        <Button label="Cancelar" severity="secondary" @click="areaDialog = false" />
        <Button label="Guardar" icon="pi pi-check" :loading="savingArea" @click="saveArea" />
      </template>
    </Dialog>

    <Dialog v-model:visible="puestoDialog" header="Nuevo Puesto de Trabajo" modal class="w-full max-w-sm">
      <div class="flex flex-col gap-3">
        <div>
          <label class="block text-sm font-medium mb-1">Nombre del Puesto</label>
          <InputText v-model="newPuesto.nombre" class="w-full" autofocus />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Descripción</label>
          <Textarea v-model="newPuesto.descripcion" rows="2" class="w-full" />
        </div>
      </div>
      <template #footer>
        <Button label="Cancelar" severity="secondary" @click="puestoDialog = false" />
        <Button label="Guardar" icon="pi pi-check" :loading="savingPuesto" @click="savePuesto" />
      </template>
    </Dialog>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from 'primevue/card'
import InputText from 'primevue/inputtext'
import Dropdown from 'primevue/dropdown'
import Textarea from 'primevue/textarea'
import Calendar from 'primevue/calendar'
import Button from 'primevue/button'
import Dialog from 'primevue/dialog'
import axios from 'axios'

defineOptions({ layout: DashboardLayout })

const props = defineProps({
  equipo: Object,
  areas: Array,
  puestos: Array,
  tipos: Array,
  marcas: Array,
})

const estadoOptions = ['operativo', 'inactivo', 'en mantenimiento', 'de baja']
const condicionOptions = ['bueno', 'regular', 'malo']
const modeloOptions = ['EliteBook', 'ProBook', 'ThinkPad', 'Latitude', 'OptiPlex', 'MacBook Pro', 'MacBook Air', 'IdeaPad', 'Pavilion', 'Inspiron', 'Surface Pro', 'Vostro', 'ThinkCentre', 'ProDesk', 'Otro']
const propietarioOptions = ['TI Metalarc', 'Metalarc S.A.', 'Tercero', 'Leasing', 'Particular']

const filteredPuestos = computed(() => {
  if (!props.puestos) return []
  if (!form.area_id) return props.puestos
  return props.puestos.filter(p => !p.area_id || p.area_id === form.area_id)
})

const form = useForm({
  codigo: props.equipo?.codigo ?? '',
  serie: props.equipo?.serie ?? '',
  nombre_equipo: props.equipo?.nombre_equipo ?? '',
  descripcion: props.equipo?.descripcion ?? '',
  tipo: props.equipo?.tipo ?? null,
  marca: props.equipo?.marca ?? null,
  modelo: props.equipo?.modelo ?? null,
  propietario: props.equipo?.propietario ?? null,
  area_id: props.equipo?.area_id ?? null,
  puesto_id: props.equipo?.puesto_id ?? null,
  estado: props.equipo?.estado ?? 'operativo',
  condicion: props.equipo?.condicion ?? 'bueno',
  fecha_adquisicion: props.equipo?.fecha_adquisicion ?? null,
  observaciones: props.equipo?.observaciones ?? '',
})

const areaDialog = ref(false)
const puestoDialog = ref(false)
const savingArea = ref(false)
const savingPuesto = ref(false)
const newArea = ref({ nombre: '', descripcion: '' })
const newPuesto = ref({ nombre: '', descripcion: '' })

function openAreaDialog() {
  newArea.value = { nombre: '', descripcion: '' }
  areaDialog.value = true
}

function saveArea() {
  savingArea.value = true
  axios.post('/equipos/areas', newArea.value).then(res => {
    props.areas.push(res.data)
    form.area_id = res.data.id
    areaDialog.value = false
  }).catch(err => {
    alert(err.response?.data?.message || 'Error al crear área')
  }).finally(() => {
    savingArea.value = false
  })
}

function openPuestoDialog() {
  newPuesto.value = { nombre: '', descripcion: '' }
  puestoDialog.value = true
}

function savePuesto() {
  savingPuesto.value = true
  axios.post('/equipos/puestos', newPuesto.value).then(res => {
    props.puestos.push(res.data)
    form.puesto_id = res.data.id
    puestoDialog.value = false
  }).catch(err => {
    alert(err.response?.data?.message || 'Error al crear puesto')
  }).finally(() => {
    savingPuesto.value = false
  })
}

function submit() {
  if (props.equipo) {
    form.put(`/equipos/${props.equipo.id}`)
  } else {
    form.post('/equipos')
  }
}
</script>
