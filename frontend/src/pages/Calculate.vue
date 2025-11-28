<template>
  <v-container fluid>
    <h1 class="text-h3 mb-6">Calculate Train Route</h1>

    <v-row>
      <v-col cols="12" md="6">
        <RouteCalculator @route-calculated="handleRouteCalculated" />
      </v-col>

      <v-col cols="12" md="6">
        <RouteResult v-if="calculatedRoute" :route="calculatedRoute" />
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import type { Route } from '@/types'
import RouteCalculator from '@/components/RouteCalculator.vue'
import RouteResult from '@/components/RouteResult.vue'

const calculatedRoute = ref<Route | null>(null)

function handleRouteCalculated(route: Route) {
  calculatedRoute.value = route
}
</script>
