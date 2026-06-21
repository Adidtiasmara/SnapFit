<template>
  <router-view v-slot="{ Component }">
    <Transition name="page" mode="out-in">
      <component :is="Component" />
    </Transition>
  </router-view>
  <NotificationContainer />
</template>

<script setup>
import NotificationContainer from '@/pages/landing/partials/NotificationContainer.vue';
</script>