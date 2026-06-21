<template>
  <div class="snapfit-heritage-bg min-h-screen font-sans">
    <!-- Menggunakan Navbar bawaan SnapFit -->
    <Navbar :user="user" />

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-8 pt-20 md:pt-28 flex flex-col md:flex-row gap-6 lg:gap-8">
      <!-- Sidebar -->
      <aside class="w-full md:w-72 flex-shrink-0 space-y-6">
        <!-- Profile Card -->
        <div class="bg-surface rounded-2xl p-6 border border-borderSoft shadow-sm">
          <div class="flex items-center gap-4 mb-4">
            <div class="w-14 h-14 bg-gradient-to-tr from-orange-600 to-amber-400 rounded-full flex items-center justify-center text-xl font-bold text-white shadow-lg shadow-terracotta/20 flex-shrink-0 overflow-hidden border border-terracotta/30">
              <img :src="userAvatar" alt="Avatar" class="w-full h-full object-cover" />
            </div>
            <div class="flex-1 min-w-0">
              <h3 class="text-espresso font-bold text-base truncate">{{ user?.name || 'User' }}</h3>
              
            </div>
          </div>
        </div>

        <!-- Navigation Menu -->
        <div class="bg-surface rounded-2xl p-3 border border-borderSoft shadow-sm flex flex-col gap-1">
          <button v-for="menu in menus" :key="menu.id" 
            class="flex items-center gap-4 px-4 py-3.5 rounded-xl text-sm font-medium transition-colors text-left group"
            :class="activeMenu === menu.id ? 'bg-sand text-terracotta border border-terracotta/20' : 'text-muted hover:bg-slate-50 hover:text-espresso border border-transparent'"
            @click="activeMenu = menu.id"
          >
            <span v-html="menu.icon" class="transition-colors" :class="activeMenu === menu.id ? 'text-terracotta' : 'text-slate-400 group-hover:text-muted'"></span>
            {{ menu.label }}
          </button>
        </div>

        <!-- Logout Button -->
        <div class="bg-surface rounded-2xl p-3 border border-borderSoft shadow-sm">
          <button @click="logout" class="w-full flex items-center gap-4 px-4 py-3.5 rounded-xl text-sm font-bold text-red-500 hover:bg-red-50 transition-colors text-left group border border-transparent hover:border-red-100">
            <svg class="w-5 h-5 text-red-400 group-hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Keluar
          </button>
        </div>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 min-w-0">
        

        <!-- Form Area (Profil Saya) -->
        <div v-if="activeMenu === 'profile'" class="bg-surface rounded-[2rem] p-6 sm:p-8 border border-borderSoft shadow-sm">
          <div class="flex flex-col items-center mb-10">
            <div @click="triggerAvatarUpload" class="w-24 h-24 bg-gradient-to-tr from-orange-600 to-amber-400 rounded-full flex items-center justify-center text-4xl font-bold text-white shadow-xl shadow-terracotta/20 mb-4 relative group cursor-pointer border-4 border-white overflow-hidden">
              <img :src="userAvatar" alt="Avatar" class="w-full h-full object-cover" />
              <div class="absolute inset-0 bg-slate-900/40 rounded-full opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
              </div>
            </div>
            <input ref="avatarInput" type="file" accept="image/jpeg,image/png,image/webp" class="hidden" @change="uploadAvatar" />
            <button type="button" @click="triggerAvatarUpload" :disabled="isUploadingAvatar"
              class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-terracotta transition-colors disabled:opacity-50">
              {{ isUploadingAvatar ? 'Mengunggah...' : 'Ubah Foto' }}
            </button>
          </div>

          <form class="space-y-6 max-w-2xl mx-auto" @submit.prevent="updateProfile">
            <!-- Nama Lengkap -->
            <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-6">
              <label class="w-40 text-sm font-bold text-muted flex-shrink-0">Nama Lengkap</label>
              <input v-model="form.name" type="text" class="flex-1 bg-surface border border-borderSoft rounded-xl px-4 py-3 text-sm text-espresso font-medium focus:outline-none focus:border-terracotta focus:ring-2 focus:ring-terracotta/20 transition-all placeholder-slate-400" />
            </div>

            <!-- Email -->
            <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-6">
              <label class="w-40 text-sm font-bold text-muted flex-shrink-0">Email</label>
              <input v-model="form.email" type="email" readonly class="flex-1 bg-slate-50 border border-borderSoft rounded-xl px-4 py-3 text-sm text-slate-400 font-medium cursor-not-allowed" />
            </div>

            <!-- Jenis Kelamin -->
            <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-6">
              <label class="w-40 text-sm font-bold text-muted flex-shrink-0">Jenis Kelamin</label>
              <div class="flex-1 relative">
                <select v-model="form.gender" class="w-full bg-surface border border-borderSoft rounded-xl px-4 py-3 text-sm text-espresso font-medium focus:outline-none focus:border-terracotta focus:ring-2 focus:ring-terracotta/20 transition-all appearance-none cursor-pointer">
                  <option value="Laki-laki">Laki-laki</option>
                  <option value="Perempuan">Perempuan</option>
                  <option value="Lainnya">Lainnya</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none">
                  <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </div>
              </div>
            </div>

            <!-- Tanggal Lahir -->
            <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-6">
              <label class="w-40 text-sm font-bold text-muted flex-shrink-0">Tanggal Lahir</label>
              <input v-model="form.dob" type="date" class="flex-1 bg-surface border border-borderSoft rounded-xl px-4 py-3 text-sm text-espresso font-medium focus:outline-none focus:border-terracotta focus:ring-2 focus:ring-terracotta/20 transition-all cursor-pointer" />
            </div>

            <div class="pt-8 flex justify-end">
              <button :disabled="isSavingProfile" type="submit" class="bg-terracotta hover:bg-[#2B1E16] text-white font-bold py-3 px-6 rounded-xl transition-colors active:scale-[0.98] text-sm shadow-md shadow-terracotta/20 disabled:opacity-50">
                {{ isSavingProfile ? 'Menyimpan...' : 'Simpan Perubahan' }}
              </button>
            </div>
          </form>
        </div>

        <!-- Role & Workspace -->
        <div v-else-if="activeMenu === 'roles'" class="bg-surface rounded-[2rem] p-6 sm:p-10 border border-borderSoft shadow-sm animate-fade-in">
          <h3 class="text-2xl font-bold text-espresso mb-2">Workspace</h3>
          <p class="text-muted text-sm mb-8">Kelola peran Anda dan akses ke berbagai workspace di SnapFit.</p>
          
          <RoleWorkspaceMenu />
        </div>

        <!-- Pesanan Saya -->
        <div v-else-if="activeMenu === 'orders'" class="bg-surface rounded-[2rem] p-6 sm:p-10 border border-borderSoft shadow-sm min-h-[400px] animate-fade-in">
          <!-- Loading State -->
          <div v-if="isLoadingOrders" class="flex flex-col items-center justify-center min-h-[300px]">
            <div class="w-10 h-10 border-3 border-slate-200 border-t-slate-600 rounded-full animate-spin mb-4"></div>
            <p class="text-sm text-muted">Memuat pesanan...</p>
          </div>

          <!-- Empty State -->
          <div v-else-if="orders.length === 0" class="flex flex-col items-center justify-center text-center h-full pt-10">
            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mb-6 group hover:scale-110 transition-transform duration-300">
              <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
            </div>
            <h3 class="text-xl font-bold text-espresso mb-2">Belum ada pesanan</h3>
            <p class="text-muted max-w-sm mb-8 text-sm">Anda belum melakukan pemesanan apapun. Mari temukan karya UMKM dan desainer kreatif terbaik!</p>
            <button @click="router.push('/')" class="bg-slate-900 text-white px-8 py-3.5 rounded-xl font-bold text-sm hover:bg-terracotta transition-all duration-300 shadow-lg shadow-slate-900/10 hover:shadow-terracotta/20 active:scale-[0.98]">Mulai Belanja</button>
          </div>

          <!-- Orders List -->
          <div v-else>
            <h3 class="text-2xl font-bold text-espresso mb-6">Riwayat Pesanan</h3>
            <div class="space-y-4">
              <div v-for="order in orders" :key="order.id" class="border border-borderSoft rounded-2xl p-5 hover:border-slate-300 transition-colors bg-slate-50/50">
                <div class="flex justify-between items-center mb-4 border-b border-borderSoft pb-4">
                  <div>
                    <p class="text-[10px] text-slate-400 font-bold mb-1 uppercase tracking-widest">{{ new Date(order.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}</p>
                    <p class="text-sm font-bold text-espresso">{{ order.midtrans_order_id || `ORD-${order.id}` }}</p>
                  </div>
                  <div class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest"
                       :class="{
                         'bg-yellow-100 text-yellow-700': order.status === 'pending', 
                         'bg-blue-100 text-blue-700': order.status === 'paid',
                         'bg-purple-100 text-purple-700': order.status === 'shipped',
                         'bg-green-100 text-green-700': order.status === 'completed', 
                         'bg-red-100 text-red-700': order.status === 'cancelled'
                       }">
                    {{ statusLabel(order.status) }}
                  </div>
                </div>

                <!-- Shipping Status Stepper -->
                <div v-if="order.status !== 'cancelled'" class="mb-5 flex items-center justify-between px-2 sm:px-6 mt-4">
                  <div class="flex items-center w-full relative">
                    <!-- Step 1: Dibuat -->
                    <div class="flex flex-col items-center flex-1 relative z-10">
                      <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-extrabold transition-all duration-300 shadow-sm"
                           :class="['pending', 'paid', 'shipped', 'completed'].includes(order.status) ? 'bg-espresso text-white' : 'bg-slate-200 text-slate-400'">
                        1
                      </div>
                      <span class="text-[9px] font-bold mt-1 text-slate-500">Dibuat</span>
                    </div>

                    <!-- Line 1-2 -->
                    <div class="absolute left-[12.5%] right-[62.5%] top-[11px] h-[2px] -z-0 transition-all duration-300"
                         :class="['paid', 'shipped', 'completed'].includes(order.status) ? 'bg-espresso' : 'bg-slate-200'"></div>

                    <!-- Step 2: Diproses -->
                    <div class="flex flex-col items-center flex-1 relative z-10">
                      <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-extrabold transition-all duration-300 shadow-sm"
                           :class="['paid', 'shipped', 'completed'].includes(order.status) ? 'bg-espresso text-white' : 'bg-slate-200 text-slate-400'">
                        2
                      </div>
                      <span class="text-[9px] font-bold mt-1 text-slate-500">Diproses</span>
                    </div>

                    <!-- Line 2-3 -->
                    <div class="absolute left-[37.5%] right-[37.5%] top-[11px] h-[2px] -z-0 transition-all duration-300"
                         :class="['shipped', 'completed'].includes(order.status) ? 'bg-espresso' : 'bg-slate-200'"></div>

                    <!-- Step 3: Dikirim -->
                    <div class="flex flex-col items-center flex-1 relative z-10">
                      <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-extrabold transition-all duration-300 shadow-sm"
                           :class="['shipped', 'completed'].includes(order.status) ? 'bg-espresso text-white' : 'bg-slate-200 text-slate-400'">
                        3
                      </div>
                      <span class="text-[9px] font-bold mt-1 text-slate-500">Dikirim</span>
                    </div>

                    <!-- Line 3-4 -->
                    <div class="absolute left-[62.5%] right-[12.5%] top-[11px] h-[2px] -z-0 transition-all duration-300"
                         :class="order.status === 'completed' ? 'bg-espresso' : 'bg-slate-200'"></div>

                    <!-- Step 4: Selesai -->
                    <div class="flex flex-col items-center flex-1 relative z-10">
                      <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-extrabold transition-all duration-300 shadow-sm"
                           :class="order.status === 'completed' ? 'bg-espresso text-white' : 'bg-slate-200 text-slate-400'">
                        4
                      </div>
                      <span class="text-[9px] font-bold mt-1 text-slate-500">Selesai</span>
                    </div>
                  </div>
                </div>
                <!-- Order Items Preview -->
                <div v-if="order.items?.length" class="flex gap-2 mb-4 overflow-x-auto pb-2">
                  <div v-for="item in order.items.slice(0, 4)" :key="item.id" class="w-14 h-14 rounded-lg overflow-hidden bg-slate-100 flex-shrink-0 border border-borderSoft">
                    <img v-if="item.product?.image_url" :src="item.product.image_url" :alt="item.product?.name" class="w-full h-full object-cover" />
                    <div v-else class="w-full h-full flex items-center justify-center text-slate-300">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    </div>
                  </div>
                  <div v-if="order.items.length > 4" class="w-14 h-14 rounded-lg bg-slate-100 border border-borderSoft flex items-center justify-center flex-shrink-0">
                    <span class="text-xs font-bold text-muted">+{{ order.items.length - 4 }}</span>
                  </div>
                </div>

                <div class="flex justify-between items-end">
                  <div>
                    <p class="text-xs font-semibold text-muted mb-1">{{ order.items?.length || 0 }} Produk</p>
                    <p class="font-black text-espresso text-lg">{{ formatPrice(order.total_amount) }}</p>
                  </div>
                  <button @click="viewOrderDetail(order)" class="text-[11px] bg-surface border border-borderSoft text-espresso font-bold px-4 py-2 rounded-lg hover:bg-slate-50 hover:text-terracotta transition-colors">Detail Pesanan</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Keranjang -->
        <div v-else-if="activeMenu === 'cart'" class="bg-surface rounded-[2rem] p-6 sm:p-10 border border-borderSoft shadow-sm min-h-[400px] animate-fade-in">
          <!-- Loading State -->
          <div v-if="isLoadingCart" class="flex flex-col items-center justify-center min-h-[300px]">
            <div class="w-10 h-10 border-3 border-slate-200 border-t-slate-600 rounded-full animate-spin mb-4"></div>
            <p class="text-sm text-muted">Memuat keranjang...</p>
          </div>

          <!-- Empty State -->
          <div v-else-if="cartItems.length === 0" class="flex flex-col items-center justify-center text-center min-h-[300px]">
            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mb-6 group hover:scale-110 transition-transform duration-300">
              <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            </div>
            <h3 class="text-xl font-bold text-espresso mb-2">Keranjang Anda kosong</h3>
            <p class="text-muted max-w-sm mb-8 text-sm">Kelihatannya Anda belum menambahkan apapun ke keranjang. Yuk cari inspirasi desain!</p>
            <button @click="router.push('/')" class="bg-slate-900 text-white px-8 py-3.5 rounded-xl font-bold text-sm hover:bg-terracotta transition-all duration-300 shadow-lg shadow-slate-900/10 hover:shadow-terracotta/20 active:scale-[0.98]">Cari Produk</button>
          </div>

          <!-- Cart Items -->
          <div v-else>
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-2xl font-bold text-espresso">Keranjang Saya</h3>
              <span class="text-sm text-muted">{{ cartItems.length }} item</span>
            </div>

            <div class="space-y-4 mb-8">
              <div v-for="item in cartItems" :key="item.id" class="flex gap-4 p-4 border border-borderSoft rounded-2xl bg-slate-50/50 hover:border-slate-300 transition-colors group">
                <!-- Product Image -->
                <div class="w-20 h-20 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0">
                  <img v-if="item.product?.images?.length" :src="item.product.images[0]" :alt="item.product.name" class="w-full h-full object-cover" />
                  <div v-else class="w-full h-full flex items-center justify-center text-slate-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                  </div>
                </div>

                <!-- Product Info -->
                <div class="flex-1 min-w-0">
                  <h4 class="text-sm font-bold text-espresso truncate">{{ item.product?.name || 'Produk tidak tersedia' }}</h4>
                  <p v-if="item.variant" class="text-xs text-muted mt-0.5">Varian: {{ item.variant }}</p>
                  <p class="text-sm font-black text-espresso mt-2">{{ formatPrice(item.product?.price || 0) }}</p>
                </div>

                <!-- Quantity Controls -->
                <div class="flex flex-col items-end justify-between">
                  <button @click="removeCartItem(item.id)" class="text-slate-300 hover:text-red-500 transition-colors p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                  </button>
                  <div class="flex items-center gap-2 bg-white border border-borderSoft rounded-lg">
                    <button @click="updateCartQuantity(item.id, item.quantity - 1)" :disabled="item.quantity <= 1" class="w-7 h-7 flex items-center justify-center text-slate-400 hover:text-espresso disabled:opacity-30 transition-colors">
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4" /></svg>
                    </button>
                    <span class="text-xs font-bold text-espresso w-5 text-center">{{ item.quantity }}</span>
                    <button @click="updateCartQuantity(item.id, item.quantity + 1)" class="w-7 h-7 flex items-center justify-center text-slate-400 hover:text-espresso transition-colors">
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Cart Summary -->
            <div class="border-t border-borderSoft pt-6">
              <div class="flex justify-between items-center mb-4">
                <span class="text-sm text-muted">Total</span>
                <span class="text-xl font-black text-espresso">{{ formatPrice(cartTotal) }}</span>
              </div>
              <button @click="router.push('/checkout')" class="w-full bg-slate-900 text-white py-4 rounded-xl font-bold text-sm hover:bg-terracotta transition-all duration-300 shadow-lg shadow-slate-900/10 hover:shadow-terracotta/20 active:scale-[0.98]">
                Checkout ({{ cartItems.length }} item)
              </button>
            </div>
          </div>
        </div>

        <!-- Wishlist -->
        <div v-else-if="activeMenu === 'wishlist'" class="bg-surface rounded-[2rem] p-6 sm:p-10 border border-borderSoft shadow-sm min-h-[400px] animate-fade-in">
          <div v-if="wishlistStore.items.length === 0" class="flex flex-col items-center justify-center text-center pt-10">
            <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center text-red-500 mb-6 group hover:scale-110 transition-transform duration-300">
              <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
            </div>
            <h3 class="text-xl font-bold text-espresso mb-2">Wishlist masih kosong</h3>
            <p class="text-muted max-w-sm mb-8 text-sm">Simpan produk atau desain favorit Anda di sini agar mudah ditemukan kembali nanti.</p>
            <button @click="router.push('/')" class="bg-slate-900 text-white px-8 py-3.5 rounded-xl font-bold text-sm hover:bg-red-500 transition-all duration-300 shadow-lg shadow-slate-900/10 hover:shadow-red-500/20 active:scale-[0.98]">Eksplorasi Sekarang</button>
          </div>
          <div v-else>
            <h3 class="text-2xl font-bold text-espresso mb-6">Wishlist Tersimpan</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
              <div v-for="item in wishlistStore.items" :key="item.product_id" @click="router.push(`/product/${item.product_id}`)" class="group bg-surface border border-borderSoft rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 cursor-pointer flex flex-col relative">
                <div class="h-48 bg-slate-100 overflow-hidden relative">
                  <!-- Fallback to placeholder if parsing fails or no image -->
                  <img :src="(item.product?.images && typeof item.product.images === 'string' && item.product.images.startsWith('[')) ? JSON.parse(item.product.images)[0] : (item.product?.images?.[0] || '/images/placeholder.png')" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                </div>
                <button type="button" @click.stop="wishlistStore.toggleWishlist(item.product)" class="absolute top-3 right-3 z-10 w-8 h-8 bg-surface/90 backdrop-blur-sm rounded-full flex items-center justify-center text-red-500 shadow-sm hover:bg-red-500 hover:text-white transition-colors">
                  <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                </button>
                <div class="p-5 flex flex-col flex-1">
                  <div class="flex items-center gap-2 mb-2 opacity-70">
                    <img :src="`https://api.dicebear.com/7.x/avataaars/svg?seed=${item.product?.owner?.name || 'UMKM'}&backgroundColor=f1f5f9`" class="w-4 h-4 rounded-full" />
                    <p class="text-[10px] font-black uppercase tracking-widest text-muted">{{ item.product?.owner?.profile?.full_name || item.product?.owner?.name || 'UMKM' }}</p>
                  </div>
                  <h4 class="font-bold text-espresso leading-snug mb-1 line-clamp-2">{{ item.product?.title || item.product?.name }}</h4>
                  <div class="mt-auto pt-3 flex items-center justify-between">
                    <p class="text-terracotta font-black text-lg">{{ formatPrice(item.product?.price || 0) }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Pengaturan -->
        <div v-else-if="activeMenu === 'settings'" class="bg-surface rounded-[2rem] p-6 sm:p-10 border border-borderSoft shadow-sm animate-fade-in">
          <h3 class="text-2xl font-bold text-espresso mb-2">Pengaturan Akun</h3>
          <p class="text-muted text-sm mb-8">Kelola preferensi dan keamanan akun Anda.</p>
          
          <div class="space-y-2">
            <!-- Toggles -->
            <div class="flex items-center justify-between py-5 border-b border-borderSoft group">
              <div>
                <h4 class="font-bold text-espresso text-sm group-hover:text-terracotta transition-colors">Notifikasi Email</h4>
                <p class="text-xs text-muted mt-1">Terima email untuk pembaruan pesanan dan promo eksklusif.</p>
              </div>
              <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" v-model="settings.emailNotification" @change="saveSettings" class="sr-only peer">
                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-surface after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-terracotta"></div>
              </label>
            </div>
            
            <div class="flex items-center justify-between py-5 border-b border-borderSoft group">
              <div>
                <h4 class="font-bold text-espresso text-sm group-hover:text-terracotta transition-colors">Autentikasi Dua Faktor (2FA)</h4>
                <p class="text-xs text-muted mt-1">Amankan akun Anda dengan verifikasi dua langkah ekstra.</p>
              </div>
              <button @click="toggle2FA" class="text-xs font-bold px-5 py-2.5 rounded-xl transition-all duration-300 active:scale-[0.98]"
                :class="settings.twoFactorAuth ? 'bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-100' : 'text-terracotta bg-sand border border-terracotta/20 hover:bg-terracotta hover:text-white'">
                {{ settings.twoFactorAuth ? 'Telah Aktif' : 'Aktifkan' }}
              </button>
            </div>

            <!-- Danger Zone -->
            <div class="pt-8">
              <h4 class="font-bold text-red-600 text-sm mb-2 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                Zona Berbahaya
              </h4>
              <p class="text-xs text-muted mb-5">Setelah Anda menghapus akun, proses ini tidak dapat dibatalkan dan semua data akan hilang.</p>
              <button @click="confirmDeleteAccount" class="text-xs font-bold text-red-500 border border-red-200 bg-surface px-6 py-3 rounded-xl hover:bg-red-500 hover:text-white hover:border-red-500 transition-all duration-300 active:scale-[0.98]">Hapus Akun Permanen</button>
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- Custom Delete Account Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#2B1E16]/40 backdrop-blur-md animate-fade-in">
      <div class="bg-surface rounded-3xl w-full max-w-md p-6 sm:p-8 shadow-[0_25px_60px_-15px_rgba(43,30,22,0.25)] border border-borderSoft transform scale-100 transition-transform">
        <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center text-red-500 mb-6 mx-auto">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        </div>
        
        <h3 class="text-xl font-black text-espresso text-center mb-2">Hapus Akun Permanen?</h3>
        <p class="text-sm text-muted text-center mb-6 leading-relaxed">Tindakan ini tidak dapat dibatalkan. Semua data profil, pesanan, dan wishlist Anda akan terhapus secara permanen.</p>
        
        <div class="mb-6">
          <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider text-center">Ketik <span class="text-red-500 font-black">HAPUS</span> untuk melanjutkan</label>
          <input v-model="deleteConfirmationText" type="text" placeholder="HAPUS" class="w-full text-center bg-slate-50 border border-borderSoft rounded-xl px-4 py-3 text-sm text-espresso font-bold focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-500/20 transition-all uppercase" />
        </div>
        
        <div class="flex gap-3">
          <button @click="showDeleteModal = false; deleteConfirmationText = ''" class="flex-1 px-4 py-3 rounded-xl font-bold text-sm text-espresso bg-slate-100 hover:bg-slate-200 transition-colors">Batal</button>
          <button @click="processDeleteAccount" :disabled="deleteConfirmationText !== 'HAPUS'" class="flex-1 px-4 py-3 rounded-xl font-bold text-sm text-white transition-all shadow-lg" :class="deleteConfirmationText === 'HAPUS' ? 'bg-red-500 hover:bg-red-600 shadow-red-500/20' : 'bg-red-300 cursor-not-allowed shadow-none'">Ya, Hapus</button>
        </div>
      </div>
    </div>

    <!-- 2FA Setup Modal -->
    <div v-if="show2FAModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#2B1E16]/40 backdrop-blur-md animate-fade-in">
      <div class="bg-surface rounded-3xl w-full max-w-md p-6 sm:p-8 shadow-[0_25px_60px_-15px_rgba(43,30,22,0.25)] border border-borderSoft transform scale-100 transition-transform">
        <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center text-blue-500 mb-6 mx-auto">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
        </div>
        
        <h3 class="text-xl font-black text-espresso text-center mb-2">Setup Google Authenticator</h3>
        <p class="text-sm text-muted text-center mb-6 leading-relaxed">Pindai kode QR di bawah ini menggunakan aplikasi Google Authenticator atau aplikasi 2FA lainnya.</p>
        
        <div class="flex justify-center mb-6 bg-white p-4 rounded-2xl border border-borderSoft w-fit mx-auto">
          <!-- Menggunakan API public untuk generate QR Code secara real-time -->
          <img :src="`https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=otpauth://totp/SnapFit%3A${encodeURIComponent(user?.email || 'user')}?secret=${twoFactorSetupCode}&issuer=SnapFit`" alt="QR Code" class="w-40 h-40" />
        </div>

        <div class="mb-6 text-center">
          <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-2">Atau masukkan kode berikut secara manual:</p>
          <div class="bg-slate-50 border border-borderSoft rounded-lg py-2 px-4 text-espresso font-mono font-bold tracking-widest text-sm inline-block">
            {{ twoFactorSetupCode }}
          </div>
        </div>
        
        <div class="mb-6">
          <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider text-center">Masukkan 6 Digit Kode OTP</label>
          <input v-model="twoFactorInput" type="text" maxlength="6" placeholder="123456" class="w-full text-center bg-slate-50 border border-borderSoft rounded-xl px-4 py-3 text-lg text-espresso font-bold tracking-[0.5em] focus:outline-none focus:border-terracotta focus:ring-2 focus:ring-terracotta/20 transition-all" />
        </div>
        
        <div class="flex gap-3">
          <button @click="show2FAModal = false; twoFactorInput = ''" class="flex-1 px-4 py-3 rounded-xl font-bold text-sm text-espresso bg-slate-100 hover:bg-slate-200 transition-colors">Nanti Saja</button>
          <button @click="verifyAndEnable2FA" :disabled="twoFactorInput.length !== 6" class="flex-1 px-4 py-3 rounded-xl font-bold text-sm text-white transition-all shadow-lg" :class="twoFactorInput.length === 6 ? 'bg-terracotta hover:bg-terracottaDark shadow-terracotta/20' : 'bg-orange-300 cursor-not-allowed shadow-none'">Verifikasi</button>
        </div>
      </div>
    </div>

    <!-- Disable 2FA Confirmation Modal -->
    <div v-if="showDisable2FAModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#2B1E16]/40 backdrop-blur-md">
      <div class="bg-surface rounded-3xl w-full max-w-sm p-6 sm:p-8 shadow-[0_25px_60px_-15px_rgba(43,30,22,0.25)] border border-borderSoft">
        <div class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center text-red-500 mb-5 mx-auto">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
        </div>
        <h3 class="text-lg font-bold text-espresso text-center mb-2">Nonaktifkan 2FA?</h3>
        <p class="text-sm text-muted text-center mb-6 leading-relaxed">Ini akan mengurangi keamanan akun Anda. Anda yakin ingin menonaktifkan Autentikasi Dua Faktor?</p>
        <div class="flex gap-3">
          <button @click="showDisable2FAModal = false" class="flex-1 px-4 py-3 rounded-xl font-bold text-sm text-espresso bg-slate-100 hover:bg-slate-200 transition-colors">Batal</button>
          <button @click="confirmDisable2FA" class="flex-1 px-4 py-3 rounded-xl font-bold text-sm text-white bg-red-500 hover:bg-red-600 transition-colors shadow-lg shadow-red-500/20">Nonaktifkan</button>
        </div>
      </div>
    </div>

    <!-- 2FA Success Toast -->
    <Transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="translate-y-4 opacity-0"
      enter-to-class="translate-y-0 opacity-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="translate-y-0 opacity-100"
      leave-to-class="-translate-y-2 opacity-0"
    >
      <div v-if="show2FASuccess" class="fixed top-6 left-1/2 -translate-x-1/2 z-[100] flex items-center gap-3 bg-white border border-emerald-200 shadow-xl shadow-emerald-500/10 rounded-2xl px-5 py-4 max-w-sm">
        <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
          <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
        </div>
        <div>
          <p class="text-sm font-bold text-espresso">2FA Berhasil Diaktifkan</p>
          <p class="text-xs text-muted mt-0.5">Akun Anda kini lebih aman dengan verifikasi dua langkah.</p>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import Navbar from '@/pages/landing/partials/Navbar.vue';
import RoleWorkspaceMenu from './RoleWorkspaceMenu.vue';
import { useWishlistStore } from '@/stores/wishlistStore';
import { useNotificationStore } from '@/stores/notificationStore';

const router = useRouter();
const route = useRoute();
const wishlistStore = useWishlistStore();
const notificationStore = useNotificationStore();
const user = ref(null);
const activeMenu = ref(route.query.tab || 'profile');

const form = ref({
  name: '',
  email: '',
  gender: 'Laki-laki',
  dob: '',
  city: ''
});

const settings = ref({
  emailNotification: true,
  twoFactorAuth: false
});

const showDeleteModal = ref(false);
const deleteConfirmationText = ref('');

const show2FAModal = ref(false);
const show2FASuccess = ref(false);
const twoFactorSetupCode = ref('JBSWY3DPEHPK3PXP'); // Dummy base32 secret
const twoFactorInput = ref('');

const orders = ref([]);
const cartItems = ref([]);
const isLoadingCart = ref(false);
const isLoadingOrders = ref(false);
const isSavingProfile = ref(false);
const isUploadingAvatar = ref(false);
const avatarInput = ref(null);

const token = localStorage.getItem('token');

const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(price);
};

const cartTotal = computed(() => {
  return cartItems.value.reduce((total, item) => {
    return total + (parseFloat(item.product?.price || 0) * item.quantity);
  }, 0);
});

const fetchCart = async () => {
  if (!token) return;
  isLoadingCart.value = true;
  try {
    const res = await fetch('/api/v1/cart', {
      headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
    });
    const data = await res.json();
    if (data.status === 'success') {
      cartItems.value = data.data;
    }
  } catch (e) {
    console.error('Error fetching cart', e);
  } finally {
    isLoadingCart.value = false;
  }
};

const updateCartQuantity = async (cartId, newQuantity) => {
  if (newQuantity < 1) return;
  try {
    const res = await fetch(`/api/v1/cart/${cartId}`, {
      method: 'PUT',
      headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json', 'Content-Type': 'application/json' },
      body: JSON.stringify({ quantity: newQuantity })
    });
    const data = await res.json();
    if (data.status === 'success') {
      const item = cartItems.value.find(i => i.id === cartId);
      if (item) item.quantity = newQuantity;
    }
  } catch (e) {
    console.error('Error updating cart', e);
  }
};

const removeCartItem = async (cartId) => {
  try {
    const res = await fetch(`/api/v1/cart/${cartId}`, {
      method: 'DELETE',
      headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
    });
    const data = await res.json();
    if (data.status === 'success') {
      cartItems.value = cartItems.value.filter(i => i.id !== cartId);
    }
  } catch (e) {
    console.error('Error removing cart item', e);
  }
};

const fetchProfileData = async () => {
  if (!token) return router.push('/login');
  try {
    const res = await fetch('/api/v1/profile', {
      headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
    });
    if (res.ok) {
      const data = await res.json();
      user.value = data.user;
      form.value.name = user.value.name || '';
      form.value.email = user.value.email || '';
      if (user.value.profile) {
        form.value.city = user.value.profile.address || '';
        form.value.gender = user.value.profile.gender || 'Laki-laki';
        // Date input needs YYYY-MM-DD
        if (user.value.profile.date_of_birth) {
          form.value.dob = new Date(user.value.profile.date_of_birth).toISOString().split('T')[0];
        }
      }
    } else if (res.status === 401) {
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      router.push('/login');
    }
  } catch (err) {
    console.error('Failed to fetch profile', err);
  }
};

const fetchOrders = async () => {
  if (!token) return;
  isLoadingOrders.value = true;
  try {
    const res = await fetch('/api/v1/orders', {
      headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
    });
    if (res.ok) {
      const json = await res.json();
      orders.value = json.data;
    }
  } catch (err) {
    console.error('Failed to fetch orders', err);
  } finally {
    isLoadingOrders.value = false;
  }
};

const statusLabel = (status) => {
  const labels = {
    pending: 'Menunggu Bayar',
    paid: 'Dibayar',
    shipped: 'Dikirim',
    completed: 'Selesai',
    cancelled: 'Dibatalkan'
  };
  return labels[status] || status;
};

const getShippingStatusTitle = (status) => {
  const titles = {
    pending: 'Menunggu Pembayaran',
    paid: 'Pesanan Diproses',
    shipped: 'Pesanan Sedang Dikirim',
    completed: 'Pesanan Selesai',
    cancelled: 'Pesanan Dibatalkan'
  };
  return titles[status] || status;
};

const getShippingStatusDesc = (order) => {
  if (order.status === 'pending') {
    return 'Selesaikan pembayaran Anda agar pesanan dapat segera diproses oleh penjual.';
  } else if (order.status === 'paid') {
    return 'Pembayaran dikonfirmasi. Penjual sedang menyiapkan produk kreatif Anda.';
  } else if (order.status === 'shipped') {
    const courier = order.shipping_courier ? order.shipping_courier.toUpperCase() : 'Kurir';
    const resi = order.tracking_number ? ` (Resi: ${order.tracking_number})` : '';
    return `Paket Anda sedang dikirim menggunakan jasa ekspedisi ${courier}${resi}.`;
  } else if (order.status === 'completed') {
    return 'Paket telah sukses diterima. Terima kasih telah berbelanja kreatif!';
  } else if (order.status === 'cancelled') {
    return 'Pesanan telah dibatalkan.';
  }
  return '';
};

const selectedOrder = ref(null);

const viewOrderDetail = (order) => {
  router.push({ name: 'order.detail', params: { id: order.id } });
};

watch(activeMenu, (newVal) => {
  if (newVal === 'orders' && orders.value.length === 0) fetchOrders();
  if (newVal === 'cart' && cartItems.value.length === 0) fetchCart();
  if (newVal === 'wishlist' && wishlistStore.items.length === 0) wishlistStore.loadWishlist();
});

watch(() => route.query.tab, (newTab) => {
  if (newTab && ['profile', 'roles', 'orders', 'cart', 'wishlist', 'settings'].includes(newTab)) {
    activeMenu.value = newTab;
  }
});

onMounted(() => {
  if (!token) {
    router.push('/login');
    return;
  }
  
  // Load settings from localStorage
  const savedSettings = localStorage.getItem('snapfit_settings');
  if (savedSettings) {
    try {
      settings.value = { ...settings.value, ...JSON.parse(savedSettings) };
    } catch (e) {
      console.error('Error parsing settings', e);
    }
  }

  fetchProfileData();

  // Fetch initial tab data
  if (activeMenu.value === 'orders') fetchOrders();
  else if (activeMenu.value === 'cart') fetchCart();
  else if (activeMenu.value === 'wishlist') wishlistStore.loadWishlist();
});

const saveSettings = () => {
  localStorage.setItem('snapfit_settings', JSON.stringify(settings.value));
};

const showDisable2FAModal = ref(false);

const toggle2FA = () => {
  if (!settings.value.twoFactorAuth) {
    // Tampilkan modal setup jika belum aktif
    show2FAModal.value = true;
    twoFactorInput.value = '';
  } else {
    // Tampilkan modal konfirmasi nonaktifkan
    showDisable2FAModal.value = true;
  }
};

const confirmDisable2FA = () => {
  settings.value.twoFactorAuth = false;
  showDisable2FAModal.value = false;
  saveSettings();
};

const verifyAndEnable2FA = () => {
  if (twoFactorInput.value.length === 6) {
    show2FAModal.value = false;
    settings.value.twoFactorAuth = true;
    saveSettings();
    show2FASuccess.value = true;
    setTimeout(() => { show2FASuccess.value = false; }, 3500);
  }
};

const confirmDeleteAccount = () => {
  showDeleteModal.value = true;
  deleteConfirmationText.value = '';
};

const processDeleteAccount = () => {
  if (deleteConfirmationText.value === 'HAPUS') {
    showDeleteModal.value = false;
    logout();
  }
};

const userAvatar = computed(() => {
  if (user.value?.profile?.avatar_url) {
    return user.value.profile.avatar_url;
  }
  return `https://api.dicebear.com/7.x/avataaars/svg?seed=${user.value?.name || 'User'}&scale=130&backgroundColor=ffdfbf,c0aede,d1d4f9`;
});

const menus = [
  { 
    id: 'profile', 
    label: 'Profil',
    icon: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>`
  },
  { 
    id: 'roles', 
    label: 'Workspace',
    icon: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>`
  },
  { 
    id: 'orders', 
    label: 'Pesanan',
    icon: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>`
  },
  { 
    id: 'cart', 
    label: 'Keranjang',
    icon: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>`
  },
  { 
    id: 'wishlist', 
    label: 'Wishlist',
    icon: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>`
  },
  { 
    id: 'settings', 
    label: 'Pengaturan',
    icon: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>`
  }
];

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
    window.location.href = '/';
  }
};

