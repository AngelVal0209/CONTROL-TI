<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-600 to-indigo-900">
    <Card class="w-full max-w-md mx-4 shadow-2xl border-none">
      <template #title>
        <div class="text-center py-4">
          <div class="w-20 h-20 bg-primary rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
            <i class="pi pi-cog text-4xl text-white"></i>
          </div>
          <h2 class="text-2xl font-bold text-gray-800">TI Control</h2>
          <p class="text-sm text-gray-500 mt-1">Sistema de Gestión de Activos TI</p>
        </div>
      </template>
      <template #content>
        <form @submit.prevent="submit" class="px-2">
          <Message v-if="form.errors?.usuario" severity="error" class="mb-4" :closable="false">
            {{ form.errors.usuario?.[0] || 'Credenciales inválidas' }}
          </Message>
          <div class="flex flex-col gap-5">
            <div>
              <label for="usuario" class="block text-sm font-medium text-gray-700 mb-1">N° Documento</label>
              <IconField>
                <InputIcon>
                  <i class="pi pi-user" />
                </InputIcon>
                <InputText id="usuario" v-model="form.usuario" type="text" class="w-full pl-10" :class="{ 'p-invalid': form.errors.usuario }" placeholder="Ingrese su número de documento" />
              </IconField>
              <small v-if="form.errors.usuario" class="p-error">{{ form.errors.usuario }}</small>
            </div>
            <div>
              <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
              <IconField>
                <InputIcon>
                  <i class="pi pi-lock" />
                </InputIcon>
                <Password id="password" v-model="form.password" :feedback="false" class="w-full" :class="{ 'p-invalid': form.errors.password }" placeholder="Ingrese su contraseña" toggleMask />
              </IconField>
              <small v-if="form.errors.password" class="p-error">{{ form.errors.password }}</small>
            </div>
            <Button type="submit" label="Ingresar" icon="pi pi-sign-in" class="w-full p-3" :loading="form.processing" />
          </div>
        </form>
      </template>
    </Card>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import Card from 'primevue/card'
import InputText from 'primevue/inputtext'
import InputIcon from 'primevue/inputicon'
import IconField from 'primevue/iconfield'
import Password from 'primevue/password'
import Button from 'primevue/button'
import Message from 'primevue/message'

defineOptions({ layout: null })

const form = useForm({
  usuario: '',
  password: '',
})

function submit() {
  form.post('/login')
}
</script>
