<template>
  <v-card class="mx-auto" max-width="400">
    <v-card-title>Register</v-card-title>
    <v-card-text>
      <v-form ref="formRef" v-model="valid" @submit.prevent="handleRegister">
        <v-text-field
          v-model="form.username"
          class="mb-4"
          density="comfortable"
          label="Username"
          :rules="[rules.required]"
          variant="outlined"
        />

        <v-text-field
          v-model="form.password"
          class="mb-4"
          density="comfortable"
          label="Password"
          :rules="[rules.required, rules.minLength]"
          type="password"
          variant="outlined"
        />

        <v-text-field
          v-model="confirmPassword"
          class="mb-4"
          density="comfortable"
          label="Confirm Password"
          :rules="[rules.required, rules.passwordMatch]"
          type="password"
          variant="outlined"
        />

        <v-btn
          block
          color="primary"
          :disabled="!valid"
          :loading="loading"
          size="large"
          type="submit"
        >
          Register
        </v-btn>
      </v-form>

      <v-alert
        v-if="error"
        class="mt-4"
        closable
        type="error"
        @click:close="error = null"
      >
        {{ error }}
      </v-alert>

      <v-divider class="my-4" />

      <div class="text-center">
        <span>Already have an account? </span>
        <v-btn color="primary" variant="text" @click="$emit('switch-to-login')">
          Login
        </v-btn>
      </div>
    </v-card-text>
  </v-card>
</template>

<script setup lang="ts">
  import type { ApiError, RegisterRequest } from '@/types'
  import { reactive, ref } from 'vue'
  import { authService } from '@/services/authService'

  const emit = defineEmits<{
    'register-success': []
    'switch-to-login': []
  }>()

  const formRef = ref()
  const valid = ref(false)
  const loading = ref(false)
  const error = ref<string | null>(null)
  const confirmPassword = ref('')

  const form = reactive<RegisterRequest>({
    username: '',
    password: '',
  })

  const rules = {
    required: (v: string) => !!v || 'Field is required',
    minLength: (v: string) => v.length >= 6 || 'Password must be at least 6 characters',
    passwordMatch: (v: string) => v === form.password || 'Passwords do not match',
  }

  async function handleRegister () {
    if (!valid.value) return

    loading.value = true
    error.value = null

    try {
      await authService.register(form)
      emit('register-success')
    } catch (error_) {
      const apiError = error_ as ApiError
      error.value = apiError.message || 'Registration failed'
    } finally {
      loading.value = false
    }
  }
</script>
