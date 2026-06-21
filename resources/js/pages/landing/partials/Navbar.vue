<template>
  <header
    class="fixed top-0 left-0 right-0 z-50 bg-[rgba(255,250,243,0.86)] backdrop-blur-[14px] border-b border-[rgba(232,220,203,0.7)] shadow-sm animate-slide-down">
    <!-- Top inner highlight for premium glass effect -->
    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white/80 to-transparent"></div>
    <nav class="max-w-[1480px] mx-auto px-4 sm:px-6 lg:px-8 py-1.5 flex items-center relative">
      <!-- LEFT SECTION: Logo + Menu -->
      <div class="flex items-center gap-12">
        <!-- Logo Section -->
        <a href="/" class="flex items-center gap-2 group cursor-pointer">
          <div
            class="w-6 h-6 rounded-md overflow-hidden transform group-hover:scale-105 transition-all duration-300 flex items-center justify-center bg-surface">
            <img :src="'/images/logo_snapfit.png'" alt="SnapFit Logo" class="w-full h-full object-cover" />
          </div>
          <div>
            <h1
              class="font-black text-sm tracking-tight text-espresso leading-none group-hover:text-terracotta transition-colors">
              SnapFit</h1>
          </div>
        </a>

        <!-- Main Navigation Menu -->
        <!-- <div class="hidden lg:flex items-center gap-8">

        <a href="#ai-studio" class="relative px-3 py-2 text-[13px] font-semibold text-muted hover:text-terracotta transition-colors">
          AI Studio
        </a>
        <a href="#ar-tryon" class="relative px-3 py-2 text-[13px] font-semibold text-muted hover:text-terracotta transition-colors">
          Coba AR
        </a>
        <a href="#co-create" class="relative px-3 py-2 text-[13px] font-semibold text-muted hover:text-terracotta transition-colors">
          Co Create
        </a>
        <a href="#buka-toko" class="px-4 py-1.5 text-[12px] font-bold text-terracotta bg-cream border border-terracotta/30 rounded-full hover:bg-terracotta hover:text-white transition-all shadow-sm">
          Buka Toko
        </a>
      </div> -->
      </div>

      <!-- RIGHT SECTION: Search + Icons -->
      <div class="ml-auto flex items-center gap-6">
        <!-- Search Input -->
        <div class="hidden md:flex relative items-center search-container z-50">
          <input v-model="searchQuery" @input="debouncedSearch" @focus="showSearchResults = searchQuery.length > 0"
            type="text" placeholder="Cari produk khas Indonesia..."
            class="w-[320px] bg-surface/50 border border-borderSoft/80 text-espresso text-xs rounded-2xl pl-11 pr-4 py-2 outline-none focus:border-terracotta focus:bg-surface focus:ring-4 focus:ring-terracotta/20 transition-all placeholder-slate-400 font-medium shadow-[inset_0_2px_4px_rgba(0,0,0,0.02)]" />
          <svg class="w-4 h-4 text-muted absolute left-3.5 pointer-events-none" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>

          <!-- Search Results Dropdown -->
          <Transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 translate-y-1"
            enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
            <div v-if="showSearchResults"
              class="absolute top-full mt-2 w-full bg-surface rounded-2xl shadow-xl border border-borderSoft overflow-hidden z-[60]">
              <div v-if="isSearching" class="p-4 text-center text-xs text-muted font-bold">
                <svg class="animate-spin h-5 w-5 mx-auto text-terracotta mb-2" xmlns="http://www.w3.org/2000/svg"
                  fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                  </path>
                </svg>
                Mencari produk...
              </div>
              <div v-else-if="searchResults.length === 0" class="p-4 text-center text-xs text-muted font-bold">
                Produk tidak ditemukan
              </div>
              <div v-else class="max-h-[60vh] overflow-y-auto hide-scrollbar">
                <button v-for="item in searchResults" :key="item.id" type="button"
                  @click="goToProduct(item)"
                  class="w-full text-left flex items-center gap-3 p-3 hover:bg-sand transition-colors border-b border-gray-50 last:border-0">
                  <img :src="item.images?.[0] ?? item.image_url ?? '/images/placeholder.png'"
                    class="w-10 h-10 rounded-lg object-cover bg-gray-100 flex-shrink-0" />
                  <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-espresso truncate">{{ item.name }}</p>
                    <p class="text-[10px] text-terracotta font-bold mt-0.5">Rp {{ Number(item.price ??
                      0).toLocaleString('id-ID') }}</p>
                  </div>
                </button>
              </div>
            </div>
          </Transition>
        </div>

        <!-- Icons Container -->
        <div class="flex items-center gap-4">

          <!-- Notification Bell (Always Visible) -->
        <div class="relative notification-menu">
          <button @click="toggleNotifications"
            class="relative p-1.5 rounded-lg hover:bg-gray-100 transition-colors group">
            <svg class="w-4 h-4 text-muted group-hover:text-terracotta transition-colors" fill="none"
              stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span v-if="unreadCount > 0"
              class="absolute top-0.5 right-0.5 w-1.5 h-1.5 bg-red-500 rounded-full border border-white"></span>
          </button>

          <!-- Notification Dropdown -->
          <!-- Notification Dropdown -->
          <Transition enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95 -translate-y-2" enter-to-class="opacity-100 scale-100 translate-y-0"
            leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 scale-100 translate-y-0"
            leave-to-class="opacity-0 scale-95 -translate-y-2">
            <div v-if="showNotifications"
              class="absolute right-[-8px] top-[calc(100%+14px)] w-[320px] sm:w-[380px] bg-[#FFFCF7] rounded-[24px] shadow-[0_10px_40px_rgba(0,0,0,0.08)] border border-[#E8DCCB] z-50">
              
              <!-- Arrow -->
              <div class="absolute -top-[7px] right-[16px] w-3.5 h-3.5 bg-[#FFFCF7] border-t border-l border-[#E8DCCB] transform rotate-45 rounded-tl-[2px] z-0"></div>

              <div class="relative z-10 flex flex-col max-h-[420px] overflow-hidden rounded-[24px]">
                <!-- Header (Sticky) -->
                <div class="sticky top-0 bg-[#FFFCF7] px-5 py-4 border-b border-[#E8DCCB] flex justify-between items-center z-20">
                  <h3 class="font-black text-[#2B1E16] text-sm">Notifikasi</h3>
                  <button @click="markAllAsRead"
                    class="text-[11px] font-bold text-terracotta hover:text-[#B85C38] transition-colors">Tandai semua
                    dibaca</button>
                </div>
                
                <div class="overflow-y-auto hide-scrollbar flex-1 bg-[#FFFCF7]">
                  <div v-if="notifications.length === 0" class="p-8 text-center text-sm text-[#6F6259] font-medium">
                    Belum ada notifikasi
                  </div>
                  <div v-else>
                    <div v-for="notif in notifications" :key="notif.id"
                      class="p-5 border-b border-[#E8DCCB]/50 hover:bg-[#F8F1E7] transition-colors cursor-pointer flex gap-3 relative"
                      :class="!notif.is_read ? '' : 'opacity-80'"
                      @click="markAsRead(notif)">
                      <!-- Dot unread -->
                      <div class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0 transition-colors duration-300"
                        :class="!notif.is_read ? 'bg-[#B85C38]' : 'bg-transparent'"></div>
                      <div>
                        <h4 class="text-sm text-[#2B1E16]" :class="!notif.is_read ? 'font-black' : 'font-medium'">{{ notif.title }}</h4>
                        <p class="text-xs mt-1 leading-relaxed" :class="!notif.is_read ? 'text-[#6F6259]' : 'text-[#6F6259]/80'">{{ notif.message }}</p>
                        <p class="text-[10px] mt-2 font-bold" :class="!notif.is_read ? 'text-[#6F6259]/80' : 'text-[#6F6259]/50'">{{ notif.created_at }}</p>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Footer (Sticky) -->
                <div class="sticky bottom-0 p-4 border-t border-[#E8DCCB] text-center bg-[#FFFCF7] hover:bg-[#F8F1E7] transition-colors cursor-pointer z-20">
                  <button type="button" @click="goToNotifications"
                    class="text-xs font-black text-[#2B1E16] hover:text-[#B85C38] transition-colors">Lihat Semua
                    Notifikasi</button>
                </div>
              </div>
            </div>
          </Transition>
        </div>

        <!-- Cart Button (Always Visible) -->
        <div class="relative cart-menu">
          <button @click="toggleCart" class="relative p-1.5 rounded-lg hover:bg-sand transition-colors group">
            <svg class="w-4 h-4 text-muted group-hover:text-terracotta transition-colors" fill="none"
              stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <!-- Badge always shows if count > 0 -->
            <span v-if="cartCount > 0"
              class="absolute -top-1 -right-1 w-[18px] h-[18px] bg-terracotta text-white text-[9px] font-black rounded-full flex items-center justify-center shadow-sm shadow-terracotta/50/40">
              {{ cartCount > 99 ? '99+' : cartCount }}
            </span>
          </button>

          <!-- Cart Dropdown -->
          <Transition enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95 -translate-y-2" enter-to-class="opacity-100 scale-100 translate-y-0"
            leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 scale-100 translate-y-0"
            leave-to-class="opacity-0 scale-95 -translate-y-2">
            <div v-if="showCart"
              class="absolute right-[-8px] top-[calc(100%+14px)] w-[320px] sm:w-[380px] bg-[#FFFCF7] rounded-[24px] shadow-[0_10px_40px_rgba(0,0,0,0.08)] border border-[#E8DCCB] z-50">
              
              <!-- Arrow -->
              <div class="absolute -top-[7px] right-[16px] w-3.5 h-3.5 bg-[#FFFCF7] border-t border-l border-[#E8DCCB] transform rotate-45 rounded-tl-[2px] z-0"></div>

              <div class="relative z-10 flex flex-col max-h-[420px] overflow-hidden rounded-[24px]">
                <div class="sticky top-0 bg-[#FFFCF7] px-5 py-4 border-b border-[#E8DCCB] flex justify-between items-center z-20">
                  <h3 class="font-black text-[#2B1E16] text-sm">Keranjang Belanja <span
                      class="text-[#6F6259] font-medium">({{ cartCount }})</span></h3>
                </div>
                <div class="overflow-y-auto hide-scrollbar flex-1 bg-[#FFFCF7]">
                  <div v-if="cartStore.items.length === 0" class="p-8 text-center flex flex-col items-center">
                    <div class="w-16 h-16 bg-[#F8F1E7] rounded-full flex items-center justify-center mb-3">
                      <svg class="w-8 h-8 text-[#B85C38]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                      </svg>
                    </div>
                    <p class="text-sm text-[#2B1E16] font-black mb-1">Keranjang masih kosong</p>
                    <p class="text-xs text-[#6F6259] font-medium">Yuk cari produk impianmu!</p>
                  </div>
                  <div v-else>
                    <div v-for="item in cartStore.items.slice(0, 4)" :key="`${item.id}-${item.variant}`"
                      class="p-4 border-b border-[#E8DCCB]/50 flex gap-3 relative hover:bg-[#F8F1E7] transition-colors group">
                      <img :src="item.image || '/images/placeholder.png'"
                        class="w-14 h-14 rounded-xl object-cover bg-white border border-[#E8DCCB]/30 flex-shrink-0" />
                      <div class="flex-1 min-w-0 flex flex-col justify-center">
                        <h4 class="text-sm font-bold text-[#2B1E16] truncate pr-6">{{ item.name }}</h4>
                        <p class="text-xs font-black text-[#B85C38] mt-0.5">{{ cartStore.formatCurrency(item.price) }}
                        </p>
                        <div class="flex items-center gap-1.5 mt-1.5">
                          <button @click.stop="decrementItem(item)"
                            class="w-6 h-6 rounded-lg bg-[#FFFCF7] border border-[#E8DCCB] hover:bg-[#F8F1E7] text-[#6F6259] hover:text-[#B85C38] flex items-center justify-center text-xs font-black transition-colors">−</button>
                          <span class="w-7 text-center text-xs font-black text-[#2B1E16]">{{ item.quantity }}</span>
                          <button @click.stop="incrementItem(item)"
                            class="w-6 h-6 rounded-lg bg-[#FFFCF7] border border-[#E8DCCB] hover:bg-[#F8F1E7] text-[#6F6259] hover:text-[#B85C38] flex items-center justify-center text-xs font-black transition-colors">+</button>
                        </div>
                      </div>
                      <!-- Tombol hapus -->
                      <button @click.stop="removeCartItem(item)"
                        class="absolute top-3 right-3 w-6 h-6 rounded-lg bg-transparent hover:bg-red-50 text-gray-300 hover:text-red-500 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all"
                        title="Hapus dari keranjang">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                        </svg>
                      </button>
                    </div>
                    <div v-if="cartStore.items.length > 4"
                      class="p-3 text-center text-xs font-bold text-[#6F6259] bg-[#F8F1E7]">
                      + {{ cartStore.items.length - 4 }} produk lainnya
                    </div>
                  </div>
                </div>
                <div v-if="cartStore.items.length > 0" class="sticky bottom-0 p-4 border-t border-[#E8DCCB] bg-[#FFFCF7] z-20">
                  <div class="flex justify-between items-center mb-3">
                    <p class="text-xs font-semibold text-[#6F6259]">Total Harga</p>
                    <p class="text-sm font-black text-[#B85C38]">{{ cartStore.formatCurrency(cartStore.totalPrice) }}</p>
                  </div>
                  <button @click="goToCart"
                    class="w-full bg-[#B85C38] text-white py-2.5 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-[#9a4b2c] hover:shadow-lg hover:shadow-[#B85C38]/20 transition-all active:scale-[0.98]">
                    Lihat Keranjang
                  </button>
                </div>
              </div>
            </div>
          </Transition>
        </div>

        <template v-if="isLoggedIn">
          <div class="relative user-menu">
            <!-- Avatar Button -->
            <button @click="toggleUserMenu"
              class="relative w-8 h-8 rounded-full bg-surface overflow-hidden flex items-center justify-center shadow-sm hover:shadow-md hover:shadow-terracotta/20 hover:scale-105 transition-all duration-300 ring-2 ring-white border border-borderSoft">
              <img
                :src="user.profile?.avatar_url || user.avatar || `https://api.dicebear.com/7.x/avataaars/svg?seed=${user.name}&scale=130&backgroundColor=ffdfbf,c0aede,d1d4f9`"
                alt="Avatar" class="w-full h-full object-cover" />
            </button>

            <!-- Dropdown Menu -->
            <Transition enter-active-class="transition ease-out duration-200"
              enter-from-class="opacity-0 scale-95 -translate-y-2" enter-to-class="opacity-100 scale-100 translate-y-0"
              leave-active-class="transition ease-in duration-150"
              leave-from-class="opacity-100 scale-100 translate-y-0" leave-to-class="opacity-0 scale-95 -translate-y-2">
              <div v-if="showUserMenu"
                class="absolute right-0 top-full mt-3 w-64 bg-surface rounded-3xl shadow-2xl border border-borderSoft overflow-hidden z-50">
                <!-- User Header -->
                <div class="bg-sand px-6 py-5 border-b border-borderSoft">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-14 h-14 rounded-2xl overflow-hidden bg-surface flex items-center justify-center shadow-sm border border-borderSoft">
                      <img
                        :src="user.profile?.avatar_url || user.avatar || `https://api.dicebear.com/7.x/avataaars/svg?seed=${user.name}&scale=130&backgroundColor=ffdfbf,c0aede,d1d4f9`"
                        alt="Avatar" class="w-full h-full object-cover" />
                    </div>
                    <div>
                      <p class="text-sm font-black text-espresso">{{ user.name }}</p>
                      <!-- <p class="text-[10px] text-terracotta font-bold uppercase tracking-wider">Premium Member</p> -->
                    </div>
                  </div>
                </div>

                <!-- Menu Items -->
                <div class="py-2">
                  <!-- Basic User Menus -->
                  <button @click="goToProfile"
                    class="w-full flex items-center gap-3 px-6 py-2.5 text-sm font-bold text-espresso hover:bg-sand hover:text-terracotta transition-all group">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-terracotta" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>Profil</span>
                  </button>

                  <button @click="goToOrders"
                    class="w-full flex items-center gap-3 px-6 py-2.5 text-sm font-bold text-espresso hover:bg-sand hover:text-terracotta transition-all group">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-terracotta" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span>Pesanan</span>
                  </button>
                  
                  <!-- UMKM Specific Menus -->
                  <template v-if="user.active_role === 'umkm'">
                    <div class="h-px bg-slate-100 my-2 mx-6"></div>
                    <button @click="router.push('/umkm/dashboard'); showUserMenu = false" class="w-full flex items-center gap-3 px-6 py-2.5 text-sm font-bold text-espresso hover:bg-sand hover:text-terracotta transition-all group">
                      <svg class="w-4 h-4 text-slate-400 group-hover:text-terracotta" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                      <span>Dashboard Toko</span>
                    </button>
                    <button @click="router.push('/umkm/studio'); showUserMenu = false" class="w-full flex items-center gap-3 px-6 py-2.5 text-sm font-bold text-espresso hover:bg-sand hover:text-terracotta transition-all group">
                      <svg class="w-4 h-4 text-slate-400 group-hover:text-terracotta" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                      <span>AI Product Studio</span>
                    </button>
                    <button @click="router.push('/umkm/products'); showUserMenu = false" class="w-full flex items-center gap-3 px-6 py-2.5 text-sm font-bold text-espresso hover:bg-sand hover:text-terracotta transition-all group">
                      <svg class="w-4 h-4 text-slate-400 group-hover:text-terracotta" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                      <span>Produk Saya</span>
                    </button>
                  </template>

                  <!-- Designer Specific Menus -->
                  <template v-else-if="user.active_role === 'designer'">
                    <div class="h-px bg-slate-100 my-2 mx-6"></div>
                    <button @click="router.push('/designer/dashboard'); showUserMenu = false" class="w-full flex items-center gap-3 px-6 py-2.5 text-sm font-bold text-espresso hover:bg-sand hover:text-terracotta transition-all group">
                      <svg class="w-4 h-4 text-slate-400 group-hover:text-terracotta" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                      <span>Dashboard Designer</span>
                    </button>
                    <button @click="router.push('/designer/cocreate'); showUserMenu = false" class="w-full flex items-center gap-3 px-6 py-2.5 text-sm font-bold text-espresso hover:bg-sand hover:text-terracotta transition-all group">
                      <svg class="w-4 h-4 text-slate-400 group-hover:text-terracotta" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                      <span>Co-Create Room</span>
                    </button>
                    <button @click="router.push('/designer/portfolio'); showUserMenu = false" class="w-full flex items-center gap-3 px-6 py-2.5 text-sm font-bold text-espresso hover:bg-sand hover:text-terracotta transition-all group">
                      <svg class="w-4 h-4 text-slate-400 group-hover:text-terracotta" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                      <span>Portfolio</span>
                    </button>
                  </template>

                  <!-- Buka Toko (If general user/customer) -->
                  <template v-else>
                    <div class="h-px bg-slate-100 my-2 mx-6"></div>
                    <button @click="router.push('/register/umkm'); showUserMenu = false" class="w-full flex items-center gap-3 px-6 py-2.5 text-sm font-bold text-espresso hover:bg-sand hover:text-terracotta transition-all group">
                      <svg class="w-4 h-4 text-slate-400 group-hover:text-terracotta" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                      <span>Buka Toko UMKM</span>
                    </button>
                    <button @click="router.push('/register/designer'); showUserMenu = false" class="w-full flex items-center gap-3 px-6 py-2.5 text-sm font-bold text-espresso hover:bg-sand hover:text-terracotta transition-all group">
                      <svg class="w-4 h-4 text-slate-400 group-hover:text-terracotta" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                      <span>Gabung Designer</span>
                    </button>
                  </template>
                </div>

                <!-- Logout -->
                <div class="border-t border-borderSoft p-2">
                  <button @click="logout"
                    class="w-full flex items-center gap-3 px-6 py-3 text-sm font-black text-red-600 hover:bg-red-50 rounded-2xl transition-all group">
                    <div
                      class="w-8 h-8 rounded-xl bg-red-50 group-hover:bg-red-100 flex items-center justify-center transition-colors">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                      </svg>
                    </div>
                    <span>Keluar</span>
                  </button>
                </div>
              </div>
            </Transition>
          </div>
        </template>

        <template v-else>
          <!-- Desktop Sign In Button -->
          <button @click="goToLogin"
            class="hidden md:inline-flex group relative bg-espresso text-white px-6 py-2 rounded-full text-xs font-bold uppercase tracking-widest hover:bg-terracotta transition-all duration-300 shadow-md hover:shadow-lg hover:shadow-terracotta/30 hover:-translate-y-0.5 items-center justify-center">
            <span class="relative z-10">Masuk</span>
          </button>

          <!-- Mobile Sign In Icon Button -->
          <button @click="goToLogin"
            class="md:hidden p-1.5 rounded-lg hover:bg-sand transition-colors group flex items-center justify-center"
            title="Masuk">
            <svg class="w-4 h-4 text-muted group-hover:text-terracotta transition-colors" fill="none"
              stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </button>
        </template>
        </div>
      </div>
    </nav>

  </header>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount, watch } from 'vue';
