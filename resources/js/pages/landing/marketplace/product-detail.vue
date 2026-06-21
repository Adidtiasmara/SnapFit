<template>
  <div>
    <section class="snapfit-heritage-bg min-h-screen text-espresso pb-0 lg:pb-10 relative overflow-hidden">
    <!-- Navbar -->
    <Navbar
      :user="user"
      @logout="logout"
      @goToLogin="goToLogin"
      @goToProfile="goToProfile"
      @goToOrders="goToOrders"
    />

    <!-- Main Content Container -->
    <div class="relative z-10 max-w-[1420px] mx-auto">

      <!-- DESKTOP VIEW (hidden on mobile, visible on lg and up) -->
      <div class="desktop-view-container hidden lg:block px-4 sm:px-6 lg:px-8 pt-16 sm:pt-16 pb-12">
        <!-- Breadcrumbs (Desktop only) -->
        <div class="flex mb-6 items-center gap-2 text-xs font-semibold text-[#8A7A6C] tracking-wide">
          <router-link to="/" class="hover:text-terracotta transition">Beranda</router-link>
          <span class="text-slate-300">/</span>
          <span class="hover:text-terracotta transition capitalize cursor-pointer" @click="router.push(`/?category=${product?.category}`)">{{ product?.category ?? 'Kategori' }}</span>
          <span class="text-slate-300">/</span>
          <span class="text-espresso font-black truncate max-w-[250px]">{{ product?.name }}</span>
        </div>

        <!-- Main Product Section (Image on left, Buy/Info block on right) -->
        <div class="grid gap-8 grid-cols-[1.1fr_1fr] items-start">
          <!-- Left Column: Product Gallery -->
          <div class="bg-surface rounded-[2.25rem] p-4 shadow-[0_4px_24px_rgba(0,0,0,0.02)] border border-borderSoft overflow-hidden">
            <div class="grid gap-4">
              <!-- Main Image -->
              <div class="relative overflow-hidden rounded-[1.75rem] bg-slate-50 aspect-square lg:aspect-[4/5] border border-borderSoft/20 shadow-inner">
                <img
                  v-if="activeImage"
                  :src="activeImage"
                  :alt="product?.name ?? 'Product image'"
                  class="w-full h-full object-cover transition duration-300 ease-out hover:scale-105"
                />
                <div class="absolute right-4 top-4 rounded-full bg-surface/90 px-3 py-1.5 text-xs font-bold text-espresso backdrop-blur border border-borderSoft/40 shadow-sm">
                  {{ currentImageLabel }}
                </div>
              </div>

              <!-- Thumbnails -->
              <div class="flex flex-row gap-3 overflow-x-auto hide-scrollbar rounded-2xl bg-[#FFFCF7] p-2.5 border border-[#E8DCCB]/60">
                <button
                  v-for="(image, index) in galleryImages"
                  :key="index"
                  type="button"
                  @click="activeImageIndex = index"
                  class="group flex-shrink-0 w-20 h-20 flex items-center justify-center overflow-hidden rounded-2xl border transition-all duration-200 bg-white"
                  :class="index === activeImageIndex ? 'border-terracotta ring-2 ring-terracotta/20 shadow-sm' : 'border-[#E8DCCB]/40 hover:border-terracotta/50'"
                >
                  <img :src="image" :alt="`Gallery ${index + 1}`" class="h-full w-full object-cover transition duration-300 group-hover:scale-105" />
                </button>
              </div>
            </div>
          </div>

          <!-- Right Column: Purchasing & Seller Hub -->
          <div class="space-y-6">
            <!-- Purchase Block -->
            <div class="rounded-[2.25rem] bg-surface p-8 shadow-[0_4px_24px_rgba(0,0,0,0.02)] border border-borderSoft">
              <div class="mb-4">
                <!-- Badges -->
                <div class="flex flex-wrap gap-2 mb-3">
                  <span class="inline-flex items-center rounded-lg border border-terracotta/20 bg-[#F8F1E7]/60 px-2.5 py-1 text-[9px] font-bold text-terracotta uppercase tracking-wider leading-none">
                    {{ product?.category }}
                  </span>
                  <span v-if="product?.origin" class="inline-flex items-center rounded-lg border border-borderSoft bg-[#FAF6F0] px-2.5 py-1 text-[9px] font-bold text-[#8A7A6C] uppercase tracking-wider leading-none">
                    {{ product?.origin }}
                  </span>
                  <span v-for="badge in product?.badges?.slice(0,2)" :key="badge" class="inline-flex items-center rounded-lg border border-[#E8DCCB]/30 bg-[#FFFCF7]/80 px-2.5 py-1 text-[9px] font-bold text-espresso uppercase tracking-wider leading-none">
                    {{ badge }}
                  </span>
                </div>

                <h1 class="text-2xl lg:text-3xl font-black tracking-tight text-[#2B1E16] leading-tight">{{ product?.name ?? 'Loading produk...' }}</h1>
                
                <!-- Ratings Summary -->
                <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-[#8A7A6C]">
                  <div class="flex items-center gap-1 bg-[#F8F1E7]/60 px-2 py-1 rounded-lg border border-terracotta/10">
                    <svg class="w-3.5 h-3.5 text-amber-500 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.46a1 1 0 00-.363 1.118l1.287 3.95c.3.921-.755 1.688-1.54 1.118l-3.388-2.46a1 1 0 00-1.175 0l-3.388 2.46c-.784.57-1.838-.197-1.539-1.118l1.287-3.95a1 1 0 00-.363-1.118L2.098 9.377c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.95z"/></svg>
                    <span class="font-black text-espresso">{{ product?.rating?.toFixed(1) }}</span>
                  </div>
                  <span class="text-slate-300/60">•</span>
                  <span>{{ product?.seller?.sold }}+ terjual</span>
                  <span class="text-slate-300/60">•</span>
                  <span class="underline-offset-4 hover:underline transition font-bold">{{ reviewCount }} ulasan</span>
                </div>
              </div>

              <!-- Price & Stock -->
              <div class="mb-5 py-4 border-y border-[#E8DCCB]/30 flex flex-col gap-2.5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                  <p class="text-[9px] font-black uppercase tracking-wider text-[#8A7A6C] mb-0.5">Harga</p>
                  <p class="text-3xl font-extrabold tracking-tight text-terracotta leading-none">Rp {{ formatCurrency(displayPrice) }}</p>
                </div>
                <div class="rounded-xl bg-emerald-50/60 px-3.5 py-2 text-xs font-bold text-emerald-700 border border-emerald-500/10 shadow-sm">
                  Stok: <span class="font-extrabold">{{ selectedStock }}</span>
                </div>
              </div>

              <!-- Variants & Options -->
              <div class="space-y-4">
                <div v-if="variantOptions.length" class="mb-4">
                  <label class="block text-[10px] font-black uppercase tracking-wider text-[#8A7A6C] mb-2">Pilih Varian / Ukuran</label>
                  <div class="flex flex-wrap gap-2">
                    <button 
                      v-for="variant in variantOptions" 
                      :key="variant.value"
                      @click="selectedVariantId = variant.value"
                      class="px-4 py-2.5 rounded-xl border text-xs font-bold transition-all duration-200"
                      :class="selectedVariantId === variant.value ? 'border-terracotta bg-[#B85C38] text-white shadow-sm' : 'border-[#E8DCCB]/60 hover:border-terracotta/40 text-espresso bg-[#F8F1E7]/10 hover:bg-[#F8F1E7]/30'"
                    >
                      {{ variant.label }}
                    </button>
                  </div>
                </div>

                <!-- Quantity -->
                <div class="flex items-center gap-4 pb-4">
                  <div>
                    <label class="block text-[10px] font-black text-[#8A7A6C] mb-1.5 uppercase tracking-wider">Kuantitas</label>
                    <div class="w-fit flex items-center gap-1.5 rounded-xl border border-[#E8DCCB]/60 bg-white p-1 shadow-sm">
                      <button type="button" @click="decrementQuantity" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 text-muted transition hover:bg-slate-100 font-bold">-</button>
                      <input type="number" v-model.number="quantity" min="1" :max="selectedStock" class="w-10 border-none bg-transparent text-center text-xs font-black text-espresso p-0 focus:ring-0" />
                      <button type="button" @click="incrementQuantity" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 text-muted transition hover:bg-slate-100 font-bold">+</button>
                    </div>
                  </div>
                  <div class="text-[10px] text-[#8A7A6C]/60 pt-5">Maksimum {{ selectedStock }} pcs</div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col gap-2.5">
                  <div class="grid gap-2.5 sm:grid-cols-2">
                    <button
                      type="button"
                      @click="addToCart"
                      :disabled="!canAddToCart"
                      class="rounded-xl bg-[#2B1E16] px-5 py-3.5 text-xs font-black uppercase tracking-widest text-white transition hover:bg-black disabled:cursor-not-allowed disabled:opacity-60 shadow-sm active:scale-[0.98]"
                    >
                      Keranjang
                    </button>
                    <button
                      type="button"
                      @click="buyNow"
                      :disabled="!canAddToCart"
                      class="rounded-xl bg-terracotta px-5 py-3.5 text-xs font-black uppercase tracking-widest text-white transition hover:bg-terracottaDark disabled:cursor-not-allowed disabled:opacity-60 shadow-sm active:scale-[0.98]"
                    >
                      Beli Sekarang
                    </button>
                  </div>
                  
                  <button
                    type="button"
                    @click="toggleWishlist"
                    class="w-full rounded-xl border border-[#E8DCCB]/60 bg-[#FFFCF7] px-5 py-3 text-xs font-bold text-espresso transition hover:bg-[#F8F1E7]/20 hover:border-terracotta/30"
                  >
                    <span class="flex items-center justify-center gap-2">
                      <svg class="w-3.5 h-3.5" :class="isWishlisted ? 'fill-red-500 text-red-500' : 'fill-none text-slate-400'" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                      {{ isWishlisted ? 'Tersimpan di Wishlist' : 'Tambah ke Wishlist' }}
                    </span>
                  </button>
                </div>

                <!-- AR Try-On Banner -->
                <button
                  v-if="product?.category === 'batik' || product?.category === 'fashion'"
                  type="button"
                  @click="openArModal"
                  class="w-full relative overflow-hidden rounded-xl bg-gradient-to-br from-[#2B1E16] to-[#453124] px-4 py-3.5 text-left transition hover:shadow-md hover:-translate-y-0.5 group mt-2 shadow border border-amber-500/20 active:scale-[0.98]"
                >
                  <div class="absolute right-0 top-0 bottom-0 w-32 bg-white/5 skew-x-12 -mr-10 group-hover:bg-white/10 transition"></div>
                  <div class="flex items-center justify-between relative z-10">
                    <div class="flex items-center gap-2.5">
                      <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-[#D4AF37]">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                      </div>
                      <div>
                        <p class="text-white font-bold text-xs">Coba dengan AR Try-On</p>
                        <p class="text-white/60 text-[9px] mt-0.5">Lihat kecocokan produk di badan secara virtual</p>
                      </div>
                    </div>
                    <svg class="w-4 h-4 text-white/50 group-hover:text-white transition group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                  </div>
                </button>
              </div>
            </div>

            <!-- Seller Info (UMKM) - Integrated inside the right sidebar purchasing hub -->
            <div class="rounded-[2.25rem] bg-surface p-6 shadow-[0_4px_24px_rgba(0,0,0,0.02)] border border-borderSoft flex flex-col">
              <h3 class="text-xs font-black uppercase tracking-widest text-[#8A7A6C] mb-4">Informasi Pengrajin UMKM</h3>
              <div class="flex items-center gap-4 mb-5">
                <div class="w-14 h-14 rounded-full bg-white flex items-center justify-center text-slate-400 overflow-hidden border border-[#E8DCCB] shadow-sm flex-shrink-0">
                  <img v-if="product?.seller?.avatar_url" :src="product.seller.avatar_url" :alt="product.seller.name" class="w-full h-full object-cover" />
                  <img v-else :src="`https://api.dicebear.com/7.x/avataaars/svg?seed=${encodeURIComponent(product?.seller?.name || 'UMKM')}`" alt="Seller Avatar" class="w-full h-full object-cover" />
                </div>
                <div>
                  <h4 class="font-black text-espresso text-base flex items-center gap-1">
                    {{ product?.seller?.name }}
                    <svg class="w-4.5 h-4.5 text-blue-500 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a.75.75 0 00-.708-.523H4.5a2.5 2.5 0 00-2.5 2.5v1.07a.75.75 0 00.523.708L5.5 8.164v3.672L2.523 12.79A.75.75 0 002 13.5v1.07a2.5 2.5 0 002.5 2.5h1.059a.75.75 0 00.708-.523L8.164 14.5h3.672l.946 2.977a.75.75 0 00.708.523H14.5a2.5 2.5 0 002.5-2.5v-1.07a.75.75 0 00-.523-.708L14.5 11.836V8.164l2.977-.946a.75.75 0 00.523-.708V5.43a2.5 2.5 0 00-2.5-2.5h-1.059a.75.75 0 00-.708.523L11.836 5.5H8.164L7.218 2.523zM9 9.5a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z" clip-rule="evenodd"/></svg>
                  </h4>
                  <p class="text-xs text-muted flex items-center gap-1 mt-1">
                    <svg class="w-3 h-3 text-terracotta" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                    {{ product?.seller?.location }}
                  </p>
                </div>
              </div>
              
              <div class="grid grid-cols-2 gap-3 mb-6 bg-[#FFFCF7] rounded-2xl p-3.5 border border-[#E8DCCB]/60 shadow-sm">
                <div class="text-center">
                  <p class="text-[9px] uppercase font-black text-[#8A7A6C] tracking-wider mb-1">Rating UMKM</p>
                  <p class="text-sm font-black text-espresso flex items-center justify-center gap-1">
                    <svg class="w-4 h-4 text-amber-500 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.46a1 1 0 00-.363 1.118l1.287 3.95c.3.921-.755 1.688-1.54 1.118l-3.388-2.46a1 1 0 00-1.175 0l-3.388 2.46c-.784.57-1.838-.197-1.539-1.118l1.287-3.95a1 1 0 00-.363-1.118L2.098 9.377c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.95z"/></svg>
                    {{ product?.seller?.rating }}
                  </p>
                </div>
                <div class="text-center border-l border-[#E8DCCB]/40">
                  <p class="text-[9px] uppercase font-black text-[#8A7A6C] tracking-wider mb-1">Produk Terjual</p>
                  <p class="text-sm font-black text-espresso">{{ product?.seller?.sold }}+</p>
                </div>
              </div>

              <div class="grid grid-cols-2 gap-3 mt-auto">
                <button @click="visitShop" class="w-full py-2.5 rounded-xl border border-terracotta text-terracotta font-bold text-xs hover:bg-terracotta hover:text-white transition shadow-sm active:scale-95">Kunjungi Toko</button>
                <button @click="inviteCollaboration" class="w-full py-2.5 rounded-xl bg-[#2B1E16] text-white font-bold text-xs hover:bg-black transition shadow-sm active:scale-95">Ajak Kolaborasi</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Middle Section: Cultural Story Editorial & Customer Reviews (2-Column Layout) -->
        <div class="grid gap-8 grid-cols-[1.1fr_1fr] items-start mt-8">
          <!-- Left: Cultural Story Editorial Section -->
          <div class="rounded-[2.25rem] bg-surface p-8 shadow-[0_4px_24px_rgba(0,0,0,0.02)] border border-borderSoft space-y-6">
            <div class="border-b border-[#E8DCCB]/30 pb-4">
              <span class="text-[10px] font-black uppercase tracking-widest text-terracotta">Nilai Warisan Budaya</span>
              <h2 class="text-xl font-black text-espresso mt-1">Kisah, Filosofi & Material</h2>
            </div>
            
            <div class="space-y-6">
              <div>
                <h4 class="text-xs font-black text-espresso uppercase tracking-wider mb-2 flex items-center gap-1.5">
                  <span class="w-1.5 h-1.5 bg-terracotta rounded-full"></span>
                  Filosofi Budaya
                </h4>
                <p class="text-muted text-sm leading-relaxed text-justify">{{ product?.cultural_story }}</p>
              </div>
              
              <div v-if="product?.motif_meaning" class="border-t border-[#E8DCCB]/20 pt-4">
                <h4 class="text-xs font-black text-espresso uppercase tracking-wider mb-2 flex items-center gap-1.5">
                  <span class="w-1.5 h-1.5 bg-terracotta rounded-full"></span>
                  Makna Motif
                </h4>
                <p class="text-muted text-sm leading-relaxed text-justify">{{ product?.motif_meaning }}</p>
              </div>

              <div class="border-t border-[#E8DCCB]/20 pt-4">
                <h4 class="text-xs font-black text-espresso uppercase tracking-wider mb-2 flex items-center gap-1.5">
                  <span class="w-1.5 h-1.5 bg-terracotta rounded-full"></span>
                  Deskripsi & Detail Produk
                </h4>
                <p class="text-espresso text-sm leading-relaxed whitespace-pre-line">{{ product?.description }}</p>
              </div>

              <div class="border-t border-[#E8DCCB]/20 pt-4">
                <h4 class="text-xs font-black text-espresso uppercase tracking-wider mb-2 flex items-center gap-1.5">
                  <span class="w-1.5 h-1.5 bg-terracotta rounded-full"></span>
                  Material & Instruksi Perawatan
                </h4>
                <p class="text-muted text-sm leading-relaxed whitespace-pre-line bg-[#FFFCF7] p-4 rounded-2xl border border-[#E8DCCB]/40 font-medium">{{ product?.material_care }}</p>
              </div>
            </div>
          </div>

          <!-- Right: Customer Reviews & Ratings List -->
          <div class="rounded-[2.25rem] bg-surface p-8 shadow-[0_4px_24px_rgba(0,0,0,0.02)] border border-borderSoft flex flex-col space-y-6">
            <div class="mb-2 flex items-center justify-between border-b border-[#E8DCCB]/30 pb-4">
              <div>
                <span class="text-[10px] font-black uppercase tracking-widest text-[#8A7A6C]">Pendapat Pembeli</span>
                <h3 class="text-xl font-black text-espresso mt-1">Ulasan Pelanggan</h3>
                <p class="text-xs text-muted mt-1">{{ reviewCount }} ulasan terverifikasi</p>
              </div>
              <div class="flex items-center gap-2">
                <button
                  v-if="!hasUserReviewed"
                  @click="openReviewModal"
                  class="px-4 py-2.5 rounded-xl bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-700 transition active:scale-95 shadow-sm"
                >
                  Tulis Ulasan
                </button>
                <button
                  v-else
                  disabled
                  class="px-4 py-2.5 rounded-xl bg-slate-50 text-slate-400 font-bold text-xs cursor-not-allowed shadow-sm border border-slate-200"
                >
                  Sudah Diulas
                </button>
                <div class="flex items-center gap-1 bg-amber-50 px-3 py-1.5 rounded-full border border-amber-100 shadow-sm">
                  <svg class="w-4 h-4 text-amber-500 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.46a1 1 0 00-.363 1.118l1.287 3.95c.3.921-.755 1.688-1.54 1.118l-3.388-2.46a1 1 0 00-1.175 0l-3.388 2.46c-.784.57-1.838-.197-1.539-1.118l1.287-3.95a1 1 0 00-.363-1.118L2.098 9.377c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.95z"/></svg>
                  <span class="font-black text-amber-700 text-sm">{{ product?.rating?.toFixed(1) }}</span>
                </div>
              </div>
            </div>

            <!-- List of Reviews -->
            <div v-if="reviews.length" class="space-y-4 max-h-[500px] overflow-y-auto pr-1 hide-scrollbar">
              <div v-for="review in reviews.slice(0, 5)" :key="review.id" class="rounded-2xl border border-borderSoft bg-[#FFFCF7]/30 p-4 shadow-sm">
                <div class="flex items-center justify-between gap-4 mb-2">
                  <div class="flex items-center gap-2">
                    <img :src="getAvatarUrl(review)" class="w-8 h-8 rounded-full object-cover border border-borderSoft bg-white" />
                    <div>
                      <p class="text-sm font-bold text-espresso">{{ review.author ?? review.user_name ?? 'Pembeli' }}</p>
                      <p class="text-[10px] text-slate-400">{{ formatDate(review.date ?? review.created_at) }}</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-0.5 text-amber-400">
                    <template v-for="i in 5" :key="i">
                      <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor" :class="i <= (review.rating ?? 0) ? 'text-amber-400 fill-current' : 'text-slate-200 fill-none'"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.46a1 1 0 00-.363 1.118l1.287 3.95c.3.921-.755 1.688-1.54 1.118l-3.388-2.46a1 1 0 00-1.175 0l-3.388 2.46c-.784.57-1.838-.197-1.539-1.118l1.287-3.95a1 1 0 00-.363-1.118L2.098 9.377c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.95z"/></svg>
                    </template>
                  </div>
                </div>
                <p class="text-espresso text-sm leading-relaxed">{{ review.comment ?? review.message ?? 'Ulasan tidak tersedia.' }}</p>
                <img
                  v-if="review.image_url"
                  :src="review.image_url"
                  alt="Foto ulasan"
                  class="mt-3 w-full max-w-[220px] rounded-xl border border-borderSoft/60 object-cover shadow-sm"
                />
              </div>
            </div>
            <p v-else class="text-sm text-muted text-center py-8 bg-[#FFFCF7]/30 rounded-2xl border border-borderSoft/60 border-dashed">Belum ada ulasan untuk produk ini.</p>
          </div>
        </div>

        <!-- Related Products Section (Full Width) -->
        <div class="rounded-[2.25rem] bg-surface p-8 shadow-[0_4px_24px_rgba(0,0,0,0.02)] border border-borderSoft mt-8">
          <div class="flex items-center justify-between mb-6 border-b border-[#E8DCCB]/30 pb-4">
            <div>
              <span class="text-[10px] font-black uppercase tracking-widest text-terracotta">Rekomendasi Kami</span>
              <h3 class="text-xl font-black text-espresso mt-1">Produk Terkait</h3>
            </div>
            <button
              type="button"
              @click="refreshRelated"
              class="text-xs font-black uppercase tracking-widest text-terracotta hover:text-orange-700 bg-[#F8F1E7]/60 border border-terracotta/20 px-4 py-2 rounded-xl transition active:scale-95"
            >Refresh</button>
          </div>
          
          <div v-if="relatedProducts.length" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <router-link
              v-for="item in relatedProducts"
              :key="item.id"
              :to="getProductLink(item)"
              class="group block rounded-3xl border border-[#E8DCCB]/40 bg-[#FFFCF7]/30 p-4 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-lg hover:bg-white"
            >
              <div class="relative overflow-hidden rounded-2xl bg-white border border-[#E8DCCB]/20 aspect-square">
                <img :src="item.images?.[0] ?? item.image_url" :alt="item.name" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" />
              </div>
              <div class="mt-3.5">
                <h4 class="text-sm font-black text-espresso line-clamp-2 leading-snug group-hover:text-terracotta transition-colors duration-200 h-10">{{ item.name }}</h4>
                <div class="mt-2.5 flex items-center justify-between">
                  <p class="text-sm font-extrabold text-terracotta">Rp {{ formatCurrency(item.price) }}</p>
                  <span class="text-[9px] font-bold text-[#8A7A6C] bg-[#FAF6F0] px-2 py-0.5 rounded border border-borderSoft">{{ item.origin }}</span>
                </div>
              </div>
            </router-link>
          </div>
          <p v-else class="text-sm text-muted text-center py-8">Tidak ada produk terkait saat ini.</p>
        </div>
      </div>

      <!-- MOBILE VIEW (visible on small screens, hidden on lg and up) -->
      <div class="mobile-view-container block lg:hidden relative pt-[40px]">

        <!-- Hero Carousel Swiper -->
        <div class="relative w-full aspect-[4/5] overflow-hidden bg-slate-100">
          <div class="flex overflow-x-auto snap-x snap-mandatory scroll-smooth hide-scrollbar w-full h-full" ref="mobileImageContainer" @scroll="onMobileScroll">
            <div v-for="(img, idx) in galleryImages" :key="idx" class="w-full h-full flex-shrink-0 snap-start">
              <img :src="img" class="w-full h-full object-cover" />
            </div>
          </div>
          <!-- Floating Back & Share Action Buttons -->
          <button @click="router.push('/')" class="absolute top-4 left-4 z-20 w-9 h-9 flex items-center justify-center rounded-full bg-white/90 backdrop-blur shadow-sm border border-borderSoft/60 text-espresso active:scale-95 transition" title="Kembali">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
          </button>
          <button @click="shareProduct" class="absolute top-4 right-4 z-20 w-9 h-9 flex items-center justify-center rounded-full bg-white/90 backdrop-blur shadow-sm border border-borderSoft/60 text-espresso active:scale-95 transition" title="Bagikan">
            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 10.742l4.818-2.409m0 0a3 3 0 10-1.228-2.458l-4.818 2.409m1.228 2.458a3 3 0 11-1.228 2.458l4.818-2.409m-1.228-2.458a3 3 0 111.228 2.458"></path></svg>
          </button>
          <!-- Dot Indicators overlayed inside the image -->
          <div class="absolute bottom-6 left-0 right-0 flex justify-center gap-1.5 z-10">
            <button v-for="(img, idx) in galleryImages" :key="idx" 
              @click="scrollToImage(idx)"
              class="h-1.5 rounded-full transition-all duration-300 shadow-sm"
              :class="idx === activeImageIndex ? 'w-5 bg-terracotta' : 'w-1.5 bg-white/75'"
            ></button>
          </div>
          <!-- Image Counter Pill -->
          <div class="absolute bottom-4 right-4 rounded-full bg-slate-900/60 backdrop-blur-sm px-2.5 py-1 text-[9px] font-bold text-white tracking-wider z-10">
            {{ activeImageIndex + 1 }} / {{ galleryImages.length }}
          </div>
        </div>

        <!-- Main Product Card Sheet -->
        <div class="bg-white rounded-t-[2.25rem] -mt-6 relative z-20 px-5 pt-6 pb-28 shadow-[0_-12px_30px_rgba(0,0,0,0.04)] border-t border-borderSoft">
          <div class="space-y-4">
            <!-- Badges & Title -->
            <div>
              <div class="flex items-center gap-2 mb-2">
                <span class="px-2.5 py-0.5 text-[9px] font-bold text-terracotta bg-[#F8F1E7] border border-terracotta/20 rounded-md uppercase tracking-wider">
                  {{ product?.category }}
                </span>
                <span class="px-2.5 py-0.5 text-[9px] font-bold text-muted bg-slate-50 border border-slate-200/60 rounded-md uppercase tracking-wider">
                  {{ product?.origin }}
                </span>
              </div>
              <h1 class="text-xl font-black text-espresso leading-tight">{{ product?.name }}</h1>

              <!-- Star Rating Row -->
              <div class="flex items-center gap-2 mt-2.5">
                <div class="flex items-center gap-1 bg-amber-50 px-2 py-0.5 rounded-lg border border-amber-200/40">
                  <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.46a1 1 0 00-.363 1.118l1.287 3.95c.3.921-.755 1.688-1.54 1.118l-3.388-2.46a1 1 0 00-1.175 0l-3.388 2.46c-.784.57-1.838-.197-1.539-1.118l1.287-3.95a1 1 0 00-.363-1.118L2.098 9.377c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.95z"/></svg>
                  <span class="text-[11px] font-bold text-amber-700">{{ product?.rating?.toFixed(1) }}</span>
                </div>
                <span class="text-slate-200 text-xs">|</span>
                <span class="text-[11px] text-muted font-bold uppercase tracking-wider">{{ product?.seller?.sold }}+ Terjual</span>
                <span class="text-slate-200 text-xs">|</span>
                <span class="text-[11px] text-[#2B1E16] font-bold underline underline-offset-2">{{ reviewCount }} Ulasan</span>
              </div>
            </div>

            <!-- Price and Stock Row -->
            <div class="flex items-center justify-between py-3.5 border-t border-b border-slate-100">
              <div>
                <p class="text-[9px] uppercase font-bold text-slate-400 tracking-wider">Harga Utama</p>
                <p class="text-2xl font-black text-terracotta leading-none mt-1">Rp {{ formatCurrency(displayPrice) }}</p>
              </div>
              <div class="rounded-xl bg-emerald-50 px-3 py-1.5 text-[10px] font-bold text-emerald-700 border border-emerald-500/10">Stok: {{ selectedStock }}</div>
            </div>

            <!-- Glowing AR banner on Mobile -->
            <button
              v-if="product?.category === 'batik' || product?.category === 'fashion'"
              @click="openArModal"
              class="w-full relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#2B1E16] to-[#453124] p-4 text-left shadow-lg border border-amber-500/20 active:scale-[0.98] transition"
            >
              <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-amber-500/10 rounded-full blur-xl"></div>
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D4AF37] to-[#F3E5AB] flex items-center justify-center text-espresso shadow">
                    <svg class="w-5 h-5 text-[#2B1E16]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                  </div>
                  <div>
                    <h4 class="text-xs font-bold text-white flex items-center gap-1.5">
                      AI AR Virtual Try-On 
                      <span class="inline-flex h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    </h4>
                    <p class="text-[9px] text-white/60 mt-0.5">Coba produk secara virtual langsung ke tubuh Anda</p>
                  </div>
                </div>
                <svg class="w-4 h-4 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
              </div>
            </button>



            <!-- Variant selector -->
            <div v-if="variantOptions.length" class="pt-2">
              <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Pilih Ukuran / Varian</label>
              <div class="flex flex-wrap gap-2">
                <button 
                  v-for="variant in variantOptions" 
                  :key="variant.value"
                  @click="selectedVariantId = variant.value"
                  class="px-4 py-2.5 rounded-xl border text-xs font-bold transition-all duration-200 active:scale-95"
                  :class="selectedVariantId === variant.value ? 'border-terracotta bg-[#F8F1E7] text-terracotta shadow-sm' : 'border-slate-200 text-espresso bg-white'"
                >
                  {{ variant.label }}
                </button>
              </div>
            </div>

            <!-- Quantity stepper -->
            <div class="flex items-center justify-between pt-3 border-t border-slate-100 mt-2">
              <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kuantitas</label>
              <div class="flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white p-1 shadow-sm">
                <button type="button" @click="decrementQuantity" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 text-muted transition hover:bg-slate-100 font-bold">-</button>
                <input type="number" v-model.number="quantity" min="1" :max="selectedStock" class="w-10 border-none bg-transparent text-center text-xs font-bold text-espresso p-0 focus:ring-0" />
                <button type="button" @click="incrementQuantity" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 text-muted transition hover:bg-slate-100 font-bold">+</button>
              </div>
            </div>

            <!-- Collapsible Info Accordions -->
            <div class="space-y-2.5 pt-4">
              <!-- Description Accordion -->
              <div class="border border-slate-100 rounded-2xl overflow-hidden bg-slate-50/30">
                <button @click="toggleAccordion('description')" class="w-full px-4 py-3.5 flex items-center justify-between text-left font-bold text-xs text-espresso uppercase tracking-wider">
                  <span class="flex items-center gap-2">📝 Deskripsi Produk</span>
                  <svg class="w-4 h-4 text-muted transition-transform duration-300" :class="activeAccordion === 'description' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div v-show="activeAccordion === 'description'" class="px-4 pb-4 pt-1 text-xs text-muted leading-relaxed whitespace-pre-line border-t border-slate-100/50">
                  {{ product?.description }}
                </div>
              </div>

              <!-- Cultural Story Accordion -->
              <div class="border border-slate-100 rounded-2xl overflow-hidden bg-slate-50/30">
                <button @click="toggleAccordion('culture')" class="w-full px-4 py-3.5 flex items-center justify-between text-left font-bold text-xs text-espresso uppercase tracking-wider">
                  <span class="flex items-center gap-2">📜 Cerita Budaya & Filosofi</span>
                  <svg class="w-4 h-4 text-muted transition-transform duration-300" :class="activeAccordion === 'culture' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div v-show="activeAccordion === 'culture'" class="px-4 pb-4 pt-1.5 space-y-3.5 text-xs text-muted leading-relaxed border-t border-slate-100/50">
                  <div>
                    <h4 class="font-bold text-terracotta mb-1 uppercase tracking-wider text-[10px]">Filosofi Budaya</h4>
                    <p>{{ product?.cultural_story }}</p>
                  </div>
                  <div class="border-t border-slate-100/60 pt-2.5">
                    <h4 class="font-bold text-terracotta mb-1 uppercase tracking-wider text-[10px]">Makna Motif</h4>
                    <p>{{ product?.motif_meaning }}</p>
                  </div>
                </div>
              </div>

              <!-- Material Accordion -->
              <div class="border border-slate-100 rounded-2xl overflow-hidden bg-slate-50/30">
                <button @click="toggleAccordion('material')" class="w-full px-4 py-3.5 flex items-center justify-between text-left font-bold text-xs text-espresso uppercase tracking-wider">
                  <span class="flex items-center gap-2">👕 Material & Perawatan</span>
                  <svg class="w-4 h-4 text-muted transition-transform duration-300" :class="activeAccordion === 'material' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div v-show="activeAccordion === 'material'" class="px-4 pb-4 pt-1 text-xs text-muted leading-relaxed whitespace-pre-line border-t border-slate-100/50">
                  {{ product?.material_care }}
                </div>
              </div>

              <!-- Seller Accordion -->
              <div class="border border-slate-100 rounded-2xl overflow-hidden bg-slate-50/30">
                <button @click="toggleAccordion('seller')" class="w-full px-4 py-3.5 flex items-center justify-between text-left font-bold text-xs text-espresso uppercase tracking-wider">
                  <span class="flex items-center gap-2">🏪 Informasi Pengrajin (UMKM)</span>
                  <svg class="w-4 h-4 text-muted transition-transform duration-300" :class="activeAccordion === 'seller' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div v-show="activeAccordion === 'seller'" class="px-4 pb-4 pt-3.5 border-t border-slate-100/50 space-y-4">
                  <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-3">
                      <div class="w-11 h-11 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 overflow-hidden border border-slate-300/40 flex-shrink-0">
                        <img v-if="product?.seller?.avatar_url" :src="product.seller.avatar_url" :alt="product.seller.name" class="w-full h-full object-cover" />
                        <img v-else :src="`https://api.dicebear.com/7.x/avataaars/svg?seed=${encodeURIComponent(product?.seller?.name || 'UMKM')}`" alt="Seller Avatar" class="w-full h-full object-cover" />
                      </div>
                      <div>
                        <h4 class="font-bold text-espresso text-xs flex items-center gap-1">
                          {{ product?.seller?.name }}
                          <svg class="w-3.5 h-3.5 text-blue-500 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a.75.75 0 00-.708-.523H4.5a2.5 2.5 0 00-2.5 2.5v1.07a.75.75 0 00.523.708L5.5 8.164v3.672L2.523 12.79A.75.75 0 002 13.5v1.07a2.5 2.5 0 002.5 2.5h1.059a.75.75 0 00.708-.523L8.164 14.5h3.672l.946 2.977a.75.75 0 00.708.523H14.5a2.5 2.5 0 002.5-2.5v-1.07a.75.75 0 00-.523-.708L14.5 11.836V8.164l2.977-.946a.75.75 0 00.523-.708V5.43a2.5 2.5 0 00-2.5-2.5h-1.059a.75.75 0 00-.708.523L11.836 5.5H8.164L7.218 2.523zM9 9.5a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z" clip-rule="evenodd"/></svg>
                        </h4>
                        <p class="text-[10px] text-muted flex items-center gap-1 mt-0.5">
                          <svg class="w-3 h-3 text-terracotta" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                          {{ product?.seller?.location }}
                        </p>
                      </div>
                    </div>
                  </div>
                  <!-- Mobile Buttons Grid -->
                  <div class="grid grid-cols-2 gap-2 mt-3 pt-3 border-t border-slate-100/50">
                    <button @click="visitShop" class="w-full py-2 rounded-xl border border-terracotta text-terracotta font-bold text-[10px] active:bg-terracotta active:text-white transition text-center">Kunjungi Toko</button>
                    <button @click="inviteCollaboration" class="w-full py-2 rounded-xl bg-[#2B1E16] text-white font-bold text-[10px] active:bg-black transition text-center">Ajak Kolaborasi</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Customer Reviews on Mobile -->
            <div class="pt-6">
              <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Ulasan Pelanggan</h3>
                <div class="flex items-center gap-1 bg-amber-50 px-2 py-0.5 rounded-lg border border-amber-200/40">
                  <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.46a1 1 0 00-.363 1.118l1.287 3.95c.3.921-.755 1.688-1.54 1.118l-3.388-2.46a1 1 0 00-1.175 0l-3.388 2.46c-.784.57-1.838-.197-1.539-1.118l1.287-3.95a1 1 0 00-.363-1.118L2.098 9.377c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.95z"/></svg>
                  <span class="text-[10px] font-bold text-amber-700">{{ product?.rating?.toFixed(1) }}</span>
                </div>
              </div>
              <button
                v-if="!hasUserReviewed"
                @click="openReviewModal"
                class="w-full py-2.5 rounded-xl border border-emerald-600 text-emerald-600 font-bold text-xs hover:bg-emerald-50 transition active:scale-95 mb-3"
              >
                Tulis Ulasan
              </button>
              <button
                v-else
                disabled
                class="w-full py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-400 font-bold text-xs cursor-not-allowed mb-3"
              >
                Sudah Diulas
              </button>
              <div v-if="reviews.length" class="space-y-3">
                <div v-for="review in reviews.slice(0, 2)" :key="review.id" class="rounded-xl border border-slate-100 bg-slate-50/50 p-3.5">
                  <div class="flex items-center justify-between gap-4 mb-1.5">
                    <div class="flex items-center gap-2">
                      <img :src="getAvatarUrl(review)" class="w-6 h-6 rounded-full object-cover border border-slate-100 bg-slate-100" />
                      <div>
                        <p class="text-xs font-bold text-espresso leading-none">{{ review.author ?? review.user_name ?? 'Pembeli' }}</p>
                        <p class="text-[8px] text-slate-400 mt-0.5">{{ formatDate(review.date ?? review.created_at) }}</p>
                      </div>
                    </div>
                    <div class="flex items-center gap-0.5 text-amber-400">
                      <template v-for="i in 5" :key="i">
                        <svg class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor" :class="i <= (review.rating ?? 0) ? 'text-amber-400' : 'text-slate-200'"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.46a1 1 0 00-.363 1.118l1.287 3.95c.3.921-.755 1.688-1.54 1.118l-3.388-2.46a1 1 0 00-1.175 0l-3.388 2.46c-.784.57-1.838-.197-1.539-1.118l1.287-3.95a1 1 0 00-.363-1.118L2.098 9.377c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.95z"/></svg>
                      </template>
                    </div>
                  </div>
                  <p class="text-espresso text-xs leading-normal">{{ review.comment ?? review.message }}</p>
                  <img
                    v-if="review.image_url"
                    :src="review.image_url"
                    alt="Foto ulasan"
                    class="mt-2.5 w-full max-w-[180px] rounded-lg border border-slate-200 object-cover"
                  />
                </div>
              </div>
              <p v-else class="text-xs text-muted">Belum ada ulasan untuk produk ini.</p>
            </div>

            <!-- Related Products on Mobile -->
            <div class="pt-6">
              <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Produk Terkait</h3>
                <button type="button" @click="refreshRelated" class="text-xs font-bold text-terracotta">Refresh</button>
              </div>
              <div v-if="relatedProducts.length" class="flex overflow-x-auto gap-3 py-1.5 hide-scrollbar -mx-5 px-5">
                <router-link
                  v-for="item in relatedProducts"
                  :key="item.id"
                  :to="getProductLink(item)"
                  class="flex-shrink-0 w-36 block rounded-2xl border border-slate-100 bg-white p-3 transition active:scale-95 shadow-sm"
                >
                  <div class="aspect-square w-full rounded-xl overflow-hidden bg-slate-50">
                    <img :src="item.images?.[0] ?? item.image_url" :alt="item.name" class="h-full w-full object-cover" />
                  </div>
                  <div class="mt-2">
                    <h4 class="text-xs font-semibold text-espresso line-clamp-2 leading-tight h-8">{{ item.name }}</h4>
                    <p class="text-xs font-bold text-terracotta mt-1">Rp {{ formatCurrency(item.price) }}</p>
                  </div>
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="showArModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 p-4 overflow-y-auto">
      <div class="w-full max-w-md rounded-[2rem] bg-surface shadow-2xl overflow-hidden flex flex-col p-6 relative border border-borderSoft/60 animate-fade-in">
        <!-- Close Button -->
        <div class="absolute top-4 right-4 flex items-center gap-2 z-30">
          <button type="button" @click="closeArModal" class="rounded-full bg-slate-100 p-2 text-espresso hover:bg-slate-200 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
          </button>
        </div>

        <div class="mb-4 pr-20">
          <h2 class="text-xl font-black text-[#2B1E16] leading-none">AI AR Try-On</h2>
          <p class="text-muted text-xs mt-1.5 leading-relaxed">
            Posisikan tubuh Anda di depan kamera untuk melihat pakaian secara virtual.
          </p>
        </div>



        <!-- Live Camera / Upload Foto Canvas -->
        <div class="bg-slate-950 relative w-full aspect-[3/4] rounded-2xl overflow-hidden shadow-inner">
          <!-- Video Feed (Hidden, used as source for MediaPipe fallback) -->
          <video ref="videoElement" autoplay playsinline class="hidden"></video>
          
          <!-- Canvas for MediaPipe live camera & uploaded photo overlay -->
          <canvas ref="canvasElement" class="absolute inset-0 w-full h-full object-cover"></canvas>

          <!-- Loading State (Smooth scanning animation & pulsing ring) -->
          <div v-if="isInitializingAR" class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-black/75 backdrop-blur-sm text-white p-6 text-center">
            <!-- Pulsing Ring -->
            <div class="relative w-20 h-20 mb-6 flex items-center justify-center">
              <div class="absolute inset-0 rounded-full border-4 border-terracotta/30 animate-ping"></div>
              <div class="absolute w-16 h-16 rounded-full border-4 border-terracotta border-t-transparent animate-spin"></div>
              <svg class="w-8 h-8 text-terracotta" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
              </svg>
            </div>
            
            <!-- Scanning Line -->
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-transparent via-terracotta to-transparent opacity-80 animate-scan"></div>
            
            <p class="font-black text-sm uppercase tracking-wider text-[#F8F1E7]">Menyiapkan Fitur AR</p>
            <p class="text-slate-300 text-[10px] mt-2 max-w-[250px] leading-relaxed">
              Mengunduh modul deteksi postur AI dan menghubungkan perangkat kamera Anda...
            </p>
            
            <!-- Dynamic Tip -->
            <div class="mt-6 bg-[#FFFCF7]/10 border border-white/10 px-3 py-2 rounded-xl text-[9px] text-[#E8C97A] max-w-[240px]">
              <strong>Tips:</strong> Pastikan Anda berada di area dengan pencahayaan yang cukup.
            </div>
          </div>
          
          <div v-if="!isCameraReady && !isCameraDenied && !isInitializingAR && !mediaPipeLoadError && !arTimeoutActive" class="absolute inset-0 flex items-center justify-center z-10 bg-slate-950">
            <p class="text-white/80 text-xs font-semibold animate-pulse">Menghubungkan Kamera...</p>
          </div>

          <!-- Timeout or Loading Error Fallback -->
          <div v-if="(mediaPipeLoadError || arTimeoutActive) && !isInitializingAR" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center z-20 bg-slate-950/95 text-white animate-fade-in">
            <div class="w-10 h-10 rounded-full bg-amber-500/20 flex items-center justify-center text-amber-500 mb-3">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <p class="font-black text-sm mb-1">{{ mediaPipeLoadError ? 'Gagal Memuat Modul AR' : 'Koneksi AR Lambat' }}</p>
            <p class="text-slate-400 text-[10px] leading-relaxed mb-4 max-w-[280px]">
              {{ mediaPipeLoadError ? 'Modul AI tidak dapat diunduh karena kendala jaringan. Coba gunakan upload foto atau analisis gaya AI.' : 'Koneksi kamera atau modul AR memerlukan waktu lebih lama dari biasanya.' }}
            </p>
            
            <div class="flex flex-col gap-2 w-full max-w-[220px]">
              <button 
                type="button" 
                @click="triggerArFileUpload" 
                class="w-full py-2 bg-white text-espresso font-bold text-xs rounded-xl hover:bg-slate-100 transition active:scale-95 shadow text-[#2B1E16]"
              >
                Gunakan Upload Foto
              </button>
              <button 
                type="button" 
                @click="showAiStylingAnalysis" 
                class="w-full py-2 bg-gradient-to-r from-amber-500 to-terracotta text-white font-bold text-xs rounded-xl hover:opacity-90 transition active:scale-95 shadow-sm"
              >
                Coba AI Styling Analysis
              </button>
              <button 
                type="button" 
                @click="retryArConnection" 
                class="w-full py-2 bg-slate-800 text-white font-bold text-xs rounded-xl hover:bg-slate-700 transition active:scale-95 border border-slate-700"
              >
                Coba Hubungkan Ulang
              </button>
            </div>
          </div>
          
          <!-- Camera Denied State -->
          <div v-if="isCameraDenied && !mediaPipeLoadError && !arTimeoutActive" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center z-20 bg-slate-950/95 text-white animate-fade-in">
            <div class="w-10 h-10 rounded-full bg-red-500/20 flex items-center justify-center text-red-500 mb-3 animate-bounce">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <p class="font-black text-sm mb-1">Akses Kamera Diperlukan</p>
            <p class="text-slate-300 text-[10px] leading-relaxed mb-4 max-w-[280px]">
              Klik ikon **Gembok/Info (🔒)** di bilah alamat browser Anda (URL bar) lalu ubah opsi Kamera menjadi **Izinkan (Allow)**, kemudian muat ulang halaman.
            </p>
            
            <div class="flex flex-col gap-2 w-full max-w-[220px]">
              <button 
                type="button" 
                @click="triggerArFileUpload" 
                class="w-full py-2.5 bg-white text-espresso font-bold text-xs rounded-xl hover:bg-slate-100 transition active:scale-95 shadow text-[#2B1E16]"
              >
                Gunakan Upload Foto
              </button>
              <button 
                type="button" 
                @click="showAiStylingAnalysis" 
                class="w-full py-2.5 bg-gradient-to-r from-amber-500 to-terracotta text-white font-bold text-xs rounded-xl hover:opacity-90 transition active:scale-95 shadow-sm"
              >
                Coba AI Styling Analysis
              </button>
              <button 
                type="button" 
                @click="retryArConnection" 
                class="w-full py-2.5 bg-slate-800 text-white font-bold text-xs rounded-xl hover:bg-slate-700 transition active:scale-95 border border-slate-700"
              >
                Coba Akses Lagi
              </button>
            </div>
          </div>

          <!-- AI Styling Analysis Panel Overlay -->
          <div v-if="showStylingPanel" class="absolute inset-0 z-30 flex flex-col justify-between bg-[#1A110B]/95 p-5 text-white animate-fade-in">
            <div class="space-y-4 overflow-y-auto max-h-[85%] pr-1 hide-scrollbar">
              <div class="flex items-center gap-2 border-b border-[#E8DCCB]/25 pb-3">
                <span class="text-lg">✨</span>
                <h4 class="font-bold text-sm text-[#E8C97A] uppercase tracking-wider">AI Styling Analysis</h4>
              </div>
              <div class="text-[11px] leading-relaxed space-y-2 text-slate-200">
                <p class="font-bold text-white text-[12px] mb-1.5">{{ product?.name }}</p>
                <div v-html="renderMarkdown(getAiStylingTips)"></div>
              </div>
            </div>
            <button 
              type="button" 
              @click="showStylingPanel = false" 
              class="w-full py-2.5 bg-gradient-to-r from-amber-600 to-terracotta text-white font-bold text-xs rounded-xl hover:opacity-90 transition active:scale-95 mt-4"
            >
              Kembali ke Menu AR
            </button>
          </div>
        </div>

        <!-- AR Action Footer / Fallback Controls -->
        <div class="mt-4 flex items-center justify-between gap-3">
          <button 
            v-if="!isUploadedPhotoActive"
            type="button" 
            @click="triggerArFileUpload" 
            class="flex-1 py-3 px-4 rounded-xl border border-borderSoft text-espresso font-bold text-xs hover:bg-slate-50 transition active:scale-95 flex items-center justify-center gap-1.5 shadow-sm text-[#2B1E16] border-[#E8DCCB]"
          >
            <svg class="w-4 h-4 text-terracotta" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
            Upload Foto Fallback
          </button>
          
          <button 
            v-else
            type="button" 
            @click="resetArPhoto" 
            class="flex-1 py-3 px-4 rounded-xl bg-terracotta text-white font-bold text-xs hover:bg-terracottaDark transition active:scale-95 flex items-center justify-center gap-1.5 shadow-md shadow-terracotta/20"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a5 5 0 015 5v2M3 10l4-4M3 10l4 4" /></svg>
            Kembali ke Kamera Live
          </button>
        </div>

        <!-- Hidden input for file upload -->
        <input 
          ref="arFileInput" 
          type="file" 
          accept="image/*" 
          class="hidden" 
          @change="handleArFileUpload" 
        />

      </div>
    </div>
  </section>

  <!-- Mobile Sticky Bottom Bar (Visible only on mobile) -->
  <div class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-surface/95 backdrop-blur-md border-t border-borderSoft p-4 pb-6 flex items-center gap-3 shadow-[0_-10px_30px_rgba(0,0,0,0.06)]">
    <!-- Wishlist Button -->
    <button
      type="button"
      @click="toggleWishlist"
      class="w-12 h-12 rounded-xl border border-borderSoft flex items-center justify-center transition active:scale-95 bg-surface text-espresso"
      :class="isWishlisted ? 'text-red-500 border-red-200 bg-red-50/50' : 'text-espresso'"
    >
      <svg class="w-5 h-5" :class="isWishlisted ? 'fill-current' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
      </svg>
    </button>

    <!-- Tambah ke Keranjang -->
    <button
      type="button"
      @click="addToCart"
      :disabled="!canAddToCart"
      class="flex-1 py-3 bg-[#2B1E16] text-white rounded-xl font-bold text-xs uppercase tracking-widest transition active:scale-95 disabled:opacity-50"
    >
      + Keranjang
    </button>

    <!-- Beli Sekarang -->
    <button
      type="button"
      @click="buyNow"
      :disabled="!canAddToCart"
      class="flex-1 py-3 bg-terracotta text-white rounded-xl font-bold text-xs uppercase tracking-widest transition active:scale-95 disabled:opacity-50 shadow-md shadow-terracotta/20"
    >
      Beli Sekarang
    </button>
  </div>

  <!-- Beli Sekarang (Buy Now) Variation Modal -->
  <div v-if="showBuyNowModal" class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-[#2B1E16]/40 backdrop-blur-md p-0 sm:p-4">
    <!-- Backdrop Close Trigger -->
    <div class="absolute inset-0" @click="showBuyNowModal = false"></div>
    
    <!-- Modal Content -->
    <div class="relative w-full sm:max-w-md bg-surface rounded-t-[2rem] sm:rounded-3xl p-6 shadow-[0_25px_60px_-15px_rgba(43,30,22,0.25)] border-t sm:border border-borderSoft/60 overflow-hidden animate-slide-up sm:animate-fade-in flex flex-col max-h-[90vh]">
      <!-- Header -->
      <div class="flex items-center justify-between pb-4 border-b border-borderSoft mb-4">
        <h3 class="text-base font-black text-espresso uppercase tracking-wider">Beli Sekarang</h3>
        <button @click="showBuyNowModal = false" type="button" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-espresso hover:bg-slate-100 active:scale-95 transition-all duration-150">
          <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Product Preview -->
      <div class="flex gap-4 mb-6 bg-slate-50/50 p-3 rounded-2xl border border-borderSoft/40">
        <div class="w-20 h-20 bg-slate-100 rounded-xl overflow-hidden flex-shrink-0 border border-borderSoft/40">
          <img :src="product?.images?.[0] || '/images/placeholder-product.png'" class="w-full h-full object-cover" />
        </div>
        <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5">
          <h4 class="font-bold text-espresso line-clamp-1 leading-snug">{{ product?.name }}</h4>
          <div>
            <p class="text-lg font-extrabold text-terracotta leading-none">Rp {{ formatCurrency(displayPrice) }}</p>
            <p class="text-[10px] font-semibold text-slate-400 mt-1.5 flex items-center gap-1">
              <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
              Stok Tersedia: {{ selectedStock }}
            </p>
          </div>
        </div>
      </div>

      <!-- Variant Selector -->
      <div v-if="variantOptions.length" class="mb-6">
        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3 ml-1">Pilih Ukuran / Varian</label>
        <div class="flex flex-wrap gap-2">
          <button 
            v-for="variant in variantOptions" 
            :key="variant.value"
            @click="selectedVariantId = variant.value"
            class="px-4 py-2.5 rounded-xl border text-xs font-bold transition-all duration-200 active:scale-95"
            :class="selectedVariantId === variant.value ? 'border-terracotta bg-[#F8F1E7]/50 text-terracotta shadow-sm' : 'border-slate-200 text-espresso bg-white hover:border-slate-300'"
          >
            {{ variant.label }}
          </button>
        </div>
      </div>

      <!-- Quantity Stepper -->
      <div class="flex items-center justify-between pb-6 border-b border-borderSoft mb-6">
        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Jumlah</span>
        <div class="flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white p-1 shadow-sm">
          <button type="button" @click="decrementQuantity" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 text-muted transition hover:bg-slate-100 font-bold">-</button>
          <input type="number" v-model.number="quantity" min="1" :max="selectedStock" class="w-10 border-none bg-transparent text-center text-xs font-bold text-espresso p-0 focus:ring-0" />
          <button type="button" @click="incrementQuantity" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 text-muted transition hover:bg-slate-100 font-bold">+</button>
        </div>
      </div>

      <!-- Action Button -->
      <button 
        @click="confirmBuyNow"
        :disabled="quantity > selectedStock"
        class="w-full rounded-xl bg-terracotta text-white py-4 font-black text-xs uppercase tracking-widest hover:bg-terracottaDark transition active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-terracotta/20"
      >
        Lanjutkan ke Checkout
      </button>
    </div>
  </div>



  <!-- Review Submission Modal -->
  <div v-if="showReviewModal" class="fixed inset-0 z-50 flex items-center justify-center bg-[#2B1E16]/40 backdrop-blur-md p-4 animate-fade-in">
    <div class="w-full max-w-md bg-surface rounded-3xl p-6 shadow-[0_25px_60px_-15px_rgba(43,30,22,0.25)] border border-borderSoft/60 overflow-hidden relative">
      <!-- Close button -->
      <button @click="closeReviewModal" type="button" class="absolute top-4 right-4 rounded-full flex items-center justify-center text-slate-400 hover:text-espresso hover:bg-slate-100 active:scale-95 transition-all duration-150 z-30">
        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

      <h3 class="text-base font-black text-espresso mb-4 pb-2 border-b border-borderSoft uppercase tracking-wider">Tulis Ulasan Produk</h3>

      <!-- Product Summary in review modal -->
      <div class="flex gap-4 mb-5 bg-[#F8F1E7]/25 p-3 rounded-2xl border border-borderSoft/40">
        <div class="w-16 h-16 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0 border border-borderSoft/20">
          <img :src="product?.images?.[0] || '/images/placeholder-product.png'" class="w-full h-full object-cover" />
        </div>
        <div class="flex-1 min-w-0 flex flex-col justify-center">
          <h4 class="font-bold text-espresso line-clamp-1 leading-snug">{{ product?.name }}</h4>
          <p class="text-[10px] text-muted mt-1 uppercase tracking-wider font-bold text-terracotta">{{ product?.category }}</p>
        </div>
      </div>

      <form @submit.prevent="submitReview" class="space-y-4">
        <!-- Star selector -->
        <div>
          <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2.5 ml-1">Beri Bintang</label>
          <div class="flex gap-2">
            <button
              v-for="star in 5"
              :key="star"
              type="button"
              @click="reviewForm.rating = star"
              @mouseenter="hoveredStar = star"
              @mouseleave="hoveredStar = 0"
              class="text-2xl transition duration-150 hover:scale-125 focus:outline-none"
            >
              <svg
                class="w-8 h-8"
                :class="(hoveredStar ? star <= hoveredStar : star <= reviewForm.rating) ? 'text-amber-400 fill-current' : 'text-slate-200 fill-none'"
                stroke="currentColor"
                stroke-width="1.5"
                viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
              </svg>
            </button>
          </div>
          <p v-if="reviewFormError.rating" class="text-red-500 text-[10px] mt-1 font-bold">{{ reviewFormError.rating }}</p>
        </div>

        <!-- Comment input -->
        <div>
          <label for="comment" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2.5 ml-1">Komentar Ulasan</label>
          <textarea
            id="comment"
            v-model="reviewForm.comment"
            rows="4"
            maxlength="300"
            class="w-full rounded-2xl border border-borderSoft bg-slate-50/50 p-4 text-xs font-medium text-espresso placeholder:text-slate-400 focus:bg-white focus:border-terracotta focus:ring-1 focus:ring-terracotta transition"
            placeholder="Tuliskan pengalaman Anda menggunakan produk ini secara jujur dan detail..."
          ></textarea>
          <div class="flex justify-between mt-1.5 text-[10px] text-slate-400">
            <span class="text-red-500 font-bold" v-if="reviewFormError.comment">{{ reviewFormError.comment }}</span>
            <span v-else></span>
            <span>{{ reviewForm.comment.length }}/300 karakter</span>
          </div>
        </div>

        <div>
          <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2.5 ml-1">Foto Ulasan (Opsional)</label>
          <input
            ref="reviewImageInput"
            type="file"
            accept="image/*"
            @change="onReviewImageChange"
            class="block w-full text-xs text-espresso file:mr-3 file:rounded-lg file:border-0 file:bg-[#F8F1E7] file:px-3 file:py-2 file:font-bold file:text-terracotta hover:file:bg-[#f2e5d5]"
          />
          <div v-if="reviewImagePreview" class="mt-2">
            <img :src="reviewImagePreview" alt="Preview foto ulasan" class="w-28 h-28 rounded-xl object-cover border border-borderSoft" />
          </div>
          <p v-if="reviewFormError.image" class="text-red-500 text-[10px] mt-1 font-bold">{{ reviewFormError.image }}</p>
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          :disabled="isSubmittingReview"
          class="w-full rounded-xl bg-emerald-600 text-white py-3.5 font-bold text-xs uppercase tracking-widest hover:bg-emerald-700 transition active:scale-[0.98] disabled:opacity-60 flex items-center justify-center gap-2 shadow-lg shadow-emerald-600/10"
        >
          <svg v-if="isSubmittingReview" class="w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
          Kirim Ulasan
        </button>
      </form>
    </div>
  </div>
</div>
</template>

<script setup>
import { ref, computed, onMounted, watch, onBeforeUnmount } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useCartStore } from '@/stores/cartStore';
import { useNotificationStore } from '@/stores/notificationStore';
import { useWishlistStore } from '@/stores/wishlistStore';
import Navbar from '@/pages/landing/partials/Navbar.vue';
import { marked } from 'marked';

marked.setOptions({ gfm: true, breaks: true });
const renderMarkdown = (text) => marked.parse(text || '');

const route = useRoute();
const router = useRouter();
const cartStore = useCartStore();
const notificationStore = useNotificationStore();
const wishlistStore = useWishlistStore();

const user = ref(null);
const product = ref(null);
const isLoading = ref(true);
const error = ref('');
const quantity = ref(1);
const activeImageIndex = ref(0);
const showArModal = ref(false);
const showReviewModal = ref(false);
const relatedProducts = ref([]);
const variantOptions = ref([]);
const selectedVariantId = ref(null);
const activeTab = ref('Cerita Budaya');
const activeAccordion = ref('description');
const toggleAccordion = (name) => {
  activeAccordion.value = activeAccordion.value === name ? '' : name;
};

const mobileImageContainer = ref(null);
const onMobileScroll = (e) => {
  const width = e.target.offsetWidth;
  const scrollLeft = e.target.scrollLeft;
  if (width > 0) {
    activeImageIndex.value = Math.round(scrollLeft / width);
  }
};
const scrollToImage = (idx) => {
  activeImageIndex.value = idx;
  if (mobileImageContainer.value) {
    const width = mobileImageContainer.value.offsetWidth;
    mobileImageContainer.value.scrollTo({
      left: idx * width,
      behavior: 'smooth'
    });
  }
};

// AR Try-On State
const videoElement = ref(null);
const canvasElement = ref(null);
const arFileInput = ref(null);
const isUploadedPhotoActive = ref(false);
const uploadedPhotoSrc = ref(null);
const stream = ref(null);
const isCameraReady = ref(false);
const isCameraDenied = ref(false);
const isInitializingAR = ref(false);
const mediaPipeLoadError = ref(false);
const arTimeoutActive = ref(false);
const showStylingPanel = ref(false);

const getAiStylingTips = computed(() => {
  if (!product.value) return '';
  const category = product.value.category;
  const name = product.value.name;
  if (category === 'batik') {
    return `**Rekomendasi Padu Padan AI:**\n\n• **Bawahan:** Celana Chinos warna beige/khaki untuk kesan hangat, atau Celana Bahan hitam slim-fit untuk acara formal.\n• **Alas Kaki:** Loafers kulit cokelat gelap atau Oxford shoes hitam.\n• **Aksesoris:** Jam tangan kulit cokelat dengan strap minimalis untuk melengkapi motif heritage ${name}.`;
  } else if (category === 'kerajinan' || category === 'dekorasi') {
    return `**Rekomendasi Penempatan & Gaya:**\n\n• **Penempatan:** Letakkan di ruang tamu dengan pencahayaan hangat (warm white) untuk menonjolkan tekstur serat alamnya.\n• **Kombinasi:** Padukan dengan furnitur kayu jati atau tanaman indoor hijau (seperti Monstera) untuk nuansa tropis modern yang menyatu dengan keindahan ${name}.`;
  } else {
    return `**Rekomendasi Gaya AI:**\n\n• **Kombinasi:** Sangat serasi dipadukan dengan outfit bernuansa earthy-tone (seperti terakota, sage, atau putih gading).\n• **Acara:** Cocok digunakan pada acara kasual terhormat, gathering komunitas kreatif, atau sebagai hadiah bernilai seni tinggi.`;
  }
});

// Review state
const hoveredStar = ref(0);
const isSubmittingReview = ref(false);
const reviewForm = ref({ rating: 0, comment: '', image_url: '' });
const reviewFormError = ref({ rating: '', comment: '', image: '' });
const productReviews = ref([]);
const reviewImageInput = ref(null);
const reviewImageFile = ref(null);
const reviewImagePreview = ref('');

// MediaPipe State
let pose = null;
let camera = null;
let productImgObj = null;
let productImgBounds = null;

// Smoothing state untuk landmark tracking yang halus
let smoothedLandmarks = null;
const SMOOTH_FACTOR = 0.35; // 0 = no smoothing, 1 = full lag

const fetchJson = async (url) => {
  const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
  if (!res.ok) throw new Error('Gagal memuat data');
  const payload = await res.json();
  return payload?.data ?? payload;
};

const safeParseArray = (value) => {
  if (!value) return [];
  if (Array.isArray(value)) return value;
  try {
    return JSON.parse(value);
  } catch {
    return [value];
  }
};

const normalizeProduct = (raw) => {
  const item = raw?.product ?? raw;
  const images = safeParseArray(item.images ?? item.gallery ?? item.image_url ?? []).filter(Boolean);
  
  const price = item.price ? Number(item.price) : 349000;
  const category = item.category ?? 'batik';

  let mockVariants = [];
  if (category === 'batik' || category === 'fashion') {
    mockVariants = [
      { id: 1, name: 'S', price: price },
      { id: 2, name: 'M', price: price },
      { id: 3, name: 'L', price: price },
      { id: 4, name: 'XL', price: price }
    ];
  } else if (category === 'kerajinan' || category === 'dekorasi') {
    mockVariants = [
      { id: 1, name: 'Kecil (Small)', price: Math.round(price * 0.8) },
      { id: 2, name: 'Sedang (Medium)', price: price },
      { id: 3, name: 'Besar (Large)', price: Math.round(price * 1.25) }
    ];
  } else if (category === 'aksesoris') {
    mockVariants = [
      { id: 1, name: 'Standar (One Size)', price: price },
      { id: 2, name: 'Kustom (Custom Size)', price: Math.round(price * 1.15) }
    ];
  } else {
    mockVariants = [
      { id: 1, name: 'Standar', price: price }
    ];
  }
  
  const variants = mockVariants;
  const reviews = Array.isArray(item.reviews) ? item.reviews : [];

  const dbName = item.name ?? '';

  // Dynamic cultural stories based on name & category
  let culturalStory = 'Karya seni warisan nusantara hasil kurasi UMKM lokal terpilih dengan standar kualitas tinggi.';
  let motifMeaning = 'Setiap produk membawa identitas budaya lokal yang membanggakan.';
  let materialCare = 'Perawatan:\nIkuti petunjuk perawatan khusus pada label kemasan produk.';

  if (category === 'batik') {
    if (dbName.includes('Parang')) {
      culturalStory = 'Motif Parang dikenal sebagai simbol kekuatan, keteguhan, dan semangat yang terus bergerak maju. Motif ini banyak digunakan dalam karya batik Yogyakarta dan Solo.';
      motifMeaning = 'Garis diagonal pada motif Parang menggambarkan kesinambungan dan perjuangan. Filosofinya cocok untuk pengguna yang ingin membawa kesan kuat, elegan, dan berkarakter.';
    } else if (dbName.includes('Mega Mendung') || dbName.includes('MegaMendung')) {
      culturalStory = 'Motif Mega Mendung merupakan ikon batik Cirebon yang melambangkan ketenangan, kesabaran, dan kebijaksanaan seperti awan mendung yang menyejukkan.';
      motifMeaning = 'Bentuk awan berlapis melambangkan dunia atas yang membawa hujan pembawa berkah, serta kesabaran dalam menahan amarah.';
    } else if (dbName.includes('Lasem')) {
      culturalStory = 'Batik Lasem sangat dipengaruhi oleh akulturasi budaya Tionghoa dan Jawa pesisir, menghasilkan warna merah khas (merah darah ayam) yang legendaris.';
      motifMeaning = 'Motif burung phoenix (lokcan) melambangkan keabadian dan keberuntungan, berpadu dengan motif tumbuhan khas Jawa.';
    } else {
      culturalStory = 'Batik tradisional nusantara yang dibuat oleh pengrajin berpengalaman dengan melestarikan keahlian tulis/cap turun-temurun.';
      motifMeaning = 'Tiap goresan lilin malam pada kain ini memiliki makna filosofis mendalam tentang keseimbangan hidup dan keselarasan dengan alam.';
    }
    materialCare = 'Material:\nKatun premium dengan tekstur lembut dan nyaman digunakan.\n\nPerawatan:\nCuci manual dengan air dingin, hindari pemutih, dan jemur di tempat teduh agar warna tetap awet.';
  } else if (category === 'kerajinan') {
    culturalStory = 'Karya tenun ikat tradisional yang ditenun secara manual menggunakan alat tenun bukan mesin (ATBM) serta benang dengan pewarnaan alami.';
    motifMeaning = 'Motif geometris khas suku nusantara yang menceritakan tentang hubungan harmonis manusia dengan leluhur dan alam sekitar.';
    materialCare = 'Material:\nBenang tenun katun premium dengan pewarna alami.\n\nPerawatan:\nDry clean sangat disarankan, atau cuci tangan perlahan tanpa diperas keras.';
  } else if (category === 'aksesoris') {
    culturalStory = 'Aksesoris tradisional hasil karya seniman logam Kotagede dan Celuk yang dipahat dan dibentuk secara manual dengan ketelitian tinggi.';
    motifMeaning = 'Sentuhan motif ukiran klasik khas kerajaan nusantara yang melambangkan kemewahan, kehormatan, dan keanggunan.';
    materialCare = 'Material:\nPerak murni 925 / Alpaka berkualitas.\n\nPerawatan:\nHindari kontak langsung dengan parfum, simpan di wadah kedap udara, dan bersihkan dengan kain microfiber.';
  } else if (category === 'dekorasi') {
    culturalStory = 'Elemen interior rumah yang menggabungkan motif batik klasik dengan desain fungsional modern untuk memperindah ruang tinggal Anda.';
    motifMeaning = 'Simbol kehangatan keluarga dan estetika warisan budaya yang membawa aura ketenangan di dalam rumah.';
    materialCare = 'Material:\nKain katun batik premium tebal.\n\nPerawatan:\nCuci dengan deterjen lembut tanpa pemutih, setrika dengan suhu sedang.';
  }

  const sellerName = item.umkm_name ?? item.owner?.profile?.business_name ?? item.owner?.name ?? 'Sanggar Batik Laras';
  const sellerLocation = item.origin ?? 'Yogyakarta, Indonesia';

  return {
    id: item.id,
    name: dbName || 'Kemeja Batik Parang Heritage',
    price: price,
    description: item.description ?? 'Kemeja batik premium dengan motif klasik, dirancang untuk tampilan formal maupun casual. Dibuat dari kain berkualitas tinggi.',
    category: category,
    sku: item.sku ?? item.code ?? 'N/A',
    rating: item.rating ?? item.average_rating ?? 4.8,
    average_rating: item.average_rating ?? item.rating ?? 4.8,
    reviews,
    ar_model_url: item.ar_model_url,
    seller: {
      name: sellerName,
      location: sellerLocation,
      rating: item.owner?.rating ?? 4.9,
      sold: item.owner?.sold ?? item.sold ?? 12,
      avatar_url: item.owner?.profile?.avatar_url,
      id: item.owner?.id,
    },
    cultural_story: culturalStory,
    motif_meaning: motifMeaning,
    material_care: materialCare,
    artisan_info: `Dibuat oleh ${sellerName}, UMKM asal ${sellerLocation} yang berfokus pada pelestarian kerajinan nusantara dengan sentuhan desain modern.`,
    badges: item.badges ? (typeof item.badges === 'string' ? safeParseArray(item.badges) : item.badges) : ['Handmade', 'Premium', 'AR Ready'],
    images: images.length ? [images[0], images[0], images[0], images[0]] : ['/images/placeholder-product.png'],
    stock: Math.max(1, item.stock ?? item.quantity ?? 999),
    variants,
  };
};

const loadProduct = async () => {
  const rawId = route.params.id;
  // Extract numeric ID from format "slug-i.id" or use rawId if it's already just a number
  const match = String(rawId).match(/(?:-i\.)?(\d+)$/);
  const id = match ? match[1] : rawId;

  // If product is already loaded and has the same ID, just canonicalize URL if needed and return
  if (product.value && String(product.value.id) === String(id)) {
    const slug = product.value.name.toLowerCase()
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/(^-|-$)/g, '');
    const expectedPath = `/${slug}-i.${product.value.id}`;
    if (route.path !== expectedPath) {
      router.replace({ path: expectedPath, query: route.query });
    }
    return;
  }

  isLoading.value = true;
  error.value = '';

  try {
    const payload = await fetchJson(`/api/v1/products/${id}`);
    const normalized = normalizeProduct(payload);

    product.value = normalized;
    activeImageIndex.value = 0;
    variantOptions.value = normalized.variants.map((variant, idx) => {
      const labelParts = [variant.name, variant.size, variant.color].filter(Boolean);
      return {
        value: variant.id ?? idx,
        label: labelParts.length ? labelParts.join(' - ') : `Varian ${idx + 1}`,
        price: variant.price ?? normalized.price,
        stock: Math.max(1, variant.stock ?? normalized.stock ?? 999),
        raw: variant,
      };
    });

    // Do NOT auto-select a variant to force the user to choose their size/color
    selectedVariantId.value = null;

    // Load reviews
    try {
      const reviewsPayload = await fetchJson(`/api/v1/products/${id}/reviews`);
      const rawReviews = reviewsPayload.data ?? reviewsPayload ?? [];
      productReviews.value = rawReviews.map(r => ({
        id: r.id,
        user_id: r.user?.id,
        author: r.user?.name ?? 'Pembeli',
        avatar: r.user?.avatar_url ?? null,
        rating: r.rating,
        comment: r.comment ?? 'Ulasan tidak tersedia.',
        image_url: r.image_url ?? null,
        created_at: r.created_at
      }));
    } catch (err) {
      console.warn('Gagal memuat ulasan:', err);
      productReviews.value = [];
    }

    const related = await fetchJson('/api/v1/products');
    const list = Array.isArray(related) ? related : related.data ?? [];
    const relatedNames = ['Outer Batik Mega Mendung', 'Kemeja Batik Kawung', 'Scarf Batik Cirebon', 'Blouse Batik Lasem'];

    relatedProducts.value = list
      .filter((item) => item?.id && item.id !== normalized.id)
      .slice(0, 4)
      .map((item, idx) => {
        const norm = normalizeProduct(item);
        norm.name = relatedNames[idx] || norm.name;
        return norm;
      });

    // Canonicalize URL to Shopee-like format: /slug-i.id
    const slug = normalized.name.toLowerCase()
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/(^-|-$)/g, '');
    const expectedPath = `/${slug}-i.${normalized.id}`;
    if (route.path !== expectedPath) {
      router.replace({ path: expectedPath, query: route.query });
    }
  } catch (err) {
    console.error(err);
    error.value = 'Gagal memuat data produk. Silakan coba lagi.';
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

const getAvatarUrl = (review) => {
  if (review.avatar) return review.avatar;
  const name = encodeURIComponent(review.author || review.user_name || 'Pembeli');
  return `https://api.dicebear.com/7.x/avataaars/svg?seed=${name}`;
};

const galleryImages = computed(() => product.value?.images ?? []);
const activeImage = computed(() => galleryImages.value[activeImageIndex.value] ?? '');
const currentImageLabel = computed(() => `${activeImageIndex.value + 1}/${galleryImages.value.length}`);
const isWishlisted = computed(() => product.value ? wishlistStore.isWishlisted(product.value.id) : false);
const selectedVariant = computed(() => variantOptions.value.find((variant) => variant.value === selectedVariantId.value));
const displayPrice = computed(() => selectedVariant.value?.price ?? product.value?.price ?? 0);
const selectedStock = computed(() => {
  const stock = selectedVariant.value?.stock ?? product.value?.stock ?? 0;
  return Math.max(1, stock);
});
const reviews = computed(() => productReviews.value);
const reviewCount = computed(() => product.value?.reviews_count ?? productReviews.value.length);
const hasUserReviewed = computed(() => {
  return !!(user.value && productReviews.value.some(r => r.user_id === user.value.id));
});
const canAddToCart = computed(() => selectedStock.value > 0 && !isLoading.value);

const formatCurrency = (value) => {
  const number = Number(value ?? 0);
  return number.toLocaleString('id-ID');
};

const formatDate = (value) => {
  if (!value) return '-';
  const date = new Date(value);
  return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
};

const incrementQuantity = () => {
  const maxStock = selectedStock.value || product.value?.stock || 1;
  if (quantity.value < maxStock) {
    quantity.value += 1;
  }
};

const decrementQuantity = () => {
  if (quantity.value > 1) {
    quantity.value -= 1;
  }
};

const addToCart = async () => {
  if (!product.value) {
    notificationStore.error('Produk tidak ditemukan');
    return;
  }
  // Check if product has variants and user hasn't selected one
  if (variantOptions.value.length && selectedVariantId.value === null) {
    notificationStore.warning('Harap pilih varian/ukuran produk terlebih dahulu.');
    return;
  }
  const variant = selectedVariant.value?.label ?? null;
  const price = selectedVariant.value?.price ?? product.value.price;
  await cartStore.addItem(product.value, quantity.value, variant, price);
};

const showBuyNowModal = ref(false);

const buyNow = () => {
  if (!product.value) {
    notificationStore.error('Produk tidak ditemukan');
    return;
  }
  // Check if product has variants and user hasn't selected one
  if (variantOptions.value.length && selectedVariantId.value === null) {
    notificationStore.warning('Harap pilih varian/ukuran produk terlebih dahulu.');
    return;
  }
  showBuyNowModal.value = true;
};

const confirmBuyNow = async () => {
  showBuyNowModal.value = false;
  
  const token = localStorage.getItem('token');
  const userStored = localStorage.getItem('user');
  const isAuthenticated = !!(token && userStored);

  const selectedVariantLabel = selectedVariant.value?.label ?? '';

  if (!isAuthenticated) {
    notificationStore.info('Silakan login terlebih dahulu untuk melakukan checkout.');
    router.push({ 
      name: 'login', 
      query: { 
        redirect: `/checkout?source=buy-now&product_id=${product.value.id}&qty=${quantity.value}&variant=${encodeURIComponent(selectedVariantLabel)}` 
      } 
    });
    return;
  }

  // Save the single checkout item in sessionStorage
  const buyNowItem = {
    id: product.value.id,
    product_id: product.value.id,
    name: product.value.name,
    image: product.value.images?.[0] ?? '',
    variant: selectedVariantLabel || null,
    quantity: quantity.value,
    price: selectedVariant.value?.price ?? product.value.price
  };
  sessionStorage.setItem('buy_now_item', JSON.stringify(buyNowItem));

  router.push({ path: '/checkout', query: { source: 'buy-now' } });
};

const toggleWishlist = async () => {
  if (product.value) {
    await wishlistStore.toggleWishlist(product.value);
  }
};

const shareProduct = async () => {
  const shareData = {
    title: product.value.name,
    text: 'Lihat produk batik menarik di SnapFit Marketplace',
    url: window.location.href,
  };

  try {
    if (navigator.share) {
      await navigator.share(shareData);
      return;
    }
  } catch (err) {
    console.warn('Share API gagal:', err);
  }

  await navigator.clipboard.writeText(window.location.href);
  notificationStore.success('Link produk berhasil disalin ke clipboard.', 2000);
};

const refreshRelated = async () => {
  await loadProduct();
};

const visitShop = () => {
  if (product.value?.seller?.id) {
    router.push(`/shop/${product.value.seller.id}`);
  } else if (product.value?.seller?.name) {
    router.push({ path: '/', query: { search: product.value.seller.name } });
  }
};

const inviteCollaboration = async () => {
  const token = localStorage.getItem('token');
  
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

  // Don't collaborate with yourself
  if (product.value?.seller?.id === user.value.id) {
    notificationStore.warning('Anda tidak dapat memulai kolaborasi dengan diri sendiri.', 4000);
    return;
  }

  try {
    const sellerName = product.value?.seller?.name || 'UMKM';
    const roomName = `Kolaborasi: Desain Produk dengan ${sellerName}`;
    const description = `Ruang kerja kolaboratif untuk mendesain produk bersama ${sellerName} yang diinisiasi dari produk ${product.value?.name}.`;
    const inviteUserId = product.value?.seller?.id;

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
        invite_user_id: inviteUserId
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
    notificationStore.error(err.message || 'Gagal men-generate ruang kolaborasi.');
  }
};

/* ── Navigation ───────────────────────────────────────── */
const goToLogin   = () => router.push('/login');
const goToProfile = () => console.log('Navigate to profile');
const goToOrders  = () => router.push('/orders');

const logout = () => {
  localStorage.removeItem('user');
  localStorage.removeItem('token');
  user.value = null;
  router.push('/');
};



// Review Modal Functions
const openReviewModal = () => {
  if (!user.value) {
    notificationStore.info('Silakan login terlebih dahulu untuk menulis ulasan.');
    router.push({ name: 'login', query: { redirect: route.fullPath } });
    return;
  }
  reviewForm.value = { rating: 0, comment: '', image_url: '' };
  reviewFormError.value = { rating: '', comment: '', image: '' };
  reviewImageFile.value = null;
  reviewImagePreview.value = '';
  if (reviewImageInput.value) reviewImageInput.value.value = '';
  showReviewModal.value = true;
};

const closeReviewModal = () => {
  showReviewModal.value = false;
};

const onReviewImageChange = (event) => {
  reviewFormError.value.image = '';
  const file = event.target.files?.[0];
  if (!file) {
    reviewImageFile.value = null;
    reviewImagePreview.value = '';
    return;
  }

  if (!file.type.startsWith('image/')) {
    reviewFormError.value.image = 'File harus berupa gambar.';
    reviewImageFile.value = null;
    reviewImagePreview.value = '';
    return;
  }

  reviewImageFile.value = file;
  reviewImagePreview.value = URL.createObjectURL(file);
};

const uploadReviewImage = async () => {
  if (!reviewImageFile.value) return '';

  const token = localStorage.getItem('token');
  const formData = new FormData();
  formData.append('image', reviewImageFile.value);

  const res = await fetch('/api/v1/upload/review-image', {
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      'Authorization': `Bearer ${token}`,
    },
    body: formData,
  });

  const data = await res.json();
  if (!res.ok) throw new Error(data.message || 'Gagal upload gambar ulasan');
  return data.url || '';
};

const submitReview = async () => {
  reviewFormError.value = { rating: '', comment: '', image: '' };
  
  if (reviewForm.value.rating === 0) {
    reviewFormError.value.rating = 'Pilih rating bintang terlebih dahulu.';
    return;
  }
  
  if (!reviewForm.value.comment.trim()) {
    reviewFormError.value.comment = 'Silakan tulis komentar ulasan Anda.';
    return;
  }
  
  isSubmittingReview.value = true;
  try {
    const token = localStorage.getItem('token');
    const id = product.value?.id;
    let imageUrl = '';
    if (reviewImageFile.value) {
      imageUrl = await uploadReviewImage();
    }

    const payload = {
      ...reviewForm.value,
      image_url: imageUrl || reviewForm.value.image_url || ''
    };

    const response = await fetch(`/api/v1/products/${id}/reviews`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`
      },
      body: JSON.stringify(payload)
    });
    
    const data = await response.json();
    if (!response.ok) {
      if (response.status === 422 && data.errors) {
        reviewFormError.value.rating = data.errors.rating?.[0] || '';
        reviewFormError.value.comment = data.errors.comment?.[0] || '';
        reviewFormError.value.image = data.errors.image_url?.[0] || '';
      }
      throw new Error(data.message || 'Gagal mengirim ulasan');
    }
    
    notificationStore.success('Ulasan Anda berhasil dikirim!');
    closeReviewModal();
    await loadProduct();
    
    if (route.query.review) {
      const newQuery = { ...route.query };
      delete newQuery.review;
      router.replace({ query: newQuery });
    }
  } catch (err) {
    notificationStore.error(err.message || 'Gagal mengirim ulasan.');
  } finally {
    isSubmittingReview.value = false;
  }
};

// AR Try-On Functions
const openArModal = async () => {
  showArModal.value = true;
  mediaPipeLoadError.value = false;
  arTimeoutActive.value = false;
  showStylingPanel.value = false;
  isInitializingAR.value = true;
  isUploadedPhotoActive.value = false;
  uploadedPhotoSrc.value = null;
  smoothedLandmarks = null;

  // Set timeout max 20 detik untuk inisialisasi modul AR
  const initTimeout = setTimeout(() => {
    if (isInitializingAR.value && !isCameraReady.value) {
      console.warn('[AR] Inisialisasi terlalu lama.');
      arTimeoutActive.value = true;
      isInitializingAR.value = false;
      notificationStore.warning('Koneksi AR lambat. Anda dapat menggunakan opsi Upload Foto.');
    }
  }, 20000);

  try {
    await initProductImage();
    await loadMediaPipe();
    await startCamera();
    clearTimeout(initTimeout);
    isCameraReady.value = true;
    isInitializingAR.value = false;
    notificationStore.success('Kamera AR berhasil terhubung!');
  } catch (err) {
    console.error('AR Modal Init Failed:', err);
    isInitializingAR.value = false;
    clearTimeout(initTimeout);
    
    const errMsg = String(err?.message || err || '');
    if (errMsg.includes('Permission') || errMsg.includes('NotAllowed') || errMsg.includes('denied')) {
      isCameraDenied.value = true;
      notificationStore.error('Akses kamera ditolak. Izinkan kamera di pengaturan browser Anda.');
    } else {
      mediaPipeLoadError.value = true;
      notificationStore.error('Gagal memuat modul AR: ' + (errMsg.length > 100 ? errMsg.substring(0, 100) + '...' : errMsg));
    }
  }
};

const closeArModal = async () => {
  stopCamera();
  showArModal.value = false;
  isInitializingAR.value = false;
  isCameraReady.value = false;
  isCameraDenied.value = false;
  mediaPipeLoadError.value = false;
  arTimeoutActive.value = false;
  showStylingPanel.value = false;
};

const showAiStylingAnalysis = () => {
  showStylingPanel.value = true;
};



const retryArConnection = async () => {
  mediaPipeLoadError.value = false;
  arTimeoutActive.value = false;
  isInitializingAR.value = true;
  isUploadedPhotoActive.value = false;
  uploadedPhotoSrc.value = null;
  smoothedLandmarks = null;
  
  stopCamera();
  await openArModal();
};

const triggerArFileUpload = () => {
  arFileInput.value?.click();
};

const handleArFileUpload = (event) => {
  const file = event.target.files?.[0];
  if (!file) return;

  isInitializingAR.value = true;
  isUploadedPhotoActive.value = true;
  stopCamera(); // Turn off camera feed

  const reader = new FileReader();
  reader.onload = async (e) => {
    uploadedPhotoSrc.value = e.target.result;

    const img = new Image();
    img.onload = async () => {
      isInitializingAR.value = false;
      isCameraReady.value = true;
      isCameraDenied.value = false;

      await loadMediaPipe();

      if (!pose) {
        pose = new window.Pose({
          locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/pose/${file}`
        });
        pose.setOptions({
          modelComplexity: 0,
          smoothLandmarks: true,
          enableSegmentation: false,
          smoothSegmentation: false,
          minDetectionConfidence: 0.5,
          minTrackingConfidence: 0.5
        });
        pose.onResults(onPoseResults);
      }

      await pose.send({ image: img });
    };
    img.src = e.target.result;
  };
  reader.readAsDataURL(file);
  event.target.value = ''; // Reset input to allow uploading the same file
};

