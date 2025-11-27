<template>
  <v-app>
    <v-app-bar v-if="isAuthenticated" color="primary" prominent>
      <v-app-bar-title>
        <v-icon class="mr-2" icon="mdi-train" />
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
  import type { Route } from '@/types'
  import { onMounted, ref } from 'vue'
  import RouteCalculator from '@/components/RouteCalculator.vue'
  import RouteResult from '@/components/RouteResult.vue'
  import Auth from '@/pages/Auth.vue'
  import { authService } from '@/services/authService'

  const isAuthenticated = ref(false)
  const calculatedRoute = ref<Route | null>(null)

  function handleRouteCalculated (route: Route) {
    calculatedRoute.value = route
  }

  function handleAuthSuccess () {
    isAuthenticated.value = true
  }

  function handleLogout () {
    authService.logout()
    isAuthenticated.value = false
    calculatedRoute.value = null
  }

  onMounted(() => {
    isAuthenticated.value = authService.isAuthenticated()
  })
</script>