import { useRouter } from 'vue-router';
import { useCartStore } from '@/stores/cartStore';

const router = useRouter();
const cartStore = useCartStore();

const props = defineProps({
  user: {
    type: Object,
    default: null
  }
});

const emit = defineEmits(['logout', 'goToLogin', 'goToProfile', 'goToOrders']);

const showUserMenu = ref(false);

const showNotifications = ref(false);
const showCart = ref(false);
const isLoggedIn = computed(() => !!props.user);
const cartCount = computed(() => cartStore.itemCount);

const toggleUserMenu = () => {
  showUserMenu.value = !showUserMenu.value;
  if (showUserMenu.value) { showNotifications.value = false; showCart.value = false; }
};

const toggleNotifications = () => {
  showNotifications.value = !showNotifications.value;
  if (showNotifications.value) { showUserMenu.value = false; showCart.value = false; }
};

const toggleCart = () => {
  showCart.value = !showCart.value;
  if (showCart.value) { showUserMenu.value = false; showNotifications.value = false; }
};



// Notifications Data
const notifications = ref([]);
const unreadCount = ref(0);

const fetchNotifications = async () => {
  if (!isLoggedIn.value) return;
  try {
    const token = localStorage.getItem('token');
    const res = await fetch('/api/v1/notifications', {
      headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
    });
    if (res.ok) {
      const data = await res.json();
      notifications.value = data.data;
      unreadCount.value = data.unread_count;
    }
  } catch (err) {
    console.error('Failed to fetch notifications', err);
  }
};

