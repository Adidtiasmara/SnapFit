<template>
  <div class="snapfit-heritage-bg min-h-screen font-sans pb-24">
    <Navbar :user="user" />

    <div class="max-w-[1420px] mx-auto px-4 sm:px-6 lg:px-8 pt-16">
      <!-- Back Button -->
      <button @click="router.back()" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-espresso bg-white/60 hover:bg-white border border-borderSoft/60 hover:border-terracotta/40 rounded-full shadow-[0_2px_8px_rgba(43,30,22,0.04)] backdrop-blur-md transition-all duration-300 hover:-translate-x-0.5 mb-6 group cursor-pointer">
        <svg class="w-4 h-4 text-espresso group-hover:text-terracotta transition-colors transform group-hover:-translate-x-0.5 duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span>Kembali</span>
      </button>

      <!-- Loading State -->
      <div v-if="isLoading" class="flex flex-col items-center justify-center py-32 bg-white/40 backdrop-blur-md rounded-3xl border border-borderSoft/40 max-w-lg mx-auto shadow-sm">
        <div class="w-12 h-12 border-4 border-slate-100 border-t-terracotta rounded-full animate-spin"></div>
        <p class="text-xs text-slate-400 font-bold mt-4 tracking-wider uppercase">Memuat Profil Toko...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="bg-white rounded-3xl p-12 border border-[#F2EADA] text-center shadow-sm max-w-md mx-auto">
        <span class="text-4xl">⚠️</span>
        <h3 class="text-lg font-black text-espresso mt-4">Gagal memuat toko</h3>
        <p class="text-xs text-slate-500 mt-2">{{ error }}</p>
        <button @click="loadShop" class="mt-6 px-6 py-2.5 bg-terracotta text-white font-bold rounded-xl text-xs hover:bg-terracottaDark transition">Coba Lagi</button>
      </div>

      <!-- Shop Main View -->
      <div v-else class="space-y-8 animate-fade-in">
        
        <!-- Shop Header Card -->
        <div class="bg-white rounded-3xl border border-borderSoft/50 shadow-[0_16px_48px_rgba(43,30,22,0.03)] overflow-hidden">
          
          <!-- Background Banner (Sleek Compact Height with Rich Heritage Feel) -->
          <div class="h-36 sm:h-44 bg-gradient-to-br from-[#2B1E16] via-[#3D2A1F] to-[#1E140E] relative overflow-hidden">
            <!-- Decorative Batik Pattern with Radial Overlay -->
            <div class="absolute inset-0 opacity-[0.16] bg-cover bg-center mix-blend-overlay" style="background-image: url('/assets/batik-bg.png');"></div>
            <!-- Glow Highlights -->
            <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-gold/10 rounded-full blur-3xl"></div>
            <div class="absolute -left-20 -top-20 w-64 h-64 bg-terracotta/15 rounded-full blur-3xl"></div>
            <!-- Elegant Heritage Grid Lines -->
            <div class="absolute inset-0 opacity-[0.02] bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
            <!-- Golden Decorative Accent Stripe -->
            <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-gradient-to-r from-terracotta via-gold to-terracotta"></div>
          </div>

          <!-- Profile Details Container (Reduced Padding) -->
          <div class="px-5 pb-5 pt-0 sm:px-8 relative">
            
            <!-- Shop Logo / Avatar (Compact Double-Ring) - Fixed: Removed duplicate 'relative' to make it float absolute -->
            <div class="absolute -top-12 sm:-top-16 left-5 sm:left-8 z-20 w-24 h-24 sm:w-32 sm:h-32 rounded-full border-4 border-white bg-white shadow-[0_8px_24px_rgba(43,30,22,0.1)] overflow-hidden flex items-center justify-center transition-all duration-500 hover:scale-[1.03] hover:rotate-1 ring-4 ring-sand/30 ml-1 sm:ml-2">
              <img v-if="seller?.avatar_url" :src="seller.avatar_url" :alt="seller.name" class="w-full h-full object-cover" />
              <img v-else :src="`https://api.dicebear.com/7.x/avataaars/svg?seed=${encodeURIComponent(seller?.name || 'UMKM')}`" alt="Seller Avatar" class="w-full h-full object-cover" />
            </div>

            <!-- Shop Main Information (Polished Spacing & Alignment) -->
            <div class="pt-16 sm:pt-6 sm:pl-44 space-y-2 px-1 sm:px-0">
              <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3">
                <h1 class="text-xl sm:text-2xl font-serif font-black text-espresso tracking-tight flex items-center gap-2 leading-tight">
                  {{ seller?.name }}
                  <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-gradient-to-r from-gold to-amber-500 text-white shadow-sm ring-1 ring-white/10" title="UMKM Terverifikasi">
                    <svg class="w-2.5 h-2.5 fill-current" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                    </svg>
                  </span>
                </h1>
                
                <span class="inline-flex items-center rounded-full bg-terracotta/8 border border-terracotta/15 px-3 py-0.5 text-[9px] font-black text-terracotta uppercase tracking-wider leading-none w-fit shadow-sm">
                  UMKM Kreator
                </span>
              </div>

              <div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 text-[11px] font-semibold text-muted">
                <span class="flex items-center gap-1 hover:text-espresso transition-colors duration-200">
                  <svg class="w-3.5 h-3.5 text-terracotta" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  {{ seller?.location }}
                </span>
                
                <span v-if="seller?.phone" class="flex items-center gap-1 hover:text-espresso transition-colors duration-200">
                  <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                  </svg>
                  {{ formatPhoneNumber(seller.phone) }}
                </span>
              </div>

              <p class="text-xs text-muted/95 leading-relaxed max-w-3xl font-medium">
                {{ seller?.bio || 'Selamat datang di toko kami! Kami menyediakan produk kerajinan berkualitas tinggi khas Nusantara.' }}
              </p>
            </div>

            <!-- Separator Line (Compact Margin) -->
            <div class="border-t border-borderSoft/40 my-4.5"></div>

            <!-- Statistics Grid & Action Row (Compact) -->
            <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
              <!-- Grid statistics (Compact Floating Widgets) -->
              <div class="grid grid-cols-3 gap-3 w-full lg:max-w-md">
                
                <!-- Rating Card -->
                <div class="bg-gradient-to-br from-white to-[#FCFBF9] border border-borderSoft hover:border-amber-400/40 rounded-2xl p-3 text-center shadow-[0_4px_12px_rgba(43,30,22,0.015)] transition-all duration-300 hover:shadow-[0_8px_20px_rgba(43,30,22,0.04)] hover:-translate-y-0.5 group">
                  <div class="w-7 h-7 mx-auto rounded-full bg-amber-500/10 flex items-center justify-center mb-1.5 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-4 h-4 text-amber-500 fill-current" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.46a1 1 0 00-.363 1.118l1.287 3.95c.3.921-.755 1.688-1.54 1.118l-3.388-2.46a1 1 0 00-1.175 0l-3.388 2.46c-.784.57-1.838-.197-1.539-1.118l1.287-3.95a1 1 0 00-.363-1.118L2.098 9.377c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.95z"/>
                    </svg>
                  </div>
                  <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block">Rating Toko</span>
                  <span class="text-xs font-black text-espresso mt-1 block leading-none">
                    {{ seller?.rating?.toFixed(1) || '4.5' }} <span class="text-[9px] text-slate-400 font-normal">/ 5.0</span>
                  </span>
                </div>
                
                <!-- Total Sold Card -->
                <div class="bg-gradient-to-br from-white to-[#FCFBF9] border border-borderSoft hover:border-terracotta/30 rounded-2xl p-3 text-center shadow-[0_4px_12px_rgba(43,30,22,0.015)] transition-all duration-300 hover:shadow-[0_8px_20px_rgba(43,30,22,0.04)] hover:-translate-y-0.5 group">
                  <div class="w-7 h-7 mx-auto rounded-full bg-terracotta/10 flex items-center justify-center mb-1.5 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-4 h-4 text-terracotta" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                  </div>
                  <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block">Total Terjual</span>
                  <span class="text-xs font-black text-espresso mt-1 block leading-none">{{ seller?.sold || 0 }}+ pcs</span>
                </div>
                
                <!-- Total Products Card -->
                <div class="bg-gradient-to-br from-white to-[#FCFBF9] border border-borderSoft hover:border-emerald-500/30 rounded-2xl p-3 text-center shadow-[0_4px_12px_rgba(43,30,22,0.015)] transition-all duration-300 hover:shadow-[0_8px_20px_rgba(43,30,22,0.04)] hover:-translate-y-0.5 group">
                  <div class="w-7 h-7 mx-auto rounded-full bg-emerald-600/10 flex items-center justify-center mb-1.5 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                  </div>
                  <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block">Jumlah Produk</span>
                  <span class="text-xs font-black text-espresso mt-1 block leading-none">{{ products.length }} item</span>
                </div>
              </div>

              <!-- Button actions (Aligned & Sleek Heights) -->
              <div class="grid grid-cols-2 gap-2.5 sm:flex sm:flex-row sm:gap-3 w-full lg:w-auto mt-2 lg:mt-0">
                <a v-if="seller?.phone" :href="`https://wa.me/${seller.phone.replace(/[^0-9]/g, '')}`" target="_blank" class="h-11 px-5 border border-borderSoft hover:border-emerald-500 bg-white hover:bg-emerald-50/15 text-slate-700 hover:text-emerald-700 font-bold rounded-xl text-xs flex items-center justify-center gap-2 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 shadow-sm hover:shadow-md cursor-pointer text-center whitespace-nowrap">
                  <svg class="w-4.5 h-4.5 text-emerald-500 fill-current flex-shrink-0" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.73-1.45L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.37 9.864-9.799.002-2.63-1.023-5.101-2.885-6.965C16.588 1.977 14.113.953 11.488.953c-5.43 0-9.85 4.37-9.854 9.799-.001 1.758.463 3.473 1.345 4.998l-.998 3.645 3.733-.979zm11.391-7.145c-.298-.15-1.763-.87-2.036-.97-.273-.1-.472-.15-.67.15-.198.3-.77.97-.943 1.17-.173.2-.347.225-.645.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.521.15-.173.199-.298.298-.497.099-.198.05-.372-.025-.521-.075-.15-.67-1.613-.918-2.21-.242-.58-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.762-.719 2.01-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                  </svg>
                  <span>Hubungi Penjual</span>
                </a>
                
                <button @click="inviteCollaboration" class="h-11 px-5 bg-espresso hover:bg-terracotta text-white font-bold rounded-xl text-xs hover:scale-[1.02] hover:shadow-lg active:scale-95 transition-all duration-300 flex items-center justify-center gap-2 cursor-pointer text-center" :class="seller?.phone ? '' : 'col-span-2'">
                  <svg class="w-4.5 h-4.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  <span>Ajak Kolaborasi</span>
                </button>
              </div>
            </div>

          </div>
        </div>

        <!-- Catalog & Search Filter Area -->
        <div class="space-y-6 mt-8">
          
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-borderSoft/50 pb-4">
            <div>
              <h2 class="text-xl font-serif font-black text-espresso flex items-center gap-2">
                🛍️ Koleksi Produk
              </h2>
              <p class="text-xs text-muted mt-1 font-semibold">Jelajahi produk kerajinan berkualitas tinggi di toko kami.</p>
            </div>

            <!-- Internal Search Input (Premium Pill) -->
            <div class="relative w-full sm:max-w-xs group">
              <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 pointer-events-none group-focus-within:text-terracotta transition-colors duration-250">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </span>
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Cari produk di toko ini..."
                class="w-full bg-white border border-[#EBE3D5] rounded-full pl-10 pr-4 py-2.5 text-xs text-espresso placeholder-slate-400 focus:outline-none focus:border-terracotta focus:ring-4 focus:ring-terracotta/10 transition-all duration-300"
              />
            </div>
          </div>

          <!-- Dynamic Category Tabs (Premium Glowing Pills) -->
          <div v-if="uniqueCategories.length > 2" class="flex overflow-x-auto hide-scrollbar gap-2.5 pb-2 -mx-4 px-4 sm:mx-0 sm:px-0">
            <button
              v-for="cat in uniqueCategories"
              :key="cat"
              @click="activeCategory = cat"
              class="px-5 py-2.5 rounded-full text-xs font-bold transition-all duration-350 whitespace-nowrap border cursor-pointer"
              :class="activeCategory === cat
                ? 'bg-terracotta border-terracotta text-white shadow-[0_4px_12px_rgba(184,92,56,0.25)]'
                : 'bg-white border-borderSoft text-espresso hover:border-terracotta/55 hover:text-terracotta hover:shadow-sm'"
            >
              {{ cat === 'Semua' ? '✨ Semua Kategori' : cat }}
            </button>
          </div>

          <!-- Products Grid -->
          <div v-if="filteredProducts.length" class="grid gap-6 grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            <router-link
              v-for="item in filteredProducts"
              :key="item.id"
              :to="getProductLink(item)"
              class="group block rounded-3xl border border-borderSoft bg-white p-3.5 transition-all duration-500 hover:-translate-y-1.5 hover:shadow-[0_24px_48px_rgba(43,30,22,0.06)] hover:border-terracotta/20"
            >
              <!-- Product Image Wrapper -->
              <div class="relative overflow-hidden rounded-2xl aspect-[4/5] bg-slate-50 border border-slate-100/50">
                <img :src="item.images?.[0] ?? '/images/placeholder-product.png'" :alt="item.name" class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-108" />
                
                <!-- Floating Glassmorphic Badges -->
                <div class="absolute top-3 left-3 flex flex-col gap-1.5 z-10">
                  <span v-for="badge in (item.badges || [])" :key="badge" class="inline-block rounded-full border border-white/30 bg-white/75 backdrop-blur-md px-2.5 py-1 text-[8px] font-extrabold text-terracotta uppercase tracking-wider leading-none shadow-sm">
                    {{ badge }}
                  </span>
                  <!-- Glowing 3D Badge -->
                  <span v-if="item.model_3d_path" class="inline-block rounded-full border border-emerald-300/30 bg-emerald-500/85 backdrop-blur-md px-2.5 py-1 text-[8px] font-extrabold text-white uppercase tracking-wider leading-none shadow-sm animate-pulse">
                    🕶️ 3D AR
                  </span>
                </div>
              </div>

              <!-- Product Info -->
              <div class="mt-4 space-y-2.5 px-1">
                <h4 class="text-xs font-bold text-espresso line-clamp-2 leading-snug min-h-[36px] group-hover:text-terracotta transition-colors duration-300">{{ item.name }}</h4>
                
                <div class="flex items-center gap-1.5 text-[10px] text-slate-400 font-semibold">
                  <svg class="w-3.5 h-3.5 text-amber-400 fill-current" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.46a1 1 0 00-.363 1.118l1.287 3.95c.3.921-.755 1.688-1.54 1.118l-3.388-2.46a1 1 0 00-1.175 0l-3.388 2.46c-.784.57-1.838-.197-1.539-1.118l1.287-3.95a1 1 0 00-.363-1.118L2.098 9.377c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.95z"/>
                  </svg>
                  <span class="font-black text-espresso leading-none">{{ item.rating?.toFixed(1) || '4.8' }}</span>
                  <span class="text-slate-300">•</span>
                  <span class="leading-none text-muted">{{ item.sold ?? 0 }}+ terjual</span>
                </div>

                <div class="pt-3 flex justify-between items-center border-t border-[#FDFBF7]">
                  <div>
                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block leading-none mb-0.5">Harga</span>
                    <p class="text-sm font-black text-terracotta">Rp {{ formatCurrency(item.price) }}</p>
                  </div>
                  <span class="w-8.5 h-8.5 rounded-full bg-orange-50 hover:bg-terracotta flex items-center justify-center text-terracotta hover:text-white border border-orange-100/50 transition-all duration-300 shadow-sm group-hover:scale-105">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                  </span>
                </div>
              </div>
            </router-link>
          </div>

          <!-- Empty State -->
          <div v-else class="bg-white rounded-3xl p-12 border border-borderSoft/60 text-center shadow-[0_12px_30px_rgba(43,30,22,0.02)]">
            <span class="text-4xl">🔍</span>
            <p class="text-sm font-bold text-espresso mt-4">Produk tidak ditemukan</p>
            <p class="text-xs text-slate-400 mt-1">Coba gunakan kata kunci pencarian atau kategori lain.</p>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Navbar from '@/pages/landing/partials/Navbar.vue';
