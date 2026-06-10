<template>
  <div class="flex flex-col gap-6">
    <h1 class="text-2xl font-bold">Reportes</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <Card>
        <template #title>
          <div class="flex items-center gap-2">
            <i class="pi pi-file-pdf text-red-500 text-xl"></i>
            <span>Reportes PDF</span>
          </div>
        </template>
        <template #content>
          <div class="flex flex-col gap-3">
            <Button label="Inventario General" icon="pi pi-file-pdf" severity="danger" class="w-full justify-start" @click="router.get('/reportes/pdf/inventario-general')" />
            <Button label="Inventario por Área" icon="pi pi-file-pdf" severity="danger" class="w-full justify-start" @click="router.get('/reportes/pdf/inventario-area')" />
            <Button label="Incidentes" icon="pi pi-file-pdf" severity="danger" class="w-full justify-start" @click="router.get('/reportes/pdf/incidentes')" />
            <Button label="Mantenimientos" icon="pi pi-file-pdf" severity="danger" class="w-full justify-start" @click="router.get('/reportes/pdf/mantenimientos')" />
            <Button label="Respaldos" icon="pi pi-file-pdf" severity="danger" class="w-full justify-start" @click="router.get('/reportes/pdf/respaldos')" />
          </div>
        </template>
      </Card>

      <Card>
        <template #title>
          <div class="flex items-center gap-2">
            <i class="pi pi-file-excel text-green-500 text-xl"></i>
            <span>Exportar Excel</span>
          </div>
        </template>
        <template #content>
          <div class="flex flex-col gap-3">
            <Button label="Equipos" icon="pi pi-file-excel" severity="success" class="w-full justify-start" @click="router.get('/reportes/excel/equipos')" />
            <Button label="Incidentes" icon="pi pi-file-excel" severity="success" class="w-full justify-start" @click="router.get('/reportes/excel/incidentes')" />
            <Button label="Mantenimientos" icon="pi pi-file-excel" severity="success" class="w-full justify-start" @click="router.get('/reportes/excel/mantenimientos')" />
          </div>
        </template>
      </Card>
    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from 'primevue/card'
import Button from 'primevue/button'

defineOptions({ layout: DashboardLayout })
</script>

