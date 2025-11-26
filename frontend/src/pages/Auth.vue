<template>
  <v-container>
    <v-row justify="center" align="center" style="min-height: 80vh;">
      <v-col cols="12" sm="8" md="6" lg="4">
        <Login
          v-if="showLogin"
          @login-success="handleAuthSuccess"
          @switch-to-register="showLogin = false"
        />
        <Register
          v-else
          @register-success="handleAuthSuccess"
          @switch-to-login="showLogin = true"
        />
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import Login from '@/components/Login.vue'
import Register from '@/components/Register.vue'

const emit = defineEmits<{
  authSuccess: []
}>()

const showLogin = ref(true)

const handleAuthSuccess = () => {
  emit('authSuccess')
}
</script>