import { useNotificationStore } from '@/stores/notificationStore';

const route = useRoute();
const router = useRouter();
const notificationStore = useNotificationStore();

const sellerId = route.params.id;
const user = ref(JSON.parse(localStorage.getItem('user') || 'null'));
const token = localStorage.getItem('token');

const seller = ref(null);
const products = ref([]);
const isLoading = ref(true);
const error = ref('');

// Internal filters
const searchQuery = ref('');
const activeCategory = ref('Semua');

const loadShop = async () => {
  isLoading.value = true;
  error.value = '';
  try {
    const res = await fetch(`/api/v1/shops/${sellerId}`, {
      headers: { 'Accept': 'application/json' }
    });
    const data = await res.json();
    if (!res.ok) {
      throw new Error(data.message || 'Gagal memuat profil toko.');
    }
    seller.value = data.seller;
    products.value = data.products || [];
  } catch (err) {
    console.error('Failed to load shop:', err);
    error.value = err.message || 'Terjadi kesalahan saat memuat halaman.';
  } finally {
    isLoading.value = false;
  }
};

const getProductLink = (item) => {
  if (!item) return '#';
  const slug = item.name.toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/(^-|-$)/g, '');
  return `/${slug}-i.${item.id}`;
};

const formatCurrency = (val) => {
  if (!val) return '0';
  return new Intl.NumberFormat('id-ID').format(val);
};

