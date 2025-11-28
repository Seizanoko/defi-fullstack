<script setup lang="ts">
import { ref, defineEmits } from 'vue'

const emit = defineEmits(['submit'])

const from = ref<string>()
const to = ref<string>()
const groupBy = ref<string>('month')

const groupOptions = [
  { title: 'None', value: 'none' },
  { title: 'By Day', value: 'day' },
  { title: 'By Month', value: 'month' },
  { title: 'By Year', value: 'year' }
]

function submitForm() {
  emit('submit', { from: from.value, to: to.value, groupBy: groupBy.value })
}
</script>

<template>
  <v-card class="pa-4">
    <v-row>
      <v-col cols="12" md="4">
        <v-text-field label="From (YYYY-MM-DD)" v-model="from" type="date"/>
      </v-col>

      <v-col cols="12" md="4">
        <v-text-field label="To (YYYY-MM-DD)" v-model="to" type="date"/>
      </v-col>

      <v-col cols="12" md="4">
        <v-select
          :items="groupOptions"
          v-model="groupBy"
          label="Group By"
        />
      </v-col>

      <v-col cols="12">
        <v-btn block color="primary" @click="submitForm">
          Load Stats
        </v-btn>
      </v-col>
    </v-row>
  </v-card>
</template>
