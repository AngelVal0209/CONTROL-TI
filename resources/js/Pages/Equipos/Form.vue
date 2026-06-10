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
                <label for="tipo_id" class="block text-sm font-medium mb-1">Tipo *</label>
                <div class="flex gap-2">
                  <Dropdown id="tipo_id" v-model="form.tipo_id" :options="tipos" optionLabel="nombre" optionValue="id" placeholder="Seleccione tipo" class="w-full" :class="{ 'p-invalid': form.errors.tipo_id }" filter />
                  <Button icon="pi pi-plus" severity="secondary" @click="openTipoDialog" type="button" />
                </div>
                <small v-if="form.errors.tipo_id" class="p-error">{{ form.errors.tipo_id }}</small>
              </div>
              <div>
                <label for="marca_id" class="block text-sm font-medium mb-1">Marca *</label>
                <div class="flex gap-2">
                  <Dropdown id="marca_id" v-model="form.marca_id" :options="marcas" optionLabel="nombre" optionValue="id" placeholder="Seleccione marca" class="w-full" :class="{ 'p-invalid': form.errors.marca_id }" filter />
                  <Button icon="pi pi-plus" severity="secondary" @click="openMarcaDialog" type="button" />
                </div>
                <small v-if="form.errors.marca_id" class="p-error">{{ form.errors.marca_id }}</small>
              </div>
              <div>
                <label for="modelo_id" class="block text-sm font-medium mb-1">Modelo</label>
                <div class="flex gap-2">
                  <Dropdown id="modelo_id" v-model="form.modelo_id" :options="modelos" optionLabel="nombre" optionValue="id" placeholder="Seleccione modelo" class="w-full" filter />
                  <Button icon="pi pi-plus" severity="secondary" @click="openModeloDialog" type="button" />
                </div>
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
                <Dropdown id="propietario" v-model="form.propietario" :options="propietarioOptions" placeholder="Seleccione propietario" class="w-full" filter editable @change="onPropietarioChange" />
                <Transition name="p-toggleable-content">
                  <div v-if="mostrarUsuario" class="mt-2">
                    <label class="block text-sm font-medium mb-1">Seleccionar Usuario</label>
                    <Dropdown v-model="form.propietario" :options="props.usuarios" optionLabel="label" optionValue="label" placeholder="Seleccione usuario registrado" class="w-full" filter />
                  </div>
                </Transition>
              </div>
              <div>
                <label for="estado" class="block text-sm font-medium mb-1">Estado *</label>
                <Dropdown id="estado" v-model="form.estado" :options="estadoOptions" placeholder="Seleccione estado" class="w-full" filter editable :class="{ 'p-invalid': form.errors.estado }" />
                <small v-if="form.errors.estado" class="p-error">{{ form.errors.estado }}</small>
              </div>
              <div>
                <label for="condicion" class="block text-sm font-medium mb-1">Condición *</label>
                <Dropdown id="condicion" v-model="form.condicion" :options="condicionOptions" placeholder="Seleccione condición" class="w-full" filter editable :class="{ 'p-invalid': form.errors.condicion }" />
                <small v-if="form.errors.condicion" class="p-error">{{ form.errors.condicion }}</small>
              </div>
              <div>
                <label for="fecha_adquisicion" class="block text-sm font-medium mb-1">Fecha de Adquisición</label>
                <Calendar id="fecha_adquisicion" v-model="form.fecha_adquisicion" showIcon dateFormat="dd/mm/yy" class="w-full" @date-select="onFechaChange" @input="onFechaChange" />
              </div>
              <div v-if="tiempoUso">
                <label class="block text-sm font-medium mb-1">Tiempo de Uso</label>
                <InputText :value="tiempoUso" class="w-full" disabled />
              </div>
            </div>
            <div>
              <label for="descripcion" class="block text-sm font-medium mb-1">Descripción</label>
              <Textarea id="descripcion" v-model="form.descripcion" rows="2" class="w-full" />
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

    <Dialog v-model:visible="tipoDialog" header="Nuevo Tipo" modal class="w-full max-w-sm">
      <div class="flex flex-col gap-3">
        <div>
          <label class="block text-sm font-medium mb-1">Nombre del Tipo</label>
          <InputText v-model="newTipo.nombre" class="w-full" autofocus />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Descripción</label>
          <Textarea v-model="newTipo.descripcion" rows="2" class="w-full" />
        </div>
      </div>
      <template #footer>
        <Button label="Cancelar" severity="secondary" @click="tipoDialog = false" />
        <Button label="Guardar" icon="pi pi-check" :loading="savingTipo" @click="saveTipo" />
      </template>
    </Dialog>

    <Dialog v-model:visible="marcaDialog" header="Nueva Marca" modal class="w-full max-w-sm">
      <div class="flex flex-col gap-3">
        <div>
          <label class="block text-sm font-medium mb-1">Nombre de la Marca</label>
          <InputText v-model="newMarca.nombre" class="w-full" autofocus />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Descripción</label>
          <Textarea v-model="newMarca.descripcion" rows="2" class="w-full" />
        </div>
      </div>
      <template #footer>
        <Button label="Cancelar" severity="secondary" @click="marcaDialog = false" />
        <Button label="Guardar" icon="pi pi-check" :loading="savingMarca" @click="saveMarca" />
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

    <Dialog v-model:visible="modeloDialog" header="Nuevo Modelo" modal class="w-full max-w-sm">
      <div class="flex flex-col gap-3">
        <div>
          <label class="block text-sm font-medium mb-1">Nombre del Modelo</label>
          <InputText v-model="newModelo.nombre" class="w-full" autofocus />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Descripción</label>
          <Textarea v-model="newModelo.descripcion" rows="2" class="w-full" />
        </div>
      </div>
      <template #footer>
        <Button label="Cancelar" severity="secondary" @click="modeloDialog = false" />
        <Button label="Guardar" icon="pi pi-check" :loading="savingModelo" @click="saveModelo" />
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
  modelos: Array,
  usuarios: Array,
})

