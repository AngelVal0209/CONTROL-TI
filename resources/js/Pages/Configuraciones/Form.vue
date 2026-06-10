<template>
  <div class="flex flex-col gap-4 max-w-3xl">
    <div class="flex items-center gap-2">
      <Button icon="pi pi-arrow-left" severity="secondary" text @click="router.get(`/equipos/${equipo.id}/configuraciones`)" />
      <h1 class="text-2xl font-bold">{{ configuracion ? 'Editar Configuración' : 'Nueva Configuración' }}</h1>
      <span class="text-gray-500">- {{ equipo?.nombre_equipo }}</span>
    </div>
    <form @submit.prevent="submit">
      <Card>
        <template #content>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="sistema_operativo" class="block text-sm font-medium mb-1">Sistema Operativo</label>
              <Dropdown id="sistema_operativo" v-model="form.sistema_operativo" :options="soOptions" placeholder="Seleccione SO" class="w-full" filter editable :class="{ 'p-invalid': form.errors.sistema_operativo }" />
              <small v-if="form.errors.sistema_operativo" class="p-error">{{ form.errors.sistema_operativo }}</small>
            </div>
            <div>
              <label for="version" class="block text-sm font-medium mb-1">Versión SO</label>
              <Dropdown id="version" v-model="form.version" :options="versionOptions" placeholder="Seleccione versión" class="w-full" filter editable :class="{ 'p-invalid': form.errors.version }" />
              <small v-if="form.errors.version" class="p-error">{{ form.errors.version }}</small>
            </div>
            <div>
              <label for="office" class="block text-sm font-medium mb-1">Microsoft Office</label>
              <Dropdown id="office" v-model="form.office" :options="officeOptions" placeholder="Seleccione versión Office" class="w-full" filter editable />
            </div>
            <div>
              <label for="antivirus" class="block text-sm font-medium mb-1">Antivirus</label>
              <Dropdown id="antivirus" v-model="form.antivirus" :options="antivirusOptions" placeholder="Seleccione antivirus" class="w-full" filter editable />
            </div>
            <div>
              <label for="cpu" class="block text-sm font-medium mb-1">Procesador (CPU)</label>
              <Dropdown id="cpu" v-model="form.cpu" :options="cpuOptions" placeholder="Seleccione CPU" class="w-full" filter editable />
            </div>
            <div>
              <label for="ram" class="block text-sm font-medium mb-1">Memoria RAM</label>
              <Dropdown id="ram" v-model="form.ram" :options="ramOptions" placeholder="Seleccione RAM" class="w-full" filter editable />
            </div>
            <div>
              <label for="disco" class="block text-sm font-medium mb-1">Almacenamiento (Disco)</label>
              <Dropdown id="disco" v-model="form.disco" :options="discoOptions" placeholder="Seleccione disco" class="w-full" filter editable />
            </div>
            <div>
              <label for="fecha_actualizacion" class="block text-sm font-medium mb-1">Fecha Actualización</label>
              <Calendar id="fecha_actualizacion" v-model="form.fecha_actualizacion" showIcon dateFormat="dd/mm/yy" class="w-full" />
            </div>
          </div>
        </template>
        <template #footer>
          <div class="flex justify-end gap-2">
            <Button label="Cancelar" severity="secondary" @click="router.get(`/equipos/${equipo.id}/configuraciones`)" />
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
import Calendar from 'primevue/calendar'
import Button from 'primevue/button'

defineOptions({ layout: DashboardLayout })

const props = defineProps({
  configuracion: Object,
  equipo: Object,
})

const soOptions = ['Windows 10 Pro', 'Windows 10 Home', 'Windows 11 Pro', 'Windows 11 Home', 'Windows Server 2019', 'Windows Server 2022', 'Ubuntu 20.04', 'Ubuntu 22.04', 'macOS Ventura', 'macOS Sonoma', 'Otro']
const versionOptions = ['21H2', '22H2', '23H2', '24H2', 'LTSC 2021', 'LTSC 2024', '20.04 LTS', '22.04 LTS', '24.04 LTS', 'Sonoma 14.x', 'Ventura 13.x', 'Otra']
const officeOptions = ['Office 2019', 'Office 2021', 'Microsoft 365', 'Office 2016', 'LibreOffice', 'Sin Office', 'Otro']
const antivirusOptions = ['Defender', 'Avast', 'McAfee', 'Norton', 'Kaspersky', 'ESET', 'Bitdefender', 'Sin Antivirus', 'Otro']
const cpuOptions = ['Intel Core i3', 'Intel Core i5', 'Intel Core i7', 'Intel Core i9', 'AMD Ryzen 3', 'AMD Ryzen 5', 'AMD Ryzen 7', 'AMD Ryzen 9', 'Apple M1', 'Apple M2', 'Apple M3', 'Intel Xeon', 'Otro']
const ramOptions = ['4 GB', '8 GB', '12 GB', '16 GB', '32 GB', '64 GB', '128 GB']
const discoOptions = ['120 GB SSD', '240 GB SSD', '256 GB SSD', '480 GB SSD', '512 GB SSD', '1 TB HDD', '1 TB SSD', '2 TB HDD', '2 TB SSD', 'Otro']

const form = useForm({
  sistema_operativo: props.configuracion?.sistema_operativo ?? null,
  version: props.configuracion?.version ?? null,
  office: props.configuracion?.office ?? null,
  antivirus: props.configuracion?.antivirus ?? null,
  cpu: props.configuracion?.cpu ?? null,
  ram: props.configuracion?.ram ?? null,
  disco: props.configuracion?.disco ?? null,
  fecha_actualizacion: props.configuracion?.fecha_actualizacion ?? null,
})

const urlBase = `/equipos/${props.equipo.id}/configuraciones`

function submit() {
  if (props.configuracion) {
    form.put(`${urlBase}/${props.configuracion.id}`)
  } else {
    form.post(urlBase)
  }
}
</script>
