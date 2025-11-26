<template>
  <v-card max-width="400" class="mx-auto">
    <v-card-title>Login</v-card-title>
    <v-card-text>
      <v-form ref="formRef" v-model="valid" @submit.prevent="handleLogin">
        <v-text-field
          v-model="form.username"
          label="Username"
          :rules="[rules.required]"
          variant="outlined"
          density="comfortable"
          class="mb-4"
        />

        <v-text-field
          v-model="form.password"
          label="Password"
          type="password"
          :rules="[rules.required]"
          variant="outlined"
          density="comfortable"
          class="mb-4"
        />

        <v-btn
          type="submit"
          color="primary"
          :loading="loading"
          :disabled="!valid"
          block
          size="large"
        >
          Login
        </v-btn>
      </v-form>

      <v-alert v-if="error" type="error" class="mt-4" closable @click:close="error = null">
        {{ error }}
      </v-alert>

      <v-divider class="my-4" />

      <div class="text-center">
        <span>Don't have an account? </span>
        <v-btn variant="text" color="primary" @click="$emit('switchToRegister')">
          Register
        </v-btn>
      </div>
    </v-card-text>
  </v-card>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import { authService } from '@/services/authService'
import type { LoginRequest, ApiError } from '@/types'

const emit = defineEmits<{
  loginSuccess: []
  switchToRegister: []
}>()

const formRef = ref()
const valid = ref(false)
const loading = ref(false)
const error = ref<string | null>(null)

const form = reactive<LoginRequest>({
  username: '',
  password: ''
})

const rules = {
  required: (v: string) => !!v || 'Field is required'
}

const handleLogin = async () => {
  if (!valid.value) return

  loading.value = true
  error.value = null

  try {
    await authService.login(form)
    emit('loginSuccess')
  } catch (err) {
    const apiError = err as ApiError
    error.value = apiError.message || 'Login failed'
  } finally {
    loading.value = false
  }
}
</script>