const updateProfile = async () => {
  isSavingProfile.value = true;
  try {
    const res = await fetch('/api/v1/profile', {
      method: 'PUT',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        full_name: form.value.name,
        address: form.value.city,
        gender: form.value.gender,
        date_of_birth: form.value.dob
      })
    });
    
    if (res.ok) {
      const data = await res.json();
      user.value = data.user;
      localStorage.setItem('user', JSON.stringify(data.user));
      notificationStore.success('Profil berhasil diperbarui!');
    } else {
      notificationStore.error('Gagal memperbarui profil.');
    }
  } catch (err) {
    notificationStore.error('Terjadi kesalahan jaringan.');
  } finally {
    isSavingProfile.value = false;
  }
};

const triggerAvatarUpload = () => {
  avatarInput.value?.click();
};

const uploadAvatar = async (event) => {
  const file = event.target.files?.[0];
  if (!file) return;

  isUploadingAvatar.value = true;
  try {
    const body = new FormData();
    body.append('image', file);
    const res = await fetch('/api/v1/upload/avatar', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
      },
      body,
    });
    const data = await res.json();
    if (!res.ok) {
      throw new Error(data.message || 'Gagal mengunggah foto.');
    }
    if (!user.value.profile) user.value.profile = {};
    user.value.profile.avatar_url = data.avatar_url;
    localStorage.setItem('user', JSON.stringify(user.value));
    notificationStore.success('Foto profil berhasil diperbarui!');
  } catch (err) {
    notificationStore.error(err.message || 'Terjadi kesalahan saat mengunggah foto.');
  } finally {
    isUploadingAvatar.value = false;
    event.target.value = '';
  }
};
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.4s ease-out forwards;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
