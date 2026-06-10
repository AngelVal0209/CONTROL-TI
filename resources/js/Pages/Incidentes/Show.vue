<template>
  <div class="flex flex-col gap-6">
    <div class="flex items-center gap-2">
      <Button icon="pi pi-arrow-left" severity="secondary" text @click="router.get('/incidentes')" />
      <h1 class="text-2xl font-bold">{{ incidente.titulo }}</h1>
      <Tag :value="incidente.estado" :severity="incidente.estado === 'resuelto' ? 'success' : incidente.estado === 'en_proceso' ? 'warn' : 'danger'" />
      <Tag :value="incidente.prioridad" :severity="incidente.prioridad === 'alta' ? 'danger' : incidente.prioridad === 'media' ? 'warn' : 'info'" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
      <Card>
        <template #title>Detalles del Incidente</template>
        <template #content>
          <div class="flex flex-col gap-2">
            <div class="flex justify-between"><span class="font-medium">Equipo:</span><span>{{ incidente.equipo?.nombre_equipo }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Reportado por:</span><span>{{ incidente.usuarioReporta?.name }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Prioridad:</span><Tag :value="incidente.prioridad" :severity="incidente.prioridad === 'alta' ? 'danger' : incidente.prioridad === 'media' ? 'warn' : 'info'" /></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Estado:</span><Tag :value="incidente.estado" :severity="incidente.estado === 'resuelto' ? 'success' : incidente.estado === 'en_proceso' ? 'warn' : 'danger'" /></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Fecha Reporte:</span><span>{{ new Date(incidente.created_at).toLocaleString('es-ES') }}</span></div>
            <Divider v-if="incidente.fecha_resolucion" />
            <div v-if="incidente.fecha_resolucion" class="flex justify-between"><span class="font-medium">Fecha Resolución:</span><span>{{ new Date(incidente.fecha_resolucion).toLocaleString('es-ES') }}</span></div>
            <Divider />
            <div><span class="font-medium">Descripción:</span></div>
            <p class="text-gray-700 whitespace-pre-wrap bg-gray-50 p-3 rounded">{{ incidente.descripcion }}</p>
            <Divider v-if="incidente.solucion" />
            <div v-if="incidente.solucion"><span class="font-medium">Solución:</span></div>
            <p v-if="incidente.solucion" class="text-gray-700 whitespace-pre-wrap bg-green-50 p-3 rounded">{{ incidente.solucion }}</p>
          </div>
        </template>
      </Card>

      <Card>
        <template #title>Evidencias</template>
        <template #content>
          <div v-if="incidente.evidencias" class="flex flex-col gap-2">
            <div v-for="(ev, idx) in incidente.evidencias" :key="idx" class="flex items-center gap-2 p-2 bg-gray-50 rounded">
              <i class="pi pi-file text-primary"></i>
              <span class="text-sm">{{ typeof ev === 'string' ? ev : ev.nombre_original || ev.ruta }}</span>
            </div>
          </div>
          <p v-else class="text-gray-400 italic">Sin evidencias adjuntas</p>
        </template>
        <template v-if="incidente.estado !== 'resuelto'" #footer>
          <Button label="Resolver Incidente" icon="pi pi-check-circle" severity="success" class="w-full" @click="resolver" />
        </template>
      </Card>
    </div>

    <ConfirmDialog />
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { useConfirm } from 'primevue/useconfirm'
import { useToast } from 'primevue/usetoast'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from 'primevue/card'
import Button from 'primevue/button'
import Tag from 'primevue/tag'
import Divider from 'primevue/divider'
import ConfirmDialog from 'primevue/confirmdialog'

defineOptions({ layout: DashboardLayout })

const props = defineProps({
  incidente: Object,
})

const confirm = useConfirm()
const toast = useToast()

function resolver() {
  confirm.require({
    message: `¿Resolver el incidente "${props.incidente.titulo}"?`,
    header: 'Resolver Incidente',
    icon: 'pi pi-check-circle',
    acceptLabel: 'Resolver',
    accept: () => {
      router.patch(`/incidentes/${props.incidente.id}/resolver`, {}, {
        onSuccess: () => {
          toast.add({ severity: 'success', summary: 'Resuelto', detail: 'Incidente resuelto correctamente', life: 3000 })
          router.reload()
        },
        onError: () => toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudo resolver el incidente', life: 3000 }),
      })
    },
  })
}
</script>