const resetArPhoto = async () => {
  isUploadedPhotoActive.value = false;
  uploadedPhotoSrc.value = null;
  smoothedLandmarks = null;
  isInitializingAR.value = true;
  await startCamera();
};

const calculateVisibleBounds = (canvas) => {
  const ctx = canvas.getContext('2d', { willReadFrequently: true });
  const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
  const data = imgData.data;
  
  let minX = canvas.width;
  let minY = canvas.height;
  let maxX = 0;
  let maxY = 0;
  
  for (let y = 0; y < canvas.height; y++) {
    for (let x = 0; x < canvas.width; x++) {
      const alpha = data[(y * canvas.width + x) * 4 + 3];
      if (alpha > 10) { // Piksel tidak transparan
        if (x < minX) minX = x;
        if (y < minY) minY = y;
        if (x > maxX) maxX = x;
        if (y > maxY) maxY = y;
      }
    }
  }
  
  if (maxX < minX || maxY < minY) {
    return { x: 0, y: 0, w: canvas.width, h: canvas.height };
  }
  
  return {
    x: minX,
    y: minY,
    w: maxX - minX + 1,
    h: maxY - minY + 1
  };
};

const initProductImage = () => {
  return new Promise((resolve) => {
    const baseImgUrl = product.value?.images?.[0];
    if (!baseImgUrl) { console.warn('[AR] Tidak ada gambar produk'); resolve(); return; }
    // Tambahkan cache buster agar browser tidak memakai cache non-CORS yang menodai kanvas (SecurityError)
    const imgUrl = baseImgUrl + (baseImgUrl.includes('?') ? '&' : '?') + 'ar_cb=' + Date.now();

    console.log('[AR] Memuat gambar produk:', imgUrl);

    // Coba load dengan crossOrigin untuk bisa processing pixel
    const img = new Image();
    img.crossOrigin = "anonymous";

    img.onload = () => {
      console.log('[AR] Gambar produk berhasil dimuat:', img.width, 'x', img.height);
      try {
        // Crop atas 15% untuk buang hanger
        const cropTop = Math.floor(img.height * 0.15);
        const croppedH = img.height - cropTop;

        const c = document.createElement('canvas');
        const ctx = c.getContext('2d', { willReadFrequently: true });
        c.width = img.width;
        c.height = croppedH;
        ctx.drawImage(img, 0, cropTop, img.width, croppedH, 0, 0, img.width, croppedH);

        // Test apakah bisa getImageData (CORS check)
        ctx.getImageData(0, 0, 1, 1);

        // Berhasil — lakukan background removal menggunakan BFS flood fill dengan toleransi saturasi ketat & penyaringan tepi (feathering) agar hasil sangat rapi
        const imageData = ctx.getImageData(0, 0, c.width, c.height);
        const d = imageData.data;
        const w = c.width;
        const h = c.height;

        const visited = new Uint8Array(w * h);
        const queue = [];

        // Helper untuk mendeteksi apakah pixel adalah bagian dari background/bayangan netral
        const isBgPixel = (x, y) => {
          const idx = (y * w + x) * 4;
          const r = d[idx];
          const g = d[idx + 1];
          const b = d[idx + 2];
          const brightness = (r + g + b) / 3;
          const saturation = Math.max(r, g, b) - Math.min(r, g, b);
          // Batasi saturasi secara ketat (< 18) agar tidak bocor melubangi pola kemeja yang hangat (beige/cream)
          // dan tingkat kecerahan di atas 50 untuk mencakup seluruh gradasi bayangan
          return saturation < 18 && brightness > 50;
        };

        // Inisialisasi BFS dari semua pixel di tepi gambar (border)
        for (let x = 0; x < w; x++) {
          if (isBgPixel(x, 0)) {
            const idx = x;
            if (!visited[idx]) { visited[idx] = 1; queue.push(idx); }
          }
          if (isBgPixel(x, h - 1)) {
            const idx = (h - 1) * w + x;
            if (!visited[idx]) { visited[idx] = 1; queue.push(idx); }
          }
        }
        for (let y = 0; y < h; y++) {
          if (isBgPixel(0, y)) {
            const idx = y * w;
            if (!visited[idx]) { visited[idx] = 1; queue.push(idx); }
          }
          if (isBgPixel(w - 1, y)) {
            const idx = y * w + (w - 1);
            if (!visited[idx]) { visited[idx] = 1; queue.push(idx); }
          }
        }

        // BFS flood fill untuk membuat background & bayangan di luar kemeja menjadi transparan
        let head = 0;
        const dx = [1, -1, 0, 0];
        const dy = [0, 0, 1, -1];

        while (head < queue.length) {
          const idx = queue[head++];
          const x = idx % w;
          const y = Math.floor(idx / w);

          d[idx * 4 + 3] = 0; // Transparan

          for (let i = 0; i < 4; i++) {
            const nx = x + dx[i];
            const ny = y + dy[i];
            if (nx >= 0 && nx < w && ny >= 0 && ny < h) {
              const nidx = ny * w + nx;
              if (!visited[nidx] && isBgPixel(nx, ny)) {
                visited[nidx] = 1;
                queue.push(nidx);
              }
            }
          }
        }

        // Pastikan area kemeja (yang tidak dikunjungi flood fill) tetap 100% solid
        for (let idx = 0; idx < w * h; idx++) {
          if (!visited[idx]) {
            d[idx * 4 + 3] = 255;
          }
        }

        // Simpan salinan channel alpha yang sudah dibersihkan
        const alphaCache = new Uint8Array(w * h);
        for (let idx = 0; idx < w * h; idx++) {
          alphaCache[idx] = d[idx * 4 + 3];
        }

        // Terapkan filter rata-rata 3x3 (feathering) pada channel alpha untuk memperlembut dan merapikan tepi kemeja
        for (let y = 1; y < h - 1; y++) {
          for (let x = 1; x < w - 1; x++) {
            const idx = y * w + x;
            if (alphaCache[idx] > 0) {
              let sum = 0;
              for (let ky = -1; ky <= 1; ky++) {
                for (let kx = -1; kx <= 1; kx++) {
                  sum += alphaCache[(y + ky) * w + (x + kx)];
                }
              }
              d[idx * 4 + 3] = Math.round(sum / 9);
            }
          }
        }
        ctx.putImageData(imageData, 0, 0);
        productImgObj = c;
        
        // Hitung bounding box baju riil tanpa margin transparan agar penempatan presisi
        productImgBounds = calculateVisibleBounds(c);
        console.log('[AR] Background removal & bounding box berhasil:', productImgBounds);
      } catch(e) {
        console.warn('[AR] CORS issue, pakai gambar mentah (tanpa bg removal):', e.message);
        productImgObj = img;
        productImgBounds = { x: 0, y: 0, w: img.width, h: img.height };
      }
      resolve();
    };

    img.onerror = () => {
      console.warn('[AR] Gagal load dengan CORS, coba tanpa crossOrigin...');
      const img2 = new Image();
      img2.onload = () => {
        console.log('[AR] Gambar berhasil dimuat tanpa CORS');
        productImgObj = img2;
        productImgBounds = { x: 0, y: 0, w: img2.width, h: img2.height };
        resolve();
      };
      img2.onerror = () => {
        console.error('[AR] Gambar produk gagal total dimuat');
        productImgObj = null;
        productImgBounds = null;
        resolve();
      };
      img2.src = imgUrl;
    };

    img.src = imgUrl;
  });
};

