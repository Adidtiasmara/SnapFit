<template>
  <header class="h-[64px] bg-[#FFFCF7] border-b border-[#E8DCCB]/60 sticky top-0 z-20 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
    <div class="flex items-center gap-3 min-w-0">
      <!-- Mobile hamburger -->
      <button
        @click="$emit('toggle-sidebar')"
        class="lg:hidden w-9 h-9 rounded-xl bg-[#F8F1E7] flex items-center justify-center text-[#8A7A6C] flex-shrink-0 hover:bg-[#E8DCCB] transition-colors"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>

      <!-- Page title -->
      <div class="min-w-0">
        <h1 class="text-[15px] font-bold text-[#2B1E16] truncate">{{ pageTitle }}</h1>
        <p class="text-[11px] text-[#8A7A6C] font-medium truncate hidden sm:block">{{ pageDesc }}</p>
      </div>
    </div>

    <div class="flex items-center gap-2.5">
      <!-- Search -->
      <div class="hidden md:flex items-center bg-white rounded-xl px-3.5 py-2 gap-2 border border-[#E8DCCB]">
        <svg class="w-3.5 h-3.5 text-[#8A7A6C]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <input type="text" placeholder="Cari sesuatu..." class="bg-transparent outline-none text-[12px] font-medium text-[#2B1E16] w-36 placeholder:text-[#8A7A6C]/60" />
      </div>

      <!-- Notification -->
      <button type="button" @click="router.push('/umkm/settings')"
        class="w-9 h-9 rounded-xl bg-white flex items-center justify-center text-[#8A7A6C] hover:bg-[#F8F1E7] hover:text-[#B85C38] transition-colors relative border border-[#E8DCCB] flex-shrink-0">
        <span class="absolute top-2 right-2 w-1.5 h-1.5 bg-[#B85C38] rounded-full"></span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
      </button>
    </div>
  </header>
</template>

<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();

defineProps({
  pageTitle: { type: String, default: 'Creator Hub' },
  pageDesc: { type: String, default: '' },
});
defineEmits(['toggle-sidebar']);
</script>
