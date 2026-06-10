<template>
  <div class="flex flex-col gap-6">
    <div class="flex items-center gap-2">
      <Button icon="pi pi-arrow-left" severity="secondary" text @click="router.get('/equipos')" />
      <h1 class="text-2xl font-bold">{{ equipo.nombre_equipo }}</h1>
      <Tag v-if="equipo.estado" :value="equipo.estado" :severity="estadoSeverity(equipo.estado)" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
      <Card>
        <template #title>Información General</template>
        <template #content>
          <div class="flex flex-col gap-2">
            <div class="flex justify-between"><span class="font-medium">Código:</span><span>{{ equipo.codigo }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Serie:</span><span>{{ equipo.serie }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Tipo:</span><span>{{ equipo.tipo_model?.nombre || equipo.tipo || '—' }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Marca:</span><span>{{ equipo.marca_model?.nombre || equipo.marca || '—' }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Modelo:</span><span>{{ equipo.modelo || '—' }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Área:</span><span>{{ equipo.area_model?.nombre || equipo.area || '—' }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Puesto:</span><span>{{ equipo.puesto_model?.nombre || equipo.puesto_trabajo || '—' }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Propietario:</span><span>{{ equipo.propietario || '—' }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Condición:</span><Tag :value="equipo.condicion" :severity="condicionSeverity(equipo.condicion)" /></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Fecha Adquisición:</span><span>{{ equipo.fecha_adquisicion || '—' }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Tiempo de Uso:</span><span>{{ calcularTiempoUso(equipo.fecha_adquisicion) }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Registrado por:</span><span>{{ equipo.usuario_registra?.name || '—' }}</span></div>
            <Divider />
            <div class="flex justify-between"><span class="font-medium">Descripción:</span><span>{{ equipo.descripcion || '—' }}</span></div>
          </div>
        </template>
        <template #footer>
          <div class="flex gap-2">
            <Button label="Editar" icon="pi pi-pencil" @click="router.get(`/equipos/${equipo.id}/edit`)" />
            <Button label="Configuración" icon="pi pi-sliders-h" severity="info" @click="router.get(`/equipos/${equipo.id}/configuraciones`)" />
          </div>
        </template>
      </Card>

      <div class="flex flex-col gap-4">
        <Card>
          <template #title>Última Configuración</template>
          <template #content>
            <div v-if="equipo.configuracion" class="flex flex-col gap-2">
              <div class="flex justify-between"><span class="font-medium">SO:</span><span>{{ equipo.configuracion.sistema_operativo || '—' }}</span></div>
              <Divider />
              <div class="flex justify-between"><span class="font-medium">Office:</span><span>{{ equipo.configuracion.office || '—' }}</span></div>
              <Divider />
              <div class="flex justify-between"><span class="font-medium">Antivirus:</span><span>{{ equipo.configuracion.antivirus || '—' }}</span></div>
              <Divider />
              <div class="flex justify-between"><span class="font-medium">CPU:</span><span>{{ equipo.configuracion.cpu || '—' }}</span></div>
              <Divider />
              <div class="flex justify-between"><span class="font-medium">RAM:</span><span>{{ equipo.configuracion.ram || '—' }}</span></div>
              <Divider />
              <div class="flex justify-between"><span class="font-medium">Disco:</span><span>{{ equipo.configuracion.disco || '—' }}</span></div>
            </div>
            <p v-else class="text-gray-400 italic">Sin configuración registrada</p>
          </template>
          <template #footer>
            <Button label="Ver Configuración" icon="pi pi-external-link" severity="secondary" class="w-full" @click="router.get(`/equipos/${equipo.id}/configuraciones`)" />
          </template>
        </Card>

        <Card>
          <template #title>Últimos Incidentes</template>
          <template #content>
            <div v-if="equipo.incidentes?.length" class="flex flex-col gap-2">
              <div v-for="inc in equipo.incidentes.slice(0, 5)" :key="inc.id" class="flex justify-between items-center">
                <span class="text-sm">{{ inc.titulo }}</span>
                <Tag :value="inc.estado" :severity="inc.estado === 'resuelto' ? 'success' : 'warn'" />
              </div>
            </div>
            <p v-else class="text-gray-400 italic">Sin incidentes registrados</p>
          </template>
        </Card>

        <Card>
          <template #title>Últimos Mantenimientos</template>
          <template #content>
            <div v-if="equipo.mantenimientos?.length" class="flex flex-col gap-2">
              <div v-for="mtto in equipo.mantenimientos.slice(0, 5)" :key="mtto.id" class="flex justify-between items-center">
                <span class="text-sm">{{ mtto.tipo }} - {{ mtto.fecha_realizada || mtto.fecha_programada }}</span>
                <Tag :value="mtto.estado" :severity="mtto.estado === 'realizado' ? 'success' : 'info'" />
              </div>
            </div>
            <p v-else class="text-gray-400 italic">Sin mantenimientos registrados</p>
          </template>
        </Card>

        <Card>
          <template #title>Respaldos</template>
          <template #content>
            <div v-if="equipo.respaldos?.length" class="flex flex-col gap-2">
              <div v-for="res in equipo.respaldos.slice(0, 5)" :key="res.id" class="flex justify-between items-center">
                <span class="text-sm">{{ res.tipo }} - {{ res.fecha_respaldo }}</span>
                <Tag :value="res.tipo" :severity="res.tipo === 'completo' ? 'success' : 'info'" />
              </div>
            </div>
            <p v-else class="text-gray-400 italic">Sin respaldos registrados</p>
          </template>
        </Card>
      </div>
    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from 'primevue/card'
import Button from 'primevue/button'
import Tag from 'primevue/tag'
import Divider from 'primevue/divider'

defineOptions({ layout: DashboardLayout })

const props = defineProps({
  equipo: Object,
})

function estadoSeverity(estado) {
  const map = { operativo: 'success', inactivo: 'danger', 'en mantenimiento': 'warn', 'de baja': 'info' }
  return map[estado] || null
}

function condicionSeverity(condicion) {
  const map = { bueno: 'success', regular: 'warn', malo: 'danger' }
  return map[condicion] || null
}

function calcularTiempoUso(fecha) {
  if (!fecha) return '—'
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
</script>
