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
      <!-- LOGIN -->
      <Auth v-if="!isAuthenticated" @auth-success="handleAuthSuccess" />

      <!-- MAIN APP -->
      <template v-else>
        <!-- TABS -->
        <v-tabs
          v-model="activeTab"
          background-color="primary"
          grow
          dark
        >
          <v-tab to="/calculate" value="/calculate">Calculate Route</v-tab>
          <v-tab to="/stats" value="/stats">Stats</v-tab>
        </v-tabs>

        <router-view />
      </template>
    </v-main>
  </v-app>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import Auth from '@/pages/Auth.vue'
import { authService } from '@/services/authService'

const isAuthenticated = ref(false)
const route = useRoute()

// keep tabs synced with current route
const activeTab = ref(route.path)

watch(
  () => route.path,
  (p) => {
    activeTab.value = p
  }
)

function handleAuthSuccess() {
  isAuthenticated.value = true
}

function handleLogout() {
  authService.logout()
  isAuthenticated.value = false
}
</script>
