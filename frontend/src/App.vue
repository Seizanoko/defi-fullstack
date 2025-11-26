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
        <!-- Display default Vuetify page -->
        <router-view /> 
      </v-container>
    </v-main>
  </v-app>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import Auth from '@/pages/Auth.vue'
import { authService } from '@/services/authService'

const isAuthenticated = ref(false)

const handleAuthSuccess = () => {
  isAuthenticated.value = true
}

const handleLogout = () => {
  authService.logout()
  isAuthenticated.value = false
}

onMounted(() => {
  isAuthenticated.value = authService.isAuthenticated()
})
</script>