watch(isLoggedIn, (newVal) => {
  if (newVal) {
    fetchNotifications();
  }
}, { immediate: true });

const markAsRead = async (notif) => {
  if (notif.is_read) return;
  try {
    const token = localStorage.getItem('token');
    await fetch(`/api/v1/notifications/${notif.id}/read`, {
      method: 'PATCH',
      headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
    });
    notif.is_read = true;
    unreadCount.value = Math.max(0, unreadCount.value - 1);
  } catch (err) {
    console.error('Failed to mark as read', err);
  }
};

const markAllAsRead = async () => {
  try {
    const token = localStorage.getItem('token');
    await fetch('/api/v1/notifications/read-all', {
      method: 'PATCH',
      headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
    });
    notifications.value.forEach(n => n.is_read = true);
    unreadCount.value = 0;
  } catch (err) {
    console.error('Failed to mark all as read', err);
  }
};

// Search Logic
const searchQuery = ref('');
const searchResults = ref([]);
const isSearching = ref(false);
const showSearchResults = ref(false);
let searchTimeout = null;

const debouncedSearch = () => {
  if (searchTimeout) clearTimeout(searchTimeout);

  if (searchQuery.value.trim().length === 0) {
    searchResults.value = [];
    showSearchResults.value = false;
    return;
  }

  showSearchResults.value = true;
  isSearching.value = true;

  searchTimeout = setTimeout(async () => {
    try {
      const res = await fetch('/api/v1/products', { headers: { 'Accept': 'application/json' } });
      const data = await res.json();
      const products = Array.isArray(data) ? data : data?.data ?? [];

      const q = searchQuery.value.toLowerCase();
      searchResults.value = products.filter(p =>
        (p.name && p.name.toLowerCase().includes(q)) ||
        (p.category && p.category.toLowerCase().includes(q))
      ).slice(0, 5); // Ambil 5 teratas
    } catch (err) {
      console.error('Search error:', err);
    } finally {
      isSearching.value = false;
    }
  }, 300);
};