const loadScript = (src) => {
  return new Promise((resolve, reject) => {
    if (document.querySelector(`script[src="${src}"]`)) {
      resolve();
      return;
    }
    const script = document.createElement('script');
    script.src = src;
    script.crossOrigin = 'anonymous';
    script.onload = resolve;
    script.onerror = reject;
    document.head.appendChild(script);
  });
};

const loadScriptWithTimeout = (src, timeoutMs = 10000) => {
  return Promise.race([
    loadScript(src),
    new Promise((_, reject) => setTimeout(() => reject(new Error(`Timeout loading script: ${src}`)), timeoutMs))
  ]);
};

const loadMediaPipe = async () => {
  isInitializingAR.value = true;
  mediaPipeLoadError.value = false;
  try {
    await Promise.all([
      loadScriptWithTimeout('https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js', 10000),
      loadScriptWithTimeout('https://cdn.jsdelivr.net/npm/@mediapipe/pose/pose.js', 10000)
    ]);
  } catch (err) {
    console.error('Failed to load MediaPipe:', err);
    mediaPipeLoadError.value = true;
    isInitializingAR.value = false;
    notificationStore.error('Gagal memuat modul AI AR Try-On (Timeout CDN).');
    throw err;
  }
};

const onPoseResults = (results) => {
  const canvas = canvasElement.value;
  if (!canvas) return;
  
  const ctx = canvas.getContext('2d');
  
  if (canvas.width !== results.image.width) {
    canvas.width = results.image.width;
    canvas.height = results.image.height;
  }
  
  ctx.save();
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  
  // Mirror untuk selfie view jika bukan foto upload
  if (isUploadedPhotoActive.value) {
    ctx.drawImage(results.image, 0, 0, canvas.width, canvas.height);
  } else {
    ctx.translate(canvas.width, 0);
    ctx.scale(-1, 1);
    ctx.drawImage(results.image, 0, 0, canvas.width, canvas.height);
  }
  
  // Overlay baju jika landmark terdeteksi
  if (!productImgObj) {
    // Log sekali saja per 60 frame supaya tidak spam
    if (Math.random() < 0.02) console.warn('[AR] productImgObj belum tersedia');
  }
  if (results.poseLandmarks && productImgObj) {
    const lm = results.poseLandmarks;
    const lShoulder = lm[11];
    const rShoulder = lm[12];
    const lHip = lm[23];
    const rHip = lm[24];
    
    const shouldersVisible = lShoulder.visibility > 0.4 && rShoulder.visibility > 0.4;
    const hipsVisible = lHip.visibility > 0.4 && rHip.visibility > 0.4;
    
    if (shouldersVisible) {
      const lSx = lShoulder.x * canvas.width;
      const lSy = lShoulder.y * canvas.height;
      const rSx = rShoulder.x * canvas.width;
      const rSy = rShoulder.y * canvas.height;
      
      let torsoH;
      if (hipsVisible) {
        // Hitung tinggi torso dari landmark pinggul
        const lHy = lHip.y * canvas.height;
        const rHy = rHip.y * canvas.height;
        torsoH = ((lHy - lSy) + (rHy - rSy)) / 2;
      } else {
        // Fallback: estimasi tinggi torso = 1.8x lebar bahu
        const shoulderW = Math.hypot(lSx - rSx, lSy - rSy);
        torsoH = shoulderW * 1.8;
      }
      
      // Raw coordinates
      const raw = { lSx, lSy, rSx, rSy, torsoH };
      
      // EMA Smoothing — anti goyang/jitter
      if (!smoothedLandmarks) {
        smoothedLandmarks = { ...raw };
      } else {
        for (const key in raw) {
          smoothedLandmarks[key] = smoothedLandmarks[key] * SMOOTH_FACTOR + raw[key] * (1 - SMOOTH_FACTOR);
        }
      }
      const s = smoothedLandmarks;
      
      // Hitung dimensi badan
      const shoulderW = Math.hypot(s.lSx - s.rSx, s.lSy - s.rSy);
      const centerX = (s.lSx + s.rSx) / 2;
      const shoulderY = (s.lSy + s.rSy) / 2;
      const angle = Math.atan2(s.lSy - s.rSy, s.lSx - s.rSx);
      
      // Ambil bounding box baju yang valid (tanpa padding kosong)
      const bounds = productImgBounds || { x: 0, y: 0, w: productImgObj.width, h: productImgObj.height };
      const shirtAspect = bounds.w / bounds.h;

      // Ambil ukuran varian yang dipilih untuk menskalakan kemeja (S, M, L, XL)
      const sizeName = selectedVariant.value?.label ?? 'M';
      let sizeFactor = 1.0;
      if (sizeName.includes('S')) sizeFactor = 0.94;
      else if (sizeName.includes('XL')) sizeFactor = 1.12;
      else if (sizeName.includes('L')) sizeFactor = 1.06;
      else if (sizeName.includes('M')) sizeFactor = 1.00;

      // Sesuaikan tinggi baju berdasarkan tinggi torso, lalu lebar disesuaikan dengan aspek rasio kemeja asli agar pas di badan
      const clothH = s.torsoH * 1.22 * sizeFactor;
      const clothW = clothH * shirtAspect;
      
      // Simpan state sebelum transformasi lokal
      ctx.save();
      ctx.translate(centerX, shoulderY);
      ctx.rotate(angle);
      
      // Posisi Y: mulai sedikit di atas bahu (kerah baju)
      const drawY = -clothH * 0.14; // Dinaikkan agar menutupi kerah/leher asli dengan pas
      
      // 1. Efek Bayangan Lembut (Drop Shadow) agar baju terlihat menempel di badan dengan kedalaman nyata (3D Filter)
      ctx.shadowColor = 'rgba(0, 0, 0, 0.22)';
      ctx.shadowBlur = 15;
      ctx.shadowOffsetX = 0;
      ctx.shadowOffsetY = 8;
      
      // 2. Clipping Path untuk memotong lubang leher (Neck Cutout) secara dinamis agar dagu/leher asli pengguna tidak tertutup
      ctx.beginPath();
      // Kotak luar pembatas baju (diperlebar sedikit agar efek bayangan tidak terpotong)
      ctx.rect(-clothW / 2 - 40, drawY - 40, clothW + 80, clothH + 80);
      
      // Bentuk elips lubang leher di bagian atas tengah baju
      const neckRadiusX = shoulderW * 0.18; // Lebar lubang leher
      const neckRadiusY = shoulderW * 0.09; // Kedalaman lubang leher
      ctx.ellipse(0, drawY + neckRadiusY * 0.1, neckRadiusX, neckRadiusY, 0, 0, 2 * Math.PI, true);
      
      // Gunakan 'evenodd' untuk mengecualikan lubang leher dari penggambaran kemeja
      ctx.clip('evenodd');
      
      // Gambar kemeja dengan pemotongan presisi agar kerah pas di bahu/leher
      ctx.globalAlpha = 0.95;
      ctx.drawImage(
        productImgObj, 
        bounds.x, bounds.y, bounds.w, bounds.h, 
        -clothW / 2, drawY, clothW, clothH
      );
      
      // 3. Efek Pencahayaan Silinder (3D Cylindrical Lighting Overlay) agar baju terlihat memeluk lekuk tubuh bulat (3D wrap)
      const gradient = ctx.createLinearGradient(-clothW / 2, 0, clothW / 2, 0);
      gradient.addColorStop(0, 'rgba(0, 0, 0, 0.15)');          // Bayangan tepi kiri
      gradient.addColorStop(0.25, 'rgba(255, 255, 255, 0.08)');  // Sorotan dada kiri
      gradient.addColorStop(0.5, 'rgba(255, 255, 255, 0)');      // Tengah normal
      gradient.addColorStop(0.75, 'rgba(255, 255, 255, 0.05)');  // Sorotan dada kanan
      gradient.addColorStop(1, 'rgba(0, 0, 0, 0.18)');          // Bayangan tepi kanan
      
      // Gunakan 'source-atop' agar gradasi bayangan hanya menimpa area kemeja yang digambar
      ctx.globalCompositeOperation = 'source-atop';
      ctx.fillStyle = gradient;
      ctx.fillRect(-clothW / 2, drawY, clothW, clothH);
      
      // Kembalikan state transformasi dan clipping
      ctx.restore();
    }
  }
  
  ctx.restore();
};

