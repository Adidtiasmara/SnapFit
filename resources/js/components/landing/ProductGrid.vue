<template>
  <section id="marketplace" class="pt-4 md:pt-4 pb-16 md:pb-24 relative overflow-hidden bg-transparent">
    <!-- Clean background, elements removed to keep it simple -->

    <div class="max-w-[1520px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <!-- PROMO CAROUSEL -->
      <div v-if="isHomepage" class="mb-7" data-aos="fade-up">
        <div class="overflow-hidden rounded-3xl relative">

          <!-- Slides -->
          <div class="flex transition-transform duration-700"
            :style="{ transform: `translateX(-${currentSlide * 100}%)` }">
            <div v-for="(slide, index) in promoSlides" :key="index"
              class="min-w-full h-[185px] sm:h-[210px] md:h-[220px] relative rounded-[24px] overflow-hidden bg-gradient-to-br from-[#5C4033] via-[#8B6F47] to-[#A0845C]">
              <!-- Background -->
              <img :src="slide.image" class="absolute inset-0 w-full h-full object-cover"
                @error="$event.target.style.display = 'none'" />

              <!-- Multi-layer depth overlays -->
              <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/35 to-black/10"></div>
              <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-black/10"></div>
              <!-- Subtle radial vignette -->
              <div class="absolute inset-0"
                style="background: radial-gradient(ellipse at 75% 50%, transparent 40%, rgba(0,0,0,0.3) 100%);"></div>
              <!-- Decorative batik pattern overlay -->
              <div class="absolute inset-0 opacity-[0.08] mix-blend-overlay"
                style="background-image: url('/assets/batik-bg.png'); background-size: 280px; background-repeat: repeat;">
              </div>

              <!-- Content Wrapper (BIAR TIDAK NEMPEL) -->
              <div class="relative z-10 h-full flex items-center">
                <div class="w-full max-w-[1520px] mx-auto px-4 sm:px-6 lg:px-8">

                  <div class="max-w-2xl text-white">
                    <span v-if="slide.tag"
                      class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-black/30 backdrop-blur-sm border border-white/20 text-[10px] font-black uppercase tracking-[0.2em] mb-3">
                      <span class="w-1.5 h-1.5 bg-orange-400 rounded-full"></span>
                      {{ slide.tag }}
                    </span>

                    <h3 class="text-[15px] sm:text-2xl lg:text-3xl font-black mb-1 leading-tight tracking-tight">
                      {{ slide.title }}
                    </h3>

                    <p class="text-[10px] sm:text-xs md:text-sm text-white/85 mb-2 md:mb-4 max-w-lg leading-relaxed line-clamp-2">
                      {{ slide.description }}
                    </p>

                    <button
                      type="button"
                      @click="exploreCollection"
                      class="bg-terracotta px-3.5 py-1.5 sm:px-5 sm:py-2.5 rounded-xl font-bold text-[9px] sm:text-[11px] uppercase tracking-widest hover:bg-terracottaDark transition-all shadow-lg hover:shadow-terracotta/30">
                      Jelajahi Koleksi
                    </button>
                  </div>

                </div>
              </div>
            </div>
          </div>

          <!-- Indicator -->
          <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
            <span v-for="(slide, index) in promoSlides" :key="index" @click="currentSlide = index"
              class="h-2 rounded-full cursor-pointer transition-all"
              :class="currentSlide === index ? 'bg-terracotta w-6' : 'bg-surface/50 w-2'"></span>
          </div>

        </div>
      </div>
      <!-- Header with Animation
      <div class="text-center mb-16 animate-fade-in">


        <h2 class="text-5xl md:text-6xl font-black tracking-tighter mb-4">
          <span class="text-espresso">Indonesian Craft</span>
          <span class="block text-terracotta">Marketplace</span>
        </h2>
        <p class="text-muted text-lg max-w-2xl mx-auto font-medium">
          Koleksi terkurasi dari pengrajin terbaik seluruh Indonesia. Setiap produk dibuat dengan cinta dan keahlian
          turun-temurun.
        </p>
      </div> -->

      <!-- INNOVATION FEATURES SECTION (Only for Homepage) -->
      <div v-if="isHomepage" 
        class="grid grid-cols-3 gap-2.5 sm:gap-4 mb-6 md:mb-9" 
        data-aos="fade-up" data-aos-delay="50">

        <!-- AI Studio -->
        <div
          @click="goToAiStudio"
          class="bg-surface border border-borderSoft p-3 sm:p-4 rounded-2xl shadow-sm hover:shadow-md hover:border-terracotta transition-all duration-300 flex flex-col items-center text-center justify-center md:flex-row md:items-center md:text-left md:justify-start cursor-pointer w-full min-h-[96px] md:min-h-[82px] gap-2 md:gap-4">
          <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl overflow-hidden shrink-0 border border-[#E8DCCB]/60 shadow-sm bg-slate-50">
            <img :src="'/images/features/studio_ai.png'" class="w-full h-full object-cover" />
          </div>
          <div>
            <h3 class="text-[10px] sm:text-xs md:text-sm font-black text-espresso mb-0.5 leading-tight">Studio Produk AI</h3>
            <p class="hidden md:block text-[11px] text-muted leading-tight">Analisis tren & optimasi metadata otomatis untuk UMKM.</p>
          </div>
        </div>

        <!-- AR Try-On -->
        <div
          @click="openArTryOn"
          class="bg-surface border border-borderSoft p-3 sm:p-4 rounded-2xl shadow-sm hover:shadow-md hover:border-indigo transition-all duration-300 flex flex-col items-center text-center justify-center md:flex-row md:items-center md:text-left md:justify-start cursor-pointer w-full min-h-[96px] md:min-h-[82px] gap-2 md:gap-4">
          <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl overflow-hidden shrink-0 border border-[#E8DCCB]/60 shadow-sm bg-slate-50">
            <img :src="'/images/features/ar_tryon.png'" class="w-full h-full object-cover" />
          </div>
          <div>
            <h3 class="text-[10px] sm:text-xs md:text-sm font-black text-espresso mb-0.5 leading-tight">AR Try-On</h3>
            <p class="hidden md:block text-[11px] text-muted leading-tight">Visualisasikan kerajinan & dekorasi langsung di ruanganmu.</p>
          </div>
        </div>

        <!-- Co-Create Room -->
        <div
          @click="goToCoCreate"
          class="bg-surface border border-borderSoft p-3 sm:p-4 rounded-2xl shadow-sm hover:shadow-md hover:border-olive transition-all duration-300 flex flex-col items-center text-center justify-center md:flex-row md:items-center md:text-left md:justify-start cursor-pointer w-full min-h-[96px] md:min-h-[82px] gap-2 md:gap-4">
          <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl overflow-hidden shrink-0 border border-[#E8DCCB]/60 shadow-sm bg-slate-50">
            <img :src="'/images/features/cocreate.png'" class="w-full h-full object-cover" />
          </div>
          <div>
            <h3 class="text-[10px] sm:text-xs md:text-sm font-black text-espresso mb-0.5 leading-tight">Ruang Co-Create</h3>
            <p class="hidden md:block text-[11px] text-muted leading-tight">Kolaborasi desain custom langsung dengan pengrajin lokal.</p>
          </div>
        </div>
      </div>

      <!-- Section Title -->
      <div class="mb-4 mt-10 md:mt-12">
        <div class="flex items-center justify-between">
          <h2 class="text-lg md:text-xl font-black text-espresso flex items-center gap-2.5">
            <span class="w-1.5 h-5 bg-terracotta rounded-full"></span>
            Koleksi Pilihan Nusantara
          </h2>
          <!-- <a href="#"
            class="lihat-semua-link text-xs md:text-sm font-extrabold text-terracotta hover:text-terracottaDark transition-all flex items-center gap-1.5 group/link">
            Lihat Semua
            <svg class="w-3.5 h-3.5 md:w-4 md:h-4 transition-transform group-hover/link:translate-x-0.5" fill="none" stroke="currentColor"
              stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </a> -->
        </div>
        <!-- Tagline / Subtitle -->
        <!-- <p class="text-[10px] md:text-xs text-muted mt-1 font-medium pl-4">
          Produk autentik hasil kurasi langsung dari pengrajin terbaik Indonesia.
        </p> -->
      </div>



      <!-- Loading State with Shimmering Skeleton -->
      <div v-if="isLoading" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 md:gap-5">
        <div v-for="i in 12" :key="i" class="space-y-3 p-3 bg-surface border border-borderSoft/40 rounded-2xl">
          <div class="aspect-square w-full skeleton rounded-xl"></div>
          <div class="space-y-2.5 mt-2">
            <div class="h-4 skeleton w-5/6"></div>
            <div class="h-3 skeleton w-2/3"></div>
            <div class="h-3 skeleton w-1/2"></div>
            <div class="h-5 skeleton w-1/3 mt-3"></div>
          </div>
        </div>
      </div>

      <!-- Products Grid -->
      <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 md:gap-5">
        <div v-for="(product, index) in filteredProducts" :key="product.id" @click="viewProductDetail(product)"
          class="stagger-item bg-surface border border-borderSoft shadow-sm hover:border-terracotta hover:shadow-xl transition-all duration-300 rounded-2xl group overflow-hidden flex flex-col cursor-pointer hover:-translate-y-1 relative"
          data-aos="fade-up" :data-aos-delay="(index % 6) * 50">

          <!-- Image Section -->
          <div class="relative w-full aspect-square bg-gray-100 isolate overflow-hidden border-b border-borderSoft">


            <!-- Action Buttons (from incoming) -->
            <div
              class="absolute top-2 right-2 z-20 hidden md:flex flex-col gap-2 md:opacity-0 md:group-hover:opacity-100 transition-all duration-300 md:translate-x-4 md:group-hover:translate-x-0">
              <!-- Wishlist -->
              <button @click.stop="toggleWishlist(product)"
                class="w-8 h-8 bg-surface/95 backdrop-blur-md rounded-full flex items-center justify-center hover:bg-surface hover:scale-110 active:scale-95 transition-all shadow-sm"
                :class="isWishlisted(product.id) ? 'text-red-500' : 'text-espresso'">
                <svg class="w-4 h-4" :class="isWishlisted(product.id) ? 'fill-current' : ''" fill="none"
                  stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
              </button>
            </div>

            <!-- Add to Cart Button (Bottom Right of Image, hidden on mobile, hover slide-up on desktop) -->
            <button @click.stop="addToCart(product)"
              class="absolute bottom-2 right-2 z-20 hidden md:flex w-8 h-8 bg-surface/95 backdrop-blur-md rounded-full items-center justify-center text-espresso hover:text-terracotta active:scale-95 transition-all duration-300 shadow-sm md:opacity-0 md:translate-y-2 md:group-hover:opacity-100 md:group-hover:translate-y-0"
              title="Tambah ke Keranjang">
              <svg class="w-4 h-4 text-espresso hover:text-terracotta" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </button>

            <img
              :src="product.images && product.images.length ? product.images[0] : 'https://images.unsplash.com/photo-1617627143750-d86bc21e42bb?q=80&w=1000'"
              :alt="product.name"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />

            <!-- Hover Overlay -->
            <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity z-10"></div>
          </div>

          <!-- Product Info -->
          <div class="p-3 pb-3.5 flex flex-col flex-1">
            <h3
              class="font-bold text-[13px] mb-1.5 text-espresso line-clamp-2 leading-snug group-hover:text-terracotta transition-colors">
              {{ product.name }}
            </h3>

            <!-- Shop & Location Info -->
            <div class="flex items-center gap-1.5 text-[10px] text-muted mb-1 truncate">
              <span class="font-bold text-espresso/70 truncate">{{ product.umkm_name }}</span>
              <span v-if="product.origin" class="text-muted/40 font-bold">•</span>
              <span v-if="product.origin" class="truncate">{{ product.origin }}</span>
            </div>

            <!-- Rating & Sold Info -->
            <div class="flex items-center gap-1 mb-1.5 text-[10px]">
              <svg class="w-3 h-3 text-gold fill-current" viewBox="0 0 20 20">
                <path
                  d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <span class="font-bold text-espresso">{{ product.rating || '4.8' }}</span>
              <span class="text-muted/50 font-bold">•</span>
              <span class="text-muted">{{ product.sold || '0' }} terjual</span>
            </div>

            <!-- Spacer -->
            <div class="flex-1"></div>

            <!-- Badges -->
            <div v-if="product.badges && product.badges.length" class="flex flex-wrap gap-1 mb-2">
              <span v-for="badge in product.badges.slice(0, 2)" :key="badge"
                class="text-[8px] px-1.5 py-0.5 border border-terracotta/20 text-terracotta rounded-md bg-terracotta/5 font-bold uppercase tracking-wider leading-none">
                {{ badge }}
              </span>
            </div>

            <!-- Price -->
            <div class="flex items-baseline justify-between mt-auto">
              <p class="text-base font-extrabold text-terracotta tracking-tight">
                <span class="text-[11px] font-semibold mr-0.5">Rp</span>{{ Number(product.price).toLocaleString('id-ID') }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State with Better Design -->
      <div v-if="!isLoading && filteredProducts.length === 0" class="py-32 text-center">
        <div class="max-w-md mx-auto">
          <div class="w-24 h-24 bg-sand rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-terracotta" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
          </div>
          <h3 class="text-2xl font-black text-espresso mb-3">Belum Ada Produk</h3>
          <p class="text-muted mb-6">Belum ada koleksi yang dipublikasikan untuk kategori <span
              class="font-bold text-terracotta">{{ activeCategory }}</span>.</p>
          <button @click="activeCategory = 'all'"
            class="inline-flex items-center gap-2 bg-terracotta text-white px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-terracottaDark hover:shadow-xl transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Lihat Semua Produk
          </button>
        </div>
      </div>
      <!-- Loading More State -->
      <div v-if="isLoadingMore" class="flex justify-center items-center py-8">
        <div class="flex items-center gap-2 px-6 py-3 bg-surface shadow-md rounded-full border border-terracotta/20">
          <div class="w-5 h-5 border-2 border-orange-500 border-t-transparent rounded-full animate-spin"></div>
          <span class="text-sm font-semibold text-terracotta">Memuat produk...</span>
        </div>
      </div>

      <!-- Load More Trigger -->
      <div ref="loadMoreTrigger" class="h-4 w-full"></div>
    </div>

    <div v-if="isArPickerOpen" class="fixed inset-0 z-[80] flex items-center justify-center bg-[#2B1E16]/55 backdrop-blur-sm p-4 overflow-hidden overscroll-none" @click.self="closeArPicker" @wheel.self.prevent @touchmove.self.prevent>
      <div class="w-full max-w-4xl h-[86vh] bg-[#FFFCF7] border border-[#E8DCCB] rounded-3xl shadow-2xl overflow-hidden flex flex-col" @wheel.stop @touchmove.stop>
        <div class="px-5 sm:px-6 py-5 border-b border-[#E8DCCB] flex items-start justify-between gap-4">
          <div>
            <p class="text-[10px] font-black uppercase tracking-[0.22em] text-terracotta mb-1">AR Try-On</p>
            <h3 class="text-xl sm:text-2xl font-black text-espresso tracking-tight">Pilih produk yang ingin dicoba</h3>
            <p class="text-xs sm:text-sm text-muted mt-1">Klik salah satu produk untuk membuka kamera AR pada halaman detail produk.</p>
          </div>
          <button type="button" @click="closeArPicker"
            class="w-9 h-9 rounded-xl bg-sand hover:bg-[#E8DCCB] text-muted hover:text-espresso flex items-center justify-center transition-colors flex-shrink-0"
            title="Tutup">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div class="px-5 sm:px-6 py-4 border-b border-[#E8DCCB]">
          <div class="relative">
            <svg class="w-4 h-4 text-muted absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input v-model="arSearchQuery" type="text" placeholder="Cari nama produk, kategori, atau UMKM..."
              class="w-full bg-white border border-[#E8DCCB] rounded-2xl pl-10 pr-4 py-3 text-sm font-medium text-espresso outline-none focus:border-terracotta focus:ring-4 focus:ring-terracotta/10 transition-all" />
          </div>
        </div>

        <div class="flex-1 min-h-0 overflow-y-auto overscroll-contain p-5 sm:p-6">
          <div v-if="arProductOptions.length === 0" class="py-14 text-center">
            <div class="w-16 h-16 rounded-2xl bg-sand mx-auto mb-4 flex items-center justify-center text-terracotta">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.6a1 1 0 00-.7.3l-2.4 2.4a1 1 0 01-.7.3h-3.2a1 1 0 01-.7-.3l-2.4-2.4a1 1 0 00-.7-.3H4" />
              </svg>
            </div>
            <p class="text-base font-black text-espresso">Produk tidak ditemukan</p>
            <p class="text-sm text-muted mt-1">Coba kata kunci lain atau lihat semua koleksi.</p>
          </div>

          <div v-else class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            <button v-for="product in arProductOptions" :key="product.id" type="button" @click="selectArProduct(product)"
              class="text-left bg-white border border-[#E8DCCB] hover:border-terracotta hover:shadow-lg rounded-2xl overflow-hidden transition-all active:scale-[0.98] group">
              <div class="aspect-square bg-slate-100 overflow-hidden">
                <img :src="getProductImage(product)" :alt="product.name"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
              </div>
              <div class="p-3">
                <p class="text-[12px] sm:text-[13px] font-black text-espresso leading-snug line-clamp-2 group-hover:text-terracotta transition-colors">
                  {{ product.name }}
                </p>
                <p class="text-[10px] text-muted mt-1 truncate">{{ product.umkm_name || product.owner?.name || 'UMKM Lokal' }}</p>
                <p class="text-sm font-black text-terracotta mt-2">Rp {{ Number(product.price || 0).toLocaleString('id-ID') }}</p>
              </div>
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount, watch } from 'vue';
import { useRouter } from 'vue-router';
import { useCartStore } from '@/stores/cartStore';
import { useWishlistStore } from '@/stores/wishlistStore';

const router = useRouter();
const cartStore = useCartStore();
const wishlistStore = useWishlistStore();

onMounted(() => {
  wishlistStore.loadWishlist();
});

const props = defineProps({
  products: { type: Array, default: () => [] },
  isLoading: { type: Boolean, default: false },
  isLoadingMore: { type: Boolean, default: false },
  hasMore: { type: Boolean, default: true },
  isHomepage: { type: Boolean, default: false }
});

const emit = defineEmits(['load-more']);

const loadMoreTrigger = ref(null);
const isArPickerOpen = ref(false);
const arSearchQuery = ref('');
let observer = null;
let previousBodyStyle = null;
let lockedScrollY = 0;

const activeCategory = ref('all');
const categories = ['all', 'batik', 'kerajinan', 'aksesoris', 'dekorasi', 'fashion'];

const filterChips = [
  { label: 'Semua', value: 'all' },
  { label: 'Batik', value: 'batik' },
  { label: 'Fashion', value: 'fashion' },
  { label: 'Kerajinan', value: 'kerajinan' },
  { label: 'Dekorasi', value: 'dekorasi' },
  { label: 'Aksesoris', value: 'aksesoris' },
];

const addToCart = async (product) => {
  if (product.variants && product.variants.length > 0) {
    viewProductDetail(product);
    return;
  }
  await cartStore.addItem(product);
};

const isWishlisted = (productId) => {
  return wishlistStore.isWishlisted(productId);
};

const toggleWishlist = async (product) => {
  await wishlistStore.toggleWishlist(product);
};

const productDetailPath = (product) => {
  const slug = product.name.toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/(^-|-$)/g, '');
  return `/${slug}-i.${product.id}`;
};

const viewProductDetail = (product) => {
  router.push(productDetailPath(product));
};

const exploreCollection = () => {
  document.getElementById('marketplace')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
};

const getStoredUser = () => {
  try {
    return JSON.parse(localStorage.getItem('user') ?? 'null');
  } catch {
    return null;
  }
};

const normalizeRole = (role) => role === 'desainer' ? 'designer' : role;

const getUserRoles = (user) => {
  if (!user) return [];
  const roles = [
    normalizeRole(user.active_role),
    ...(user.owned_roles ?? []).map(normalizeRole),
    ...(user.roles ?? []).map(role => normalizeRole(role?.name ?? role)),
  ].filter(Boolean);

  if (user.is_umkm) roles.push('umkm');
  if (user.is_designer) roles.push('designer');

  return [...new Set(roles)];
};

const goToRoleRegistration = (path) => {
  const token = localStorage.getItem('token');
  if (!token) {
    router.push({ name: 'login', query: { redirect: path } });
    return;
  }
  router.push(path);
};

const goToAiStudio = () => {
  const roles = getUserRoles(getStoredUser());
  if (roles.includes('umkm')) {
    router.push('/umkm/studio');
    return;
  }
  goToRoleRegistration('/register/umkm');
};

const openArTryOn = () => {
  if (props.products.length === 0) {
    exploreCollection();
    return;
  }
  arSearchQuery.value = '';
  isArPickerOpen.value = true;
};

const closeArPicker = () => {
  isArPickerOpen.value = false;
};

const selectArProduct = (product) => {
  closeArPicker();
  router.push({ path: productDetailPath(product), query: { ar: 'true' } });
};

const goToCoCreate = () => {
  const user = getStoredUser();
  const activeRole = normalizeRole(user?.active_role);
  const roles = getUserRoles(user);

  if (activeRole === 'designer' || roles.includes('designer')) {
    router.push('/designer/cocreate');
    return;
  }

  if (activeRole === 'umkm' || roles.includes('umkm')) {
    router.push('/umkm/cocreate');
    return;
  }

  goToRoleRegistration('/register/designer');
};

const filteredProducts = computed(() => {
  if (activeCategory.value === 'all') return props.products;
  return props.products.filter(p => p.category === activeCategory.value);
});

const arProductOptions = computed(() => {
  const query = arSearchQuery.value.trim().toLowerCase();
  const products = props.products;
  if (!query) return products;
  return products.filter((product) => {
    const searchable = [
      product.name,
      product.category,
      product.umkm_name,
      product.origin,
      product.owner?.name,
    ].filter(Boolean).join(' ').toLowerCase();
    return searchable.includes(query);
  });
});

const getProductImage = (product) => {
  if (Array.isArray(product.images) && product.images.length > 0) return product.images[0];
  if (typeof product.images === 'string') {
    try {
      const parsed = JSON.parse(product.images);
      if (Array.isArray(parsed) && parsed.length > 0) return parsed[0];
      if (typeof parsed === 'string' && parsed) return parsed;
    } catch {
      if (product.images) return product.images;
    }
  }
  return product.image_url || 'https://images.unsplash.com/photo-1617627143750-d86bc21e42bb?q=80&w=1000';
};

const lockBodyScroll = () => {
  if (typeof document === 'undefined') return;
  if (previousBodyStyle) return;

  lockedScrollY = window.scrollY || document.documentElement.scrollTop || 0;
  previousBodyStyle = {
    overflow: document.body.style.overflow,
    position: document.body.style.position,
    top: document.body.style.top,
    left: document.body.style.left,
    right: document.body.style.right,
    width: document.body.style.width,
  };

  document.body.style.overflow = 'hidden';
  document.body.style.position = 'fixed';
  document.body.style.top = `-${lockedScrollY}px`;
  document.body.style.left = '0';
  document.body.style.right = '0';
  document.body.style.width = '100%';
};

const unlockBodyScroll = () => {
  if (typeof document === 'undefined' || !previousBodyStyle) return;

  document.body.style.overflow = previousBodyStyle.overflow;
  document.body.style.position = previousBodyStyle.position;
  document.body.style.top = previousBodyStyle.top;
  document.body.style.left = previousBodyStyle.left;
  document.body.style.right = previousBodyStyle.right;
  document.body.style.width = previousBodyStyle.width;
  previousBodyStyle = null;

  window.scrollTo(0, lockedScrollY);
};

watch(isArPickerOpen, (isOpen) => {
  if (isOpen) lockBodyScroll();
  else unlockBodyScroll();
});

const currentSlide = ref(0);

const promoSlides = [
  {
    title: "Sentuhan Tradisional, Kualitas Internasional",
    description: "Setiap produk adalah hasil kurasi ketat dari pengrajin maestro, memadukan teknik warisan leluhur dengan estetika kontemporer.",
    image: "/assets/banner1.png",

  },
  {
    title: "Karya Tangan Terbaik Pengrajin Lokal",
    description: "Dukung perekonomian lokal dengan membeli produk langsung dari pembuatnya.",
    image: "/assets/banner2.png",

  },
  {
    title: "Dekorasi Rumah Berbalut Budaya",
    description: "Percantik ruangan Anda dengan sentuhan estetika tradisional yang tak lekang oleh waktu.",
    image: "/assets/banner3.png",

  }
];

onMounted(() => {
  setInterval(() => {
    currentSlide.value =
      (currentSlide.value + 1) % promoSlides.length;
  }, 4000);

  // Intersection Observer for Infinite Scroll
  observer = new IntersectionObserver((entries) => {
    if (entries[0].isIntersecting && props.hasMore && !props.isLoading && !props.isLoadingMore) {
      emit('load-more');
    }
  }, {
    rootMargin: '100px',
  });

  if (loadMoreTrigger.value) {
    observer.observe(loadMoreTrigger.value);
  }
});

onBeforeUnmount(() => {
  if (observer) {
    observer.disconnect();
  }
  unlockBodyScroll();
});
</script>

<style scoped>
/* Scrollbar Hider */
.no-scrollbar::-webkit-scrollbar {
  display: none;
}

.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

/* Line Clamp */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Fade In Animation */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fadeIn 0.8s ease-out;
}

/* Smooth Hover Effects */
.group:hover .group-hover\:scale-110 {
  transform: scale(1.1);
}

/* Lihat Semua Hover Underline */
.lihat-semua-link {
  position: relative;
  text-decoration: none;
}

.lihat-semua-link::after {
  content: '';
  position: absolute;
  left: 0;
  bottom: -2px;
  width: 0;
  height: 2px;
  background: currentColor;
  border-radius: 1px;
  transition: width 0.3s ease;
}

.lihat-semua-link:hover::after {
  width: 100%;
}

/* Custom Gradient Text */
.bg-clip-text {
  -webkit-background-clip: text;
  background-clip: text;
}
</style>
