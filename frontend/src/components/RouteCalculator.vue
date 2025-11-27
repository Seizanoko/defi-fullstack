<template>
  <v-card>
    <v-card-title>Route Calculator</v-card-title>
    <v-card-text>
      <v-form ref="formRef" v-model="valid" @submit.prevent="calculateRoute">
        <v-text-field
          v-model="form.fromStationId"
          label="From Station ID"
          :rules="[rules.required]"
          variant="outlined"
          density="comfortable"
          class="mb-4"
        />

        <v-text-field
          v-model="form.toStationId"
          label="To Station ID"
          :rules="[rules.required]"
          variant="outlined"
          density="comfortable"
          class="mb-4"
        />

        <v-text-field
          v-model="form.analyticCode"
          label="Analytic Code"
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
          Calculate Route
        </v-btn>
      </v-form>

      <v-alert v-if="error" type="error" class="mt-4" closable @click:close="error = null">
        {{ error }}
      </v-alert>
    </v-card-text>
  </v-card>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import { routeService } from '@/services/routeService'
import type { RouteRequest, Route, ApiError } from '@/types'

const emit = defineEmits<{
  routeCalculated: [route: Route]
}>()

const formRef = ref()
const valid = ref(false)
const loading = ref(false)
const error = ref<string | null>(null)

const form = reactive<RouteRequest>({
  fromStationId: '',
  toStationId: '',
  analyticCode: ''
})

const rules = {
  required: (v: string) => !!v || 'Field is required'
}

const calculateRoute = async () => {
  if (!valid.value) return

  loading.value = true
  error.value = null

  try {
    const route = await routeService.calculateRoute(form)
    emit('routeCalculated', route)
  } catch (err) {
    const apiError = err as ApiError
    error.value = apiError.message || 'Failed to calculate route'
  } finally {
    loading.value = false
  }
}
</script>