<template>
  <v-container>
    <v-row align="center" justify="center" style="min-height: 80vh;">
      <v-col cols="12" lg="4" md="6" sm="8">
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
    'auth-success': []
  }>()

  const showLogin = ref(true)

  function handleAuthSuccess () {
    emit('auth-success')
  }
</script>
