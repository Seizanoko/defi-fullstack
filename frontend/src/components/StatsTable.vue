<script setup lang="ts">
import type { AnalyticDistance } from '@/types'

defineProps<{
  stats: AnalyticDistance[]
}>()
</script>

<template>
  <v-data-table
    :items="stats"
    :headers="[
      { title: 'Analytic Code', key: 'analyticCode' },
      { title: 'Group', key: 'group' },
      { title: 'Distance (Km)', key: 'totalDistanceKm' }
    ]"
  />
</template>