const estadoOptions = ['operativo', 'inactivo', 'en mantenimiento', 'de baja']
const condicionOptions = ['bueno', 'regular', 'malo']
const propietarioOptions = ['TI Metalarc', 'Metalarc S.A.', 'Tercero', 'Leasing', 'Particular', 'USUARIO']
const mostrarUsuario = ref(false)

if (props.equipo?.propietario && props.usuarios?.some(u => u.label === props.equipo.propietario)) {
  mostrarUsuario.value = true
}

function onPropietarioChange(value) {
  if (value.value === 'USUARIO') {
    mostrarUsuario.value = true
    form.propietario = null
  } else {
    mostrarUsuario.value = false
  }
}

const filteredPuestos = computed(() => {
  if (!props.puestos) return []
  if (!form.area_id) return props.puestos
  return props.puestos.filter(p => !p.area_id || p.area_id === form.area_id)
})

function calcularTiempoUso(fecha) {
  if (!fecha) return null
  const adq = new Date(fecha)
  const hoy = new Date()
  if (adq > hoy) return '—'
  let años = hoy.getFullYear() - adq.getFullYear()
  let meses = hoy.getMonth() - adq.getMonth()
  if (meses < 0) { años--; meses += 12 }
  const partes = []
  if (años > 0) partes.push(`${años} año${años !== 1 ? 's' : ''}`)
  if (meses > 0) partes.push(`${meses} mes${meses !== 1 ? 'es' : ''}`)
  return partes.length ? partes.join(', ') : '< 1 mes'
}

const tiempoUso = computed(() => calcularTiempoUso(form.fecha_adquisicion))

function onFechaChange() {
  // reactividad: el computed se actualiza solo
}

const form = useForm({
  codigo: props.equipo?.codigo ?? '',
  serie: props.equipo?.serie ?? '',
  nombre_equipo: props.equipo?.nombre_equipo ?? '',
  descripcion: props.equipo?.descripcion ?? '',
  tipo_id: props.equipo?.tipo_id ?? null,
  marca_id: props.equipo?.marca_id ?? null,
  modelo_id: props.equipo?.modelo_id ?? null,
  propietario: props.equipo?.propietario ?? null,
  area_id: props.equipo?.area_id ?? null,
  puesto_id: props.equipo?.puesto_id ?? null,
  estado: props.equipo?.estado ?? 'operativo',
  condicion: props.equipo?.condicion ?? 'bueno',
  fecha_adquisicion: props.equipo?.fecha_adquisicion ?? null,
})

const areaDialog = ref(false)
const puestoDialog = ref(false)
const tipoDialog = ref(false)
const marcaDialog = ref(false)
const modeloDialog = ref(false)
const savingArea = ref(false)
const savingPuesto = ref(false)
const savingTipo = ref(false)
const savingMarca = ref(false)
const savingModelo = ref(false)
const newArea = ref({ nombre: '', descripcion: '' })
const newPuesto = ref({ nombre: '', descripcion: '' })
const newTipo = ref({ nombre: '', descripcion: '' })
const newMarca = ref({ nombre: '', descripcion: '' })
const newModelo = ref({ nombre: '', descripcion: '' })

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

function openTipoDialog() {
  newTipo.value = { nombre: '', descripcion: '' }
  tipoDialog.value = true
}

function saveTipo() {
  savingTipo.value = true
  axios.post('/equipos/tipos', newTipo.value).then(res => {
    props.tipos.push(res.data)
    form.tipo_id = res.data.id
    tipoDialog.value = false
  }).catch(err => {
    alert(err.response?.data?.message || 'Error al crear tipo')
  }).finally(() => {
    savingTipo.value = false
  })
}

function openMarcaDialog() {
  newMarca.value = { nombre: '', descripcion: '' }
  marcaDialog.value = true
}

function saveMarca() {
  savingMarca.value = true
  axios.post('/equipos/marcas', newMarca.value).then(res => {
    props.marcas.push(res.data)
    form.marca_id = res.data.id
    marcaDialog.value = false
  }).catch(err => {
    alert(err.response?.data?.message || 'Error al crear marca')
  }).finally(() => {
    savingMarca.value = false
  })
}

function openModeloDialog() {
  newModelo.value = { nombre: '', descripcion: '' }
  modeloDialog.value = true
}

function saveModelo() {
  savingModelo.value = true
  axios.post('/equipos/modelos', newModelo.value).then(res => {
    props.modelos.push(res.data)
    form.modelo_id = res.data.id
    modeloDialog.value = false
  }).catch(err => {
    alert(err.response?.data?.message || 'Error al crear modelo')
  }).finally(() => {
    savingModelo.value = false
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