const startCamera = async () => {
  isCameraReady.value = false;
  isCameraDenied.value = false;
  
  if (window.Pose && window.Camera) {
    try {
      if (!pose) {
        pose = new window.Pose({
          locateFile: (file) => {
            return `https://cdn.jsdelivr.net/npm/@mediapipe/pose/${file}`;
          }
        });
        pose.setOptions({
          modelComplexity: 0,
          smoothLandmarks: true,
          enableSegmentation: false,
          smoothSegmentation: false,
          minDetectionConfidence: 0.5,
          minTrackingConfidence: 0.5
        });
        pose.onResults(onPoseResults);
      }

      const video = videoElement.value;
      
      camera = new window.Camera(video, {
        onFrame: async () => {
          await pose.send({image: video});
        },
        width: 640,
        height: 480,
        facingMode: 'user'
      });
      
      await camera.start();
      isCameraReady.value = true;
      isInitializingAR.value = false;
    } catch (err) {
      console.error('Error starting AR Camera:', err);
      isCameraDenied.value = true;
      isInitializingAR.value = false;
      notificationStore.error('Gagal memulai modul AR.');
    }
  } else {
    // Fallback if MediaPipe failed to load
    try {
      const mediaStream = await navigator.mediaDevices.getUserMedia({ 
        video: { facingMode: 'user', width: 640, height: 480 } 
      });
      stream.value = mediaStream;
      if (videoElement.value) {
        videoElement.value.srcObject = mediaStream;
        videoElement.value.onplaying = () => {
          isCameraReady.value = true;
          isInitializingAR.value = false;
          
          // Simple loop to draw to canvas without AR if MediaPipe fails
          const drawFallback = () => {
            const canvas = canvasElement.value;
            const ctx = canvas.getContext('2d');
            canvas.width = videoElement.value.videoWidth;
            canvas.height = videoElement.value.videoHeight;
            ctx.save();
            ctx.translate(canvas.width, 0);
            ctx.scale(-1, 1);
            ctx.drawImage(videoElement.value, 0, 0, canvas.width, canvas.height);
            // Draw static product overlay
            if (productImgObj) {
              ctx.globalCompositeOperation = 'multiply';
              ctx.drawImage(productImgObj, canvas.width/4, canvas.height/4, canvas.width/2, (canvas.width/2) * (productImgObj.height/productImgObj.width));
            }
            ctx.restore();
            requestAnimationFrame(drawFallback);
          };
          drawFallback();
        };
      }
    } catch (err) {
      console.error('Error accessing camera:', err);
      isCameraDenied.value = true;
      isInitializingAR.value = false;
      notificationStore.error('Akses kamera ditolak atau perangkat tidak mendukung.');
    }
  }
};

