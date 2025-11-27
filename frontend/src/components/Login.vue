<template>
  <v-card class="mx-auto" max-width="400">
    <v-card-title>Login</v-card-title>
    <v-card-text>
      <v-form ref="formRef" v-model="valid" @submit.prevent="handleLogin">
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
          :rules="[rules.required]"
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
          Login
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
        <span>Don't have an account? </span>
        <v-btn color="primary" variant="text" @click="$emit('switch-to-register')">
          Register
        </v-btn>
      </div>
    </v-card-text>
  </v-card>
</template>

<script setup lang="ts">
  import type { ApiError, LoginRequest } from '@/types'
  import { reactive, ref } from 'vue'
  import { authService } from '@/services/authService'

  const emit = defineEmits<{
    'login-success': []
    'switch-to-register': []
  }>()

  const formRef = ref()
  const valid = ref(false)
  const loading = ref(false)
  const error = ref<string | null>(null)

  const form = reactive<LoginRequest>({
    username: '',
    password: '',
  })

  const rules = {
    required: (v: string) => !!v || 'Field is required',
  }

  async function handleLogin () {
    if (!valid.value) return

    loading.value = true
    error.value = null

    try {
      await authService.login(form)
      emit('login-success')
    } catch (error_) {
      const apiError = error_ as ApiError
      error.value = apiError.message || 'Login failed'
    } finally {
      loading.value = false
    }
  }
</script>
