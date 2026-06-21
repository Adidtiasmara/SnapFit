<template>
  <div class="snapfit-heritage-bg min-h-screen text-espresso font-sans relative pb-20">
    <Navbar :user="user" />

    <main class="max-w-[1420px] mx-auto px-4 py-6 md:py-10 pt-16">
      <!-- Back button and Title Header -->
      <div class="mb-6 md:mb-8" data-aos="fade-down">
        <button @click="goBack" class="inline-flex items-center gap-2 text-slate-500 hover:text-espresso transition-colors font-bold text-sm mb-3 md:mb-4">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Kembali ke Pesanan
        </button>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-espresso tracking-tight">Detail Pesanan</h1>
            <p class="text-slate-500 mt-1.5 text-xs md:text-sm font-normal">Pantau status pembayaran, kurir pengiriman, dan rincian produk pesanan Anda.</p>
          </div>
          <div class="bg-surface px-4 py-3 rounded-2xl border border-borderSoft shadow-sm flex items-center justify-between md:justify-end gap-6 w-full md:w-auto">
            <div>
              <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">ID Pesanan</p>
              <p class="text-sm font-bold text-espresso select-all">{{ order?.midtrans_order_id || 'N/A' }}</p>
            </div>
            <div class="text-right border-l border-dashed border-borderSoft pl-6">
              <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Tanggal</p>
              <p class="text-sm font-bold text-espresso">{{ formatDate(order?.created_at) }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="rounded-3xl bg-surface p-12 shadow-md border border-borderSoft text-center">
        <div class="flex justify-center mb-4">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-terracotta"></div>
        </div>
        <p class="text-slate-500 font-medium">Memuat detail pesanan...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="!order" class="rounded-3xl bg-surface p-12 shadow-md border border-borderSoft text-center">
        <p class="text-red-650 font-medium mb-6">Pesanan tidak ditemukan atau Anda tidak memiliki akses ke pesanan ini.</p>
        <button @click="goBack" class="inline-flex items-center justify-center rounded-full bg-slate-900 px-6 py-3 text-sm font-bold text-white hover:bg-slate-800 transition-all duration-300">
          Kembali ke Pesanan
        </button>
      </div>

      <!-- Main Grid Layout (Reordered on mobile so transaction card/payment details come first) -->
      <div v-else class="grid gap-6 md:gap-8 lg:grid-cols-[1.6fr_1fr] items-start">
        
        <!-- Right Column: Shipping & Payment Summary Sticky Sidebar (order-1 on mobile so it displays first) -->
        <div class="space-y-6 order-1 lg:order-2 lg:sticky lg:top-28">
          <div class="rounded-3xl bg-surface p-4 sm:p-6 md:p-8 shadow-md border border-borderSoft hover:shadow-lg transition-all duration-300">
            <h2 class="text-base md:text-lg font-bold text-espresso mb-4 md:mb-6 pb-3 md:pb-4 border-b border-borderSoft">Detail Transaksi</h2>
            
            <div class="space-y-4 mb-6">
              <div class="flex justify-between items-center text-sm">
                <span class="text-slate-400 font-semibold">ID Transaksi</span>
                <span class="font-bold text-espresso select-all bg-slate-50 px-2.5 py-1 rounded-lg border border-borderSoft text-xs">{{ order.midtrans_order_id || 'N/A' }}</span>
              </div>
              <div class="flex justify-between items-center text-sm">
                <span class="text-slate-400 font-semibold">Waktu Pemesanan</span>
                <span class="font-medium text-espresso">{{ formatDate(order.created_at) }}</span>
              </div>
              <div class="flex justify-between items-center text-sm">
                <span class="text-slate-400 font-semibold">Kurir Pengiriman</span>
                <span class="font-bold text-espresso uppercase bg-sand text-orange-700 px-2.5 py-0.5 rounded-lg border border-terracotta/10 text-xs">{{ order.shipping_courier || '-' }}</span>
              </div>
              <div class="flex justify-between items-center text-sm">
                <span class="text-slate-400 font-semibold">Nomor Resi</span>
                <span class="font-bold text-espresso break-all bg-slate-50 px-2.5 py-1 rounded-lg border border-borderSoft text-xs select-all">{{ order.tracking_number || 'Belum Tersedia' }}</span>
              </div>
              <div class="flex justify-between items-center text-sm">
                <span class="text-slate-400 font-semibold">Status Pembayaran</span>
                <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider"
                      :class="order.status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-emerald-100 text-emerald-700'">
                  {{ paymentStatus }}
                </span>
              </div>
            </div>

            <div class="border-t border-dashed border-borderSoft pt-4 mb-6">
              <div class="flex justify-between items-center">
                <span class="text-espresso font-bold">Total Pembayaran</span>
                <span class="text-xl md:text-2xl font-extrabold text-terracotta">{{ formatCurrency(order.total_amount) }}</span>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
              <button
                v-if="order.status === 'pending'"
                @click="payOrder"
                class="w-full rounded-full bg-terracotta px-6 py-3 text-sm font-bold uppercase tracking-wider text-white hover:bg-terracottaDark transition-all duration-300 shadow-md shadow-terracotta/20 active:scale-[0.98]"
              >
                Bayar Sekarang
              </button>
              <button
                v-if="order.status === 'shipped' && order.tracking_number"
                @click="openTrackingLink"
                class="w-full rounded-full bg-blue-600 px-6 py-3 text-sm font-bold uppercase tracking-wider text-white hover:bg-blue-700 transition-all duration-300 shadow-md shadow-blue-500/10 active:scale-[0.98]"
              >
                Lacak Paket
              </button>
              <button
                @click="buyAgain"
                class="w-full rounded-full bg-slate-900 px-6 py-3 text-sm font-bold uppercase tracking-wider text-white hover:bg-slate-800 transition-all duration-300 shadow-md shadow-slate-950/10 active:scale-[0.98]"
              >
                Beli Lagi
              </button>
              <button
                v-if="order.status === 'completed'"
                @click="writeReview"
                class="w-full rounded-full bg-emerald-600 px-6 py-3 text-sm font-bold uppercase tracking-wider text-white hover:bg-emerald-700 transition-all duration-300 shadow-md shadow-emerald-500/10 active:scale-[0.98]"
              >
                Tulis Ulasan
              </button>
              <button
                v-if="order.status === 'pending'"
                @click="showCancelModal = true"
                class="w-full rounded-full bg-red-50 text-red-650 hover:bg-red-100/70 border border-red-200 px-6 py-3 text-sm font-bold uppercase tracking-wider transition-all duration-300 active:scale-[0.98]"
              >
                Batalkan Pesanan
              </button>
            </div>
          </div>
        </div>

        <!-- Left Column: Timeline, Products, Shipping Address (order-2 on mobile so it displays below summary) -->
        <div class="space-y-6 order-2 lg:order-1">
          
          <!-- Section 1: Timeline -->
          <div class="rounded-3xl bg-surface p-4 sm:p-6 md:p-8 shadow-md border border-borderSoft hover:shadow-lg transition-all duration-300">
            <h2 class="text-base md:text-lg font-bold text-espresso mb-6 flex items-center gap-2.5">
              <span class="w-6 h-6 rounded-full bg-sand flex items-center justify-center text-xs text-orange-700 font-bold">1</span>
              Status Perjalanan Pesanan
            </h2>
            
            <div class="relative pl-6 border-l-2 border-slate-100 ml-3 space-y-8 py-2">
              <div v-for="(item, index) in timeline" :key="item.label" class="relative">
                <!-- Icon marker -->
                <div class="absolute -left-[35px] top-0.5 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold shadow-sm"
                     :class="item.completed ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-400'">
                  {{ item.completed ? '✓' : index + 1 }}
                </div>
                <div>
                  <p class="font-bold text-espresso text-sm md:text-base" :class="item.completed ? 'text-espresso' : 'text-slate-400'">
                    {{ item.label }}
                  </p>
                  <p class="text-xs text-slate-500 mt-1 font-normal leading-relaxed">{{ item.description }}</p>
                  <p v-if="item.date" class="text-[10px] text-terracotta font-bold mt-1.5 bg-sand/30 px-2 py-0.5 rounded-md inline-block">
                    {{ item.date }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Section 2: Products -->
          <div class="rounded-3xl bg-surface p-4 sm:p-6 md:p-8 shadow-md border border-borderSoft hover:shadow-lg transition-all duration-300">
            <h2 class="text-base md:text-lg font-bold text-espresso mb-6 flex items-center gap-2.5">
              <span class="w-6 h-6 rounded-full bg-sand flex items-center justify-center text-xs text-orange-700 font-bold">2</span>
              Produk yang Dipesan
            </h2>
            
            <div class="space-y-4">
              <div v-for="item in order.items" :key="item.id" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl bg-slate-50/50 p-4 border border-borderSoft hover:bg-slate-50 transition-all duration-300">
                <div class="flex items-center gap-4">
                  <img v-if="item.product?.image_url" :src="item.product.image_url" class="h-20 w-20 rounded-xl object-cover border border-borderSoft shadow-sm" :alt="item.product?.name" />
                  <div v-else class="h-20 w-20 rounded-xl bg-slate-200 flex items-center justify-center text-slate-400 text-xs font-bold border border-borderSoft">No Image</div>
                  <div>
                    <p class="font-bold text-espresso text-base">{{ item.product?.name || 'Produk' }}</p>
                    <div class="flex flex-wrap items-center gap-3 mt-1.5 text-xs text-slate-500 font-semibold">
                      <span class="bg-sand text-orange-700 px-2.5 py-0.5 rounded-full border border-terracotta/10" v-if="item.variant">Varian: {{ item.variant }}</span>
                      <span>Jumlah: {{ item.quantity }}x</span>
                    </div>
                  </div>
                </div>
                <div class="flex sm:flex-col items-baseline sm:items-end justify-between sm:justify-center border-t sm:border-t-0 border-dashed border-borderSoft pt-3 sm:pt-0">
                  <span class="text-xs text-slate-400 sm:hidden">Total Harga:</span>
                  <div class="text-right">
                    <p class="text-xs text-slate-400 font-medium mb-0.5">@{{ formatCurrency(item.price) }}</p>
                    <p class="text-base font-bold text-espresso">{{ formatCurrency(item.price * item.quantity) }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Section 3: Shipping Address -->
          <div class="rounded-3xl bg-surface p-4 sm:p-6 md:p-8 shadow-md border border-borderSoft hover:shadow-lg transition-all duration-300">
            <h2 class="text-base md:text-lg font-bold text-espresso mb-6 flex items-center gap-2.5">
              <span class="w-6 h-6 rounded-full bg-sand flex items-center justify-center text-xs text-orange-700 font-bold">3</span>
              Alamat Pengiriman
            </h2>
            <div class="rounded-2xl bg-[#F8F1E7]/10 p-5 border border-borderSoft">
              <p class="font-bold text-espresso text-base mb-2">{{ order.buyer?.profile?.full_name || order.buyer?.name || 'Penerima' }}</p>
              <p class="text-sm text-espresso/80 font-normal leading-relaxed mb-3">{{ order.buyer?.profile?.address || 'Alamat tidak tersedia' }}</p>
              <p class="text-xs text-slate-400 font-semibold">HP: {{ order.buyer?.profile?.phone || order.buyer?.phone_number || '-' }}</p>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Cancel Order Confirmation Modal -->
    <div v-if="showCancelModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
      <div class="w-full max-w-md rounded-[2rem] bg-surface p-6 shadow-2xl border border-borderSoft">
        <h3 class="text-xl font-bold text-espresso mb-3">Batalkan Pesanan</h3>
        <p class="text-slate-500 text-sm leading-relaxed mb-6">Apakah Anda yakin ingin membatalkan pesanan ini? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex gap-3">
          <button @click="showCancelModal = false" class="flex-1 rounded-full border border-borderSoft bg-surface px-4 py-3 text-sm font-bold uppercase tracking-wider text-espresso hover:bg-slate-50 transition-all">Kembali</button>
          <button @click="cancelOrder" class="flex-1 rounded-full bg-red-600 px-4 py-3 text-sm font-bold uppercase tracking-wider text-white hover:bg-red-700 transition-all">Ya, Batalkan</button>
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
const order = ref(null);
const user = ref(null);
const loading = ref(true);
const showCancelModal = ref(false);

const apiHeaders = () => {
  const token = localStorage.getItem('token');
  const headers = {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  };
  if (token) headers.Authorization = `Bearer ${token}`;
  return headers;
};

const fetchUser = () => {
  const userData = localStorage.getItem('user');
  if (userData) {
    try {
      user.value = JSON.parse(userData);
    } catch (e) {
      console.error(e);
    }
  }
};

const payOrder = async () => {
  if (!order.value) return;
  try {
    const res = await fetch(`/api/v1/payment/orders/${order.value.id}/snap-token`, {
      method: 'POST',
      headers: apiHeaders(),
    });
    if (!res.ok) {
      throw new Error('Gagal mendapatkan token pembayaran.');
    }
    const data = await res.json();
    const snapToken = data.snap_token;
    
    if (window.snap?.pay) {
      window.snap.pay(snapToken, {
        onSuccess: () => {
          notificationStore.success('Pembayaran sukses! 🎉');
          fetchOrder();
        },
        onPending: () => {
          notificationStore.info('Menunggu pembayaran Anda...');
          fetchOrder();
        },
        onError: () => {
          notificationStore.error('Pembayaran gagal.');
        },
        onClose: () => {}
      });
    } else {
      window.location.href = data.redirect_url;
    }
  } catch (err) {
    console.error('Payment error:', err);
    notificationStore.error(err.message || 'Gagal memproses pembayaran.');
  }
};

const fetchOrder = async () => {
  loading.value = true;
  try {
    const res = await fetch(`/api/v1/orders/${route.params.id}`, {
      headers: apiHeaders(),
    });

    if (!res.ok) {
      order.value = null;
      return;
    }

    const data = await res.json();
    order.value = data.data;
  } catch (err) {
    console.error('Fetch order error:', err);
    order.value = null;
  } finally {
    loading.value = false;
  }
};

const timeline = computed(() => {
  if (!order.value) return [];
  const phases = [
    { status: 'pending', label: 'Menunggu Pembayaran', description: 'Silakan lakukan pembayaran agar pesanan segera diproses', icon: '⏳' },
    { status: 'paid', label: 'Pesanan Diproses', description: 'Penjual sedang menyiapkan produk Anda', icon: '🔄' },
    { status: 'shipped', label: 'Pesanan Dikirim', description: 'Kurir sedang mengirimkan paket ke alamat Anda', icon: '📦' },
    { status: 'completed', label: 'Pesanan Selesai', description: 'Paket telah diterima dengan sukses', icon: '✓' },
  ];

  return phases.map((phase, index) => ({
    ...phase,
    completed: ['pending', 'paid', 'shipped', 'completed'].indexOf(phase.status) <= ['pending', 'paid', 'shipped', 'completed'].indexOf(order.value.status),
    date: phase.status === order.value.status ? formatDate(order.value.updated_at || order.value.created_at) : null,
  }));
});

const paymentStatus = computed(() => {
  if (!order.value) return 'Tidak Diketahui';
  if (order.value.status === 'pending') return 'Menunggu Pembayaran';
  return 'Sudah Dibayar';
});

const formatDate = (value) => {
  if (!value) return '-';
  const date = new Date(value);
  return new Intl.DateTimeFormat('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  }).format(date);
};

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(amount);
};

const goBack = () => {
  router.push('/profile?tab=orders');
};

const openTrackingLink = () => {
  if (!order.value?.tracking_number) return;
  const url = `https://cekresi.com/?q=${order.value.tracking_number}`;
  window.open(url, '_blank');
};

const buyAgain = () => {
  if (!order.value) return;
  const buyAgainItems = order.value.items.map((item) => ({ product_id: item.product?.id, quantity: item.quantity }));
  sessionStorage.setItem('buyAgainItems', JSON.stringify(buyAgainItems));
  router.push('/cart?buyAgain=true');
};

const writeReview = () => {
  if (!order.value?.items?.length) return;
  const product = order.value.items[0].product;
  if (!product) return;
  router.push(`/product/${product.id}?review=true`);
};

const cancelOrder = async () => {
  try {
    const res = await fetch(`/api/v1/orders/${route.params.id}/cancel`, {
      method: 'POST',
      headers: apiHeaders(),
    });

    if (!res.ok) {
      console.error('Cancel order failed:', res.status);
      return;
    }

    await fetchOrder();
    showCancelModal.value = false;
  } catch (err) {
    console.error('Cancel order error:', err);
  }
};

onMounted(() => {
  fetchOrder();
  fetchUser();
});
</script>

<style scoped>
/* Additional style overrides if needed */
</style>
