<template>
  <v-app>
    <v-app-bar v-if="isAuthenticated" color="primary" prominent>
      <v-app-bar-title>
        <v-icon icon="mdi-train" class="mr-2" />
        MOB Train Routing
      </v-app-bar-title>
      <v-spacer />
      <v-btn icon @click="handleLogout">
        <v-icon>mdi-logout</v-icon>
      </v-btn>
    </v-app-bar>

    <v-main>
      <Auth v-if="!isAuthenticated" @auth-success="handleAuthSuccess" />
      
      <v-container v-else fluid>
        <v-row>
          <v-col cols="12">
            <h1 class="text-h3 mb-6">Calculate Train Route</h1>
          </v-col>
        </v-row>

        <v-row>
          <v-col cols="12" md="6">
            <RouteCalculator @route-calculated="handleRouteCalculated" />
          </v-col>

          <v-col cols="12" md="6">
            <RouteResult v-if="calculatedRoute" :route="calculatedRoute" />
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import Auth from '@/pages/Auth.vue'
import RouteCalculator from '@/components/RouteCalculator.vue'
import RouteResult from '@/components/RouteResult.vue'
import { authService } from '@/services/authService'
import type { Route } from '@/types'

const isAuthenticated = ref(false)
const calculatedRoute = ref<Route | null>(null)

const handleRouteCalculated = (route: Route) => {
  calculatedRoute.value = route
}

const handleAuthSuccess = () => {
  isAuthenticated.value = true
}

const handleLogout = () => {
  authService.logout()
  isAuthenticated.value = false
  calculatedRoute.value = null
}

onMounted(() => {
  isAuthenticated.value = authService.isAuthenticated()
})
</script>