const goToProduct = (item) => {
  showSearchResults.value = false;
  searchQuery.value = '';
  const slug = item.name.toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/(^-|-$)/g, '');
  router.push(`/${slug}-i.${item.id}`);
};

// Cart item manipulation
const incrementItem = (item) => {
  cartStore.updateQuantity(item.id, item.variant, item.quantity + 1);
};

const decrementItem = (item) => {
  if (item.quantity <= 1) {
    cartStore.removeItem(item.id, item.variant);
  } else {
    cartStore.updateQuantity(item.id, item.variant, item.quantity - 1);
  }
};

const removeCartItem = (item) => {
  cartStore.removeItem(item.id, item.variant);
};

const goToLogin = () => emit('goToLogin');
const goToProfile = () => { showUserMenu.value = false; router.push('/profile'); };
const goToOrders = () => { showUserMenu.value = false; router.push('/profile?tab=orders'); };
const goToCart = () => { showCart.value = false; router.push('/cart'); };
const goToNotifications = () => {
  showNotifications.value = false;
  router.push('/notifications');
};
const logout = async () => {
  try {
    const token = localStorage.getItem('token');
    if (token) {
      await fetch('/api/v1/auth/logout', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json'
        }
      });
    }
  } catch (err) {
    console.error('Logout error:', err);
  } finally {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    emit('logout');
    window.location.href = '/';
  }
};

// Close dropdown when clicking outside
const handleClickOutside = (e) => {
  if (!e.target.closest('.user-menu')) showUserMenu.value = false;
  if (!e.target.closest('.notification-menu')) showNotifications.value = false;
  if (!e.target.closest('.cart-menu')) showCart.value = false;
  if (!e.target.closest('.search-container')) showSearchResults.value = false;
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
  fetchNotifications();
});
onBeforeUnmount(() => document.removeEventListener('click', handleClickOutside));
</script>

<style scoped>
.nav-link::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  width: 0;
  height: 2px;
  background: #ea580c;
  transform: translateX(-50%);
  transition: width 0.3s ease;
}

.nav-link:hover::after {
  width: 60%;
}

@keyframes slideDown {
  from {
    transform: translateY(-100%);
  }

  to {
    transform: translateY(0);
  }
}

.animate-slide-down {
  animation: slideDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.hide-scrollbar::-webkit-scrollbar {
  display: none;
}

.hide-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
