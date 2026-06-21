<template>
  <header class="h-16 sm:h-[72px] bg-[#FFFCF7]/95 backdrop-blur border-b border-[#E8DCCB] sticky top-0 z-20 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
    <div class="flex items-center gap-3 sm:gap-4 min-w-0">
      <button @click="$emit('toggle-sidebar')" class="lg:hidden w-9 h-9 rounded-lg bg-[#F8F1E7] border border-[#E8DCCB] flex items-center justify-center text-[#7A5A42] flex-shrink-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
      </button>
      <div class="min-w-0">
        <h1 class="text-sm sm:text-[15px] font-bold text-espresso truncate">{{ pageTitle }}</h1>
        <p class="text-[10px] sm:text-[11px] text-[#8B6B55] font-medium -mt-0.5 truncate hidden sm:block">{{ pageDesc }}</p>
      </div>
    </div>

    <div class="flex items-center gap-2">
      <!-- Search -->
      <div class="hidden md:flex items-center bg-[#F8F1E7]/70 rounded-lg px-3.5 py-2 gap-2 border border-[#E8DCCB]">
        <svg class="w-4 h-4 text-[#8B6B55]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
        <input type="text" placeholder="Search anything..." class="bg-transparent outline-none text-[12px] font-medium text-[#5C4333] w-44 placeholder:text-[#8B6B55]" />
        <kbd class="text-[9px] font-bold text-[#8B6B55] bg-[#FFFCF7] border border-[#E8DCCB] px-1.5 py-0.5 rounded">⌘K</kbd>
      </div>
      <!-- Notification -->
      <button type="button" @click="router.push('/admin/roles')"
        class="w-9 h-9 rounded-lg bg-[#F8F1E7]/70 flex items-center justify-center text-[#8B6B55] hover:bg-[#F2E6D8] hover:text-[#5C4333] transition-colors relative border border-[#E8DCCB]">
        <span class="absolute top-2 right-2 w-2 h-2 bg-terracotta rounded-full"></span>
        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
      </button>
    </div>
  </header>
</template>

<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();

defineProps({
  pageTitle: String,
  pageDesc: String
});
defineEmits(['toggle-sidebar']);
</script>
