<template>
  <div class="snapfit-heritage-bg min-h-screen text-espresso font-sans">
    <Navbar :user="user" @logout="logout" @goToLogin="goToLogin" />

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 md:pt-28 pb-16">
      <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-6">
        <div>
          <button type="button" @click="router.back()" class="inline-flex items-center gap-2 text-xs font-bold text-muted hover:text-terracotta transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
          </button>
          <h1 class="text-2xl md:text-3xl font-black tracking-tight text-espresso">Semua Notifikasi</h1>
          <p class="text-sm text-muted mt-1">Pesan dan pembaruan aktivitas akun SnapFit Anda.</p>
        </div>

        <button
          type="button"
          @click="markAllAsRead"
          :disabled="unreadCount === 0 || loading"
          class="px-4 py-2.5 rounded-xl bg-terracotta text-white text-[11px] font-black uppercase tracking-wider hover:bg-terracottaDark transition-colors disabled:opacity-45 disabled:cursor-not-allowed"
        >
          Tandai Semua Dibaca
        </button>
      </div>

      <div class="bg-surface border border-borderSoft rounded-3xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-borderSoft flex items-center justify-between bg-[#FFFCF7]">
          <p class="text-sm font-black text-espresso">Kotak Notifikasi</p>
          <span class="text-[11px] font-bold text-muted">{{ unreadCount }} belum dibaca</span>
        </div>

        <div v-if="loading" class="p-10 text-center">
          <div class="w-9 h-9 border-2 border-terracotta border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
          <p class="text-sm font-semibold text-muted">Memuat notifikasi...</p>
        </div>

        <div v-else-if="notifications.length === 0" class="p-12 text-center">
          <div class="w-16 h-16 rounded-2xl bg-sand mx-auto mb-4 flex items-center justify-center text-terracotta">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0a3 3 0 11-6 0" />
            </svg>
          </div>
          <h2 class="text-lg font-black text-espresso mb-1">Belum ada notifikasi</h2>
          <p class="text-sm text-muted">Semua pesan aktivitas Anda akan muncul di sini.</p>
        </div>

        <div v-else class="divide-y divide-borderSoft">
          <button
            v-for="notification in notifications"
            :key="notification.id"
            type="button"
            @click="markAsRead(notification)"
            class="w-full text-left p-5 hover:bg-[#F8F1E7] transition-colors flex gap-4"
            :class="notification.is_read ? 'bg-white/30' : 'bg-[#FFFCF7]'"
          >
            <span class="mt-1.5 w-2.5 h-2.5 rounded-full flex-shrink-0" :class="notification.is_read ? 'bg-transparent ring-1 ring-borderSoft' : 'bg-terracotta'"></span>
            <span class="min-w-0 flex-1">
              <span class="block text-sm text-espresso" :class="notification.is_read ? 'font-bold' : 'font-black'">
                {{ notification.title }}
              </span>
              <span class="block text-sm text-muted mt-1 leading-relaxed">{{ notification.message }}</span>
              <span class="block text-[11px] text-muted/70 font-bold mt-3">{{ formatDate(notification.created_at) }}</span>
            </span>
          </button>
        </div>

        <div v-if="pagination.last_page > 1" class="px-5 py-4 border-t border-borderSoft flex items-center justify-between bg-[#FFFCF7]">
          <button
            type="button"
            @click="fetchNotifications(pagination.current_page - 1)"
            :disabled="pagination.current_page === 1"
            class="px-4 py-2 rounded-xl border border-borderSoft text-xs font-bold text-espresso hover:bg-sand transition-colors disabled:opacity-45"
          >
            Prev
          </button>
          <span class="text-xs font-bold text-muted">Halaman {{ pagination.current_page }} / {{ pagination.last_page }}</span>
          <button
            type="button"
            @click="fetchNotifications(pagination.current_page + 1)"
            :disabled="pagination.current_page === pagination.last_page"
            class="px-4 py-2 rounded-xl border border-borderSoft text-xs font-bold text-espresso hover:bg-sand transition-colors disabled:opacity-45"
          >
            Next
          </button>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import Navbar from '@/pages/landing/partials/Navbar.vue';

const router = useRouter();
const user = ref(null);
const notifications = ref([]);
const unreadCount = ref(0);
const loading = ref(true);
const pagination = ref({ current_page: 1, last_page: 1, total: 0 });

const authHeaders = () => ({
  'Authorization': `Bearer ${localStorage.getItem('token')}`,
  'Accept': 'application/json',
});

const fetchNotifications = async (page = 1) => {
  loading.value = true;
  try {
    const res = await fetch(`/api/v1/notifications?page=${page}`, { headers: authHeaders() });
    if (!res.ok) throw new Error('Gagal memuat notifikasi.');
    const data = await res.json();
    notifications.value = data.data ?? [];
    unreadCount.value = data.unread_count ?? 0;
    pagination.value = data.pagination ?? { current_page: 1, last_page: 1, total: notifications.value.length };
  } catch (err) {
    console.error('Fetch notifications failed:', err);
  } finally {
    loading.value = false;
  }
};

const markAsRead = async (notification) => {
  if (notification.is_read) return;
  try {
    const res = await fetch(`/api/v1/notifications/${notification.id}/read`, {
      method: 'PATCH',
      headers: authHeaders(),
    });
    if (!res.ok) throw new Error('Gagal menandai notifikasi.');
    notification.is_read = true;
    unreadCount.value = Math.max(0, unreadCount.value - 1);
  } catch (err) {
    console.error('Mark notification failed:', err);
  }
};

const markAllAsRead = async () => {
  try {
    const res = await fetch('/api/v1/notifications/read-all', {
      method: 'PATCH',
      headers: authHeaders(),
    });
    if (!res.ok) throw new Error('Gagal menandai semua notifikasi.');
    notifications.value.forEach(notification => {
      notification.is_read = true;
    });
    unreadCount.value = 0;
  } catch (err) {
    console.error('Mark all notifications failed:', err);
  }
};

const formatDate = (value) => {
  if (!value) return '-';
  return new Intl.DateTimeFormat('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  }).format(new Date(value));
};

const goToLogin = () => router.push('/login');

const logout = () => {
  localStorage.removeItem('token');
  localStorage.removeItem('user');
  router.push('/');
};

onMounted(() => {
  const stored = localStorage.getItem('user');
  if (stored) user.value = JSON.parse(stored);
  fetchNotifications();
});
</script>
