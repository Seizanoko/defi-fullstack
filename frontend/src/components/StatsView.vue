<script setup lang="ts">
import { ref } from 'vue'
import { statsService } from '@/services/statsService'
import StatsForm from '@/components/StatsForm.vue'
import StatsTable from '@/components/StatsTable.vue'
import type { AnalyticDistanceList } from '@/types'

const stats = ref<AnalyticDistanceList | null>(null)
const loading = ref(false)

async function loadStats(filters: any) {
  loading.value = true
  try {
    const result = await statsService.stats(
      filters.from,
      filters.to,
      filters.groupBy
    )
    stats.value = result
  } finally {
    loading.value = false
  }
}

</script>

<template>
  <v-container class="py-6">

    <StatsForm @submit="loadStats" />

    <v-progress-linear v-if="loading" indeterminate class="my-4" />

    <StatsTable  v-if="stats && stats.items.length" :stats="stats.items" class="mt-6" />

    <div v-else-if="!loading" class="text-center mt-4 text-medium-emphasis">
      No data loaded yet…
    </div>

  </v-container>
</template>
