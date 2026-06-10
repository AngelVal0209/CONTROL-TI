<template>
  <div class="flex flex-col gap-4 max-w-3xl">
    <div class="flex items-center gap-2">
      <Button icon="pi pi-arrow-left" severity="secondary" text @click="router.get('/renovaciones')" />
      <h1 class="text-2xl font-bold">{{ renovacion ? 'Editar Renovación' : 'Nueva Renovación' }}</h1>
    </div>
    <form @submit.prevent="submit" enctype="multipart/form-data">
      <Card>
        <template #content>
          <div class="flex flex-col gap-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label for="nombre" class="block text-sm font-medium mb-1">Nombre *</label>
                <InputText id="nombre" v-model="form.nombre" class="w-full" :class="{ 'p-invalid': form.errors.nombre }" />
                <small v-if="form.errors.nombre" class="p-error">{{ form.errors.nombre }}</small>
              </div>
              <div>
                <label for="proveedor" class="block text-sm font-medium mb-1">Proveedor</label>
                <InputText id="proveedor" v-model="form.proveedor" class="w-full" />
              </div>
              <div>
                <label for="tipo" class="block text-sm font-medium mb-1">Tipo *</label>
                <Dropdown id="tipo" v-model="form.tipo" :options="tipoOptions" placeholder="Seleccione tipo" class="w-full" filter editable :class="{ 'p-invalid': form.errors.tipo }" />
                <small v-if="form.errors.tipo" class="p-error">{{ form.errors.tipo }}</small>
              </div>
              <div>
                <label for="periodo" class="block text-sm font-medium mb-1">Período</label>
                <Dropdown id="periodo" v-model="form.periodo" :options="periodoOptions" placeholder="Seleccione período" class="w-full" filter editable />
              </div>
              <div>
                <label for="monto" class="block text-sm font-medium mb-1">Monto</label>
                <InputNumber id="monto" v-model="form.monto" :min="0" :maxFractionDigits="2" class="w-full" />
              </div>
              <div>
                <label for="moneda" class="block text-sm font-medium mb-1">Moneda</label>
                <SelectButton v-model="form.moneda" :options="monedaOptions" class="w-full" />
              </div>
              <div>
                <label for="fecha_inicio" class="block text-sm font-medium mb-1">Fecha de Inicio</label>
                <Calendar id="fecha_inicio" v-model="form.fecha_inicio" showIcon dateFormat="dd/mm/yy" class="w-full" />
              </div>
              <div>
                <label for="fecha_vencimiento" class="block text-sm font-medium mb-1">Fecha de Vencimiento *</label>
                <Calendar id="fecha_vencimiento" v-model="form.fecha_vencimiento" showIcon dateFormat="dd/mm/yy" class="w-full" :class="{ 'p-invalid': form.errors.fecha_vencimiento }" />
                <small v-if="form.errors.fecha_vencimiento" class="p-error">{{ form.errors.fecha_vencimiento }}</small>
              </div>
              <div>
                <label for="estado" class="block text-sm font-medium mb-1">Estado</label>
                <Dropdown id="estado" v-model="form.estado" :options="estadoOptions" placeholder="Seleccione estado" class="w-full" filter editable />
              </div>
              <div>
                <label for="archivo" class="block text-sm font-medium mb-1">Archivo</label>
                <FileUpload mode="basic" name="archivo" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.png,.rar,.zip" :auto="false" :chooseLabel="archivoName || 'Seleccionar archivo'" class="w-full" @select="onFileSelect" />
                <small v-if="renovacion?.archivo" class="text-gray-500 block mt-1">Archivo actual: {{ renovacion.archivo.split('/').pop() }}</small>
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
            <Button label="Cancelar" severity="secondary" @click="router.get('/renovaciones')" />
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
import InputText from 'primevue/inputtext'
import InputNumber from 'primevue/inputnumber'
import Dropdown from 'primevue/dropdown'
import SelectButton from 'primevue/selectbutton'
import Textarea from 'primevue/textarea'
import Calendar from 'primevue/calendar'
import FileUpload from 'primevue/fileupload'
import Button from 'primevue/button'

defineOptions({ layout: DashboardLayout })

const props = defineProps({
  renovacion: Object,
})

const tipoOptions = ['licencia', 'suscripcion', 'servicio', 'pago_proveedor', 'mantenimiento', 'dominio', 'hosting', 'seguro', 'otro']
const periodoOptions = ['mensual', 'trimestral', 'semestral', 'anual', 'bianual', 'unico']
const estadoOptions = ['activo', 'vencido', 'renovado', 'cancelado']
const monedaOptions = ['PEN', 'USD']

const archivoName = ref(props.renovacion?.archivo ? props.renovacion.archivo.split('/').pop() : null)

const form = useForm({
  nombre: props.renovacion?.nombre ?? '',
  proveedor: props.renovacion?.proveedor ?? '',
  tipo: props.renovacion?.tipo ?? null,
  monto: props.renovacion?.monto ?? null,
  moneda: props.renovacion?.moneda ?? 'PEN',
  fecha_inicio: props.renovacion?.fecha_inicio ?? null,
  fecha_vencimiento: props.renovacion?.fecha_vencimiento ?? null,
  periodo: props.renovacion?.periodo ?? null,
  estado: props.renovacion?.estado ?? 'activo',
  descripcion: props.renovacion?.descripcion ?? '',
  observaciones: props.renovacion?.observaciones ?? '',
  archivo: null,
})

function onFileSelect(event) {
  form.archivo = event.files[0]
  archivoName.value = event.files[0].name
}

function submit() {
  if (props.renovacion) {
    form.post(`/renovaciones/${props.renovacion.id}`, {
      _method: 'PUT',
      forceFormData: true,
    })
  } else {
    form.post('/renovaciones', {
      forceFormData: true,
    })
  }
}
</script>
