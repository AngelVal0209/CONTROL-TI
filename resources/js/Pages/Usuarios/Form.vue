<template>
  <div class="flex flex-col gap-6">
    <div class="flex items-center gap-2">
      <Button icon="pi pi-arrow-left" severity="secondary" text @click="router.get('/usuarios')" />
      <h1 class="text-2xl font-bold">{{ usuario ? 'Editar Usuario' : 'Nuevo Usuario' }}</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
      <Card>
        <template #title>Datos del Usuario</template>
        <template #content>
          <form @submit.prevent="submit">
            <div class="flex flex-col gap-4">
              <div>
                <label for="name" class="block text-sm font-medium mb-1">Nombre</label>
                <InputText id="name" v-model="form.name" class="w-full" :class="{ 'p-invalid': form.errors.name }" />
                <small v-if="form.errors.name" class="text-red-500">{{ form.errors.name }}</small>
              </div>
              <div>
                <label for="documento" class="block text-sm font-medium mb-1">N° Documento</label>
                <InputText id="documento" v-model="form.documento" class="w-full" :class="{ 'p-invalid': form.errors.documento }" />
                <small v-if="form.errors.documento" class="text-red-500">{{ form.errors.documento }}</small>
              </div>
              <div>
                <label for="email" class="block text-sm font-medium mb-1">Correo Electrónico</label>
                <InputText id="email" v-model="form.email" type="email" class="w-full" :class="{ 'p-invalid': form.errors.email }" />
                <small v-if="form.errors.email" class="text-red-500">{{ form.errors.email }}</small>
              </div>
              <div>
                <label for="telefono" class="block text-sm font-medium mb-1">Teléfono</label>
                <InputText id="telefono" v-model="form.telefono" class="w-full" />
              </div>
              <div>
                <label for="password" class="block text-sm font-medium mb-1">{{ usuario ? 'Nueva Contraseña (dejar vacío para mantener)' : 'Contraseña' }}</label>
                <Password id="password" v-model="form.password" :feedback="false" class="w-full" :class="{ 'p-invalid': form.errors.password }" />
                <small v-if="form.errors.password" class="text-red-500">{{ form.errors.password }}</small>
              </div>
              <div class="flex gap-2 justify-end">
                <Button label="Cancelar" severity="secondary" @click="router.get('/usuarios')" />
                <Button type="submit" label="Guardar" icon="pi pi-check" :loading="form.processing" />
              </div>
            </div>
          </form>
        </template>
      </Card>

      <Card>
        <template #title>Roles</template>
        <template #content>
          <div class="flex flex-col gap-3">
            <div v-for="role in roles" :key="role.id" class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded">
              <Checkbox :inputId="`role_${role.id}`" :value="role.id" v-model="form.roles" />
              <label :for="`role_${role.id}`" class="cursor-pointer">{{ role.name }}</label>
            </div>
            <p v-if="!roles?.length" class="text-gray-400 italic">No hay roles disponibles</p>
          </div>
        </template>
      </Card>
    </div>
  </div>
</template>

<script setup>
import { router, useForm } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from 'primevue/card'
import InputText from 'primevue/inputtext'
import Password from 'primevue/password'
import Button from 'primevue/button'
import Checkbox from 'primevue/checkbox'

defineOptions({ layout: DashboardLayout })

const props = defineProps({
  usuario: Object,
  roles: Array,
})

const form = useForm({
  name: props.usuario?.name || '',
  documento: props.usuario?.documento || '',
  email: props.usuario?.email || '',
  telefono: props.usuario?.telefono || '',
  password: '',
  roles: props.usuario?.roles?.map(r => r.id || r) || [],
})

function submit() {
  if (props.usuario) {
    form.put(`/usuarios/${props.usuario.id}`)
  } else {
    form.post('/usuarios')
  }
}
</script>