const stopCamera = () => {
  if (camera) {
    camera.stop();
  }
  if (stream.value) {
    stream.value.getTracks().forEach(track => track.stop());
    stream.value = null;
  }
};



watch(
  () => route.params.id,
  async () => {
    closeArModal();
    await loadProduct();
  }
);

watch(
  () => route.query.review,
  (val) => {
    if (val === 'true') {
      openReviewModal();
    }
  }
);

onMounted(() => {
  const stored = localStorage.getItem('user');
  if (stored) user.value = JSON.parse(stored);
  loadProduct();
  if (route.query.review === 'true') {
    openReviewModal();
  }
});

onBeforeUnmount(() => {
  closeArModal();
});
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.hide-scrollbar::-webkit-scrollbar {
  display: none;
}
.hide-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

@media (max-width: 1023px) {
  .desktop-view-container {
    display: none !important;
  }
  .mobile-view-container {
    display: block !important;
  }
}
@media (min-width: 1024px) {
  .desktop-view-container {
    display: block !important;
  }
  .mobile-view-container {
    display: none !important;
  }
}

@keyframes spin-slow {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}
.animate-spin-slow {
  animation: spin-slow 8s linear infinite;
}
@keyframes pulse-slow {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.85; }
}
.animate-pulse-slow {
  animation: pulse-slow 3s ease-in-out infinite;
}
@keyframes scan {
  0% { top: 0%; }
  50% { top: 100%; }
  100% { top: 0%; }
}
.animate-scan {
  animation: scan 3s linear infinite;
}
</style>
