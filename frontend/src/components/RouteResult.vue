<template>
  <v-card>
    <v-card-title>Route Result</v-card-title>
    <v-card-text>
      <v-list>
        <v-list-item>
          <v-list-item-title>Route ID</v-list-item-title>
          <v-list-item-subtitle>{{ route.id }}</v-list-item-subtitle>
        </v-list-item>

        <v-list-item>
          <v-list-item-title>From Station</v-list-item-title>
          <v-list-item-subtitle>{{ route.fromStationId }}</v-list-item-subtitle>
        </v-list-item>

        <v-list-item>
          <v-list-item-title>To Station</v-list-item-title>
          <v-list-item-subtitle>{{ route.toStationId }}</v-list-item-subtitle>
        </v-list-item>

        <v-list-item>
          <v-list-item-title>Analytic Code</v-list-item-title>
          <v-list-item-subtitle>{{ route.analyticCode }}</v-list-item-subtitle>
        </v-list-item>

        <v-list-item>
          <v-list-item-title>Distance</v-list-item-title>
          <v-list-item-subtitle class="text-h6 text-primary">
            {{ route.distanceKm }} km
          </v-list-item-subtitle>
        </v-list-item>

        <v-list-item>
          <v-list-item-title>Path</v-list-item-title>
          <v-list-item-subtitle>
            <v-chip-group>
              <v-chip v-for="(station, index) in route.path" :key="index" size="small">
                {{ station }}
              </v-chip>
            </v-chip-group>
          </v-list-item-subtitle>
        </v-list-item>

        <v-list-item>
          <v-list-item-title>Created At</v-list-item-title>
          <v-list-item-subtitle>{{ formatDate(route.createdAt) }}</v-list-item-subtitle>
        </v-list-item>
      </v-list>
    </v-card-text>
  </v-card>
</template>

<script setup lang="ts">
  import type { Route } from '@/types'

  defineProps<{
    route: Route
  }>()

  function formatDate (dateString: string): string {
    return new Date(dateString).toLocaleString()
  }
</script>
