<template>
  <v-card>
    <v-card-title>Route Calculator</v-card-title>
    <v-card-text>
      <v-form ref="formRef" v-model="valid" @submit.prevent="calculateRoute">
        <v-text-field
          v-model="form.fromStationId"
          class="mb-4"
          density="comfortable"
          label="From Station ID"
          :rules="[rules.required]"
          variant="outlined"
        />

        <v-text-field
          v-model="form.toStationId"
          class="mb-4"
          density="comfortable"
          label="To Station ID"
          :rules="[rules.required]"
          variant="outlined"
        />

        <v-text-field
          v-model="form.analyticCode"
          class="mb-4"
          density="comfortable"
          label="Analytic Code"
          :rules="[rules.required]"
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
          Calculate Route
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
    </v-card-text>
  </v-card>
</template>

<script setup lang="ts">
  import type { ApiError, Route, RouteRequest } from '@/types'
  import { reactive, ref } from 'vue'
  import { routeService } from '@/services/routeService'

  const emit = defineEmits<{
    'route-calculated': [route: Route]
  }>()

  const formRef = ref()
  const valid = ref(false)
  const loading = ref(false)
  const error = ref<string | null>(null)

  const form = reactive<RouteRequest>({
    fromStationId: '',
    toStationId: '',
    analyticCode: '',
  })

  const rules = {
    required: (v: string) => !!v || 'Field is required',
  }

  async function calculateRoute () {
    if (!valid.value) return

    loading.value = true
    error.value = null

    try {
      const route = await routeService.calculateRoute(form)
      emit('route-calculated', route)
    } catch (error_) {
      const apiError = error_ as ApiError
      error.value = apiError.message || 'Failed to calculate route'
    } finally {
      loading.value = false
    }
  }
</script>