const formatPhoneNumber = (phone) => {
  if (!phone) return '';
  let cleaned = phone.replace(/\D/g, '');
  if (cleaned.startsWith('0')) {
    return cleaned.replace(/(\d{4})(\d{4})(\d+)/, '$1-$2-$3');
  }
  if (cleaned.startsWith('62')) {
    return '+' + cleaned.replace(/(\d{2})(\d{3})(\d{4})(\d+)/, '$1 $2-$3-$4');
  }
  return phone;
};

// Extract unique categories dynamically from products
const uniqueCategories = computed(() => {
  const cats = new Set(products.value.map(p => p.category).filter(Boolean));
  return ['Semua', ...Array.from(cats)];
});

// Dynamic filtering
const filteredProducts = computed(() => {
  return products.value.filter(item => {
    const nameStr = item.name ? String(item.name).toLowerCase() : '';
    const descStr = item.description ? String(item.description).toLowerCase() : '';
    const query = searchQuery.value.toLowerCase();
    const matchesSearch = nameStr.includes(query) || descStr.includes(query);
    const matchesCategory = activeCategory.value === 'Semua' || item.category === activeCategory.value;
    return matchesSearch && matchesCategory;
  });
});

const inviteCollaboration = async () => {
  if (!token || !user.value) {
    notificationStore.warning('Silakan login terlebih dahulu untuk mengajak kolaborasi.', 4000);
    return router.push('/login');
  }

  let activeRole = user.value.active_role;

  // Jika active_role bukan UMKM atau Desainer, coba switch otomatis di latar belakang jika memiliki perannya
  if (!['umkm', 'designer', 'desainer'].includes(activeRole)) {
    const hasUmkm = user.value.is_umkm || user.value.owned_roles?.includes('umkm');
    const hasDesigner = user.value.is_designer || user.value.owned_roles?.includes('designer') || user.value.owned_roles?.includes('desainer');

    if (hasDesigner) {
      try {
        notificationStore.info('Mengaktifkan peran Desainer untuk kolaborasi...', 3000);
        const switchRes = await fetch('/api/v1/profile/switch-role', {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          },
          body: JSON.stringify({ role: 'designer' }),
        });
        if (switchRes.ok) {
          const switchData = await switchRes.json();
          localStorage.setItem('user', JSON.stringify(switchData.user));
          user.value = switchData.user;
          activeRole = 'designer';
        } else {
          notificationStore.error('Gagal mengaktifkan peran Desainer.');
          return;
        }
      } catch (err) {
        console.error('Failed to switch role:', err);
        notificationStore.error('Gagal mengaktifkan peran Desainer.');
        return;
      }
    } else if (hasUmkm) {
      try {
        notificationStore.info('Mengaktifkan peran UMKM untuk kolaborasi...', 3000);
        const switchRes = await fetch('/api/v1/profile/switch-role', {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          },
          body: JSON.stringify({ role: 'umkm' }),
        });
        if (switchRes.ok) {
          const switchData = await switchRes.json();
          localStorage.setItem('user', JSON.stringify(switchData.user));
          user.value = switchData.user;
          activeRole = 'umkm';
        } else {
          notificationStore.error('Gagal mengaktifkan peran UMKM.');
          return;
        }
      } catch (err) {
        console.error('Failed to switch role:', err);
        notificationStore.error('Gagal mengaktifkan peran UMKM.');
        return;
      }
    } else {
      notificationStore.warning('Hanya akun UMKM atau Desainer yang dapat memulai kolaborasi Co-Create. Silakan daftarkan diri Anda di menu Profil.', 5000);
      return;
    }
  }

  if (parseInt(sellerId) === user.value.id) {
    notificationStore.warning('Anda tidak dapat memulai kolaborasi dengan diri sendiri.', 4000);
    return;
  }

  try {
    const sellerName = seller.value?.name || 'UMKM';
    const roomName = `Kolaborasi: Desain Produk dengan ${sellerName}`;
    const description = `Ruang kerja kolaboratif untuk mendesain produk bersama ${sellerName} yang diinisiasi dari halaman profil toko.`;

    const res = await fetch('/api/v1/cocreate/rooms', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`
      },
      body: JSON.stringify({
        name: roomName,
        description: description,
        invite_user_id: sellerId
      })
    });

    const data = await res.json();
    if (!res.ok) {
      throw new Error(data.message || 'Gagal membuat ruang kolaborasi.');
    }

    const newRoom = data.data;
    if (newRoom.status === 'pending') {
      notificationStore.success('Permintaan kolaborasi berhasil dikirim! Menunggu persetujuan.', 5000);
      if (activeRole === 'umkm') {
        router.push('/umkm/cocreate');
      } else {
        router.push('/designer/cocreate');
      }
    } else {
      notificationStore.success('Ruang Kolaborasi Co-Create Berhasil Dibuat!', 4000);
      if (activeRole === 'umkm') {
        router.push(`/umkm/cocreate/${newRoom.id}`);
      } else {
        router.push(`/designer/cocreate/${newRoom.id}`);
      }
    }
  } catch (err) {
    console.error('Co-Create error:', err);
    notificationStore.error(err.message || 'Gagal membuat ruang kolaborasi.');
  }
};

onMounted(() => {
  loadShop();
});
</script>
