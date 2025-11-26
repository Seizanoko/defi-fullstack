<template>
  <v-card max-width="400" class="mx-auto">
    <v-card-title>Register</v-card-title>
    <v-card-text>
      <v-form ref="formRef" v-model="valid" @submit.prevent="handleRegister">
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
          :rules="[rules.required, rules.minLength]"
          variant="outlined"
          density="comfortable"
          class="mb-4"
        />

        <v-text-field
          v-model="confirmPassword"
          label="Confirm Password"
          type="password"
          :rules="[rules.required, rules.passwordMatch]"
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
          Register
        </v-btn>
      </v-form>

      <v-alert v-if="error" type="error" class="mt-4" closable @click:close="error = null">
        {{ error }}
      </v-alert>

      <v-divider class="my-4" />

      <div class="text-center">
        <span>Already have an account? </span>
        <v-btn variant="text" color="primary" @click="$emit('switchToLogin')">
          Login
        </v-btn>
      </div>
    </v-card-text>
  </v-card>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import { authService } from '@/services/authService'
import type { RegisterRequest, ApiError } from '@/types'

const emit = defineEmits<{
  registerSuccess: []
  switchToLogin: []
}>()

const formRef = ref()
const valid = ref(false)
const loading = ref(false)
const error = ref<string | null>(null)
const confirmPassword = ref('')

const form = reactive<RegisterRequest>({
  username: '',
  password: ''
})

const rules = {
  required: (v: string) => !!v || 'Field is required',
  minLength: (v: string) => v.length >= 6 || 'Password must be at least 6 characters',
  passwordMatch: (v: string) => v === form.password || 'Passwords do not match'
}

const handleRegister = async () => {
  if (!valid.value) return

  loading.value = true
  error.value = null

  try {
    await authService.register(form)
    emit('registerSuccess')
  } catch (err) {
    const apiError = err as ApiError
    error.value = apiError.message || 'Registration failed'
  } finally {
    loading.value = false
  }
}
</script>