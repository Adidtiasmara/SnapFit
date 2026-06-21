<template>
  <div class="snapfit-heritage-bg min-h-screen text-espresso font-sans selection:bg-terracotta selection:text-white overflow-x-hidden relative">


    <!-- Navbar -->
    <Navbar
      :user="user"
      @logout="logout"
      @goToLogin="goToLogin"
      @goToProfile="goToProfile"
      @goToOrders="goToOrders"
    />

    <main class="pt-[52px] md:pt-[64px]">
      <!-- Marketplace / Product Grid -->
      <ProductGrid
        :products="products"
        :isLoading="isLoading"
        :isLoadingMore="isLoadingMore"
        :hasMore="hasMore"
        @load-more="fetchMore"
        :is-homepage="true"
      />
        

    </main>


  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

// Layout components
import Navbar from '@/pages/landing/partials/Navbar.vue';

// Landing sections
import ProductGrid from '@/components/landing/ProductGrid.vue';

const router    = useRouter();
const user      = ref(null);
const products  = ref([]);
const isLoading = ref(false);

const page = ref(1);
const hasMore = ref(true);
const isLoadingMore = ref(false);

const fetchProducts = async () => {
  isLoading.value = true;
  page.value = 1;
  try {
    const res = await fetch(`/api/v1/products?page=1&per_page=12`, {
      headers: { 'Accept': 'application/json' }
    });
    if (!res.ok) throw new Error('Gagal mengambil data produk');

    const data = await res.json();
    const fetched = Array.isArray(data) ? data : (data.data ?? []);

    products.value = fetched
      .filter(p => p.is_published)
      .map(p => ({
        ...p,
        images: typeof p.images === 'string' ? JSON.parse(p.images) : p.images,
        inWishlist: false
      }));

    if (data.pagination) {
      hasMore.value = data.pagination.current_page < data.pagination.last_page;
    } else {
      hasMore.value = fetched.length > 0;
    }
  } catch (err) {
    console.error('Fetch error:', err);
  } finally {
    isLoading.value = false;
  }
};

const fetchMore = async () => {
  if (!hasMore.value || isLoadingMore.value) return;
  isLoadingMore.value = true;
  page.value++;

  try {
    const res = await fetch(`/api/v1/products?page=${page.value}&per_page=12`, {
      headers: { 'Accept': 'application/json' }
    });
    if (!res.ok) throw new Error('Gagal mengambil data produk');

    const data = await res.json();
    const fetched = Array.isArray(data) ? data : (data.data ?? []);

    const newProducts = fetched
      .filter(p => p.is_published)
      .map(p => ({
        ...p,
        images: typeof p.images === 'string' ? JSON.parse(p.images) : p.images,
        inWishlist: false
      }));

    products.value.push(...newProducts);

    if (data.pagination) {
      hasMore.value = data.pagination.current_page < data.pagination.last_page;
    } else {
      hasMore.value = fetched.length > 0;
    }
  } catch (err) {
    console.error('Fetch more error:', err);
  } finally {
    isLoadingMore.value = false;
  }
};

/* ── Navigation ───────────────────────────────────────── */
const goToLogin   = () => router.push('/login');
const goToProfile = () => router.push('/profile');
const goToOrders  = () => router.push('/orders');

const logout = () => {
  localStorage.removeItem('user');
  localStorage.removeItem('token');
  user.value = null;
  router.push('/');
};

/* ── Lifecycle ────────────────────────────────────────── */
onMounted(() => {
  // Check for token in URL (from Google OAuth redirect)
  const urlParams = new URLSearchParams(window.location.search);
  const token = urlParams.get('token');
  
  if (token) {
    // Save token to localStorage
    localStorage.setItem('token', token);
    
    // Fetch user data with the token
    fetch('/api/v1/auth/me', {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    })
    .then(res => res.json())
    .then(data => {
      if (data.user) {
        localStorage.setItem('user', JSON.stringify(data.user));
        user.value = data.user;
      }
      // Clean URL (remove token from query params)
      window.history.replaceState({}, document.title, window.location.pathname);
    })
    .catch(err => {
      console.error('Failed to fetch user:', err);
      // Clean URL even on error
      window.history.replaceState({}, document.title, window.location.pathname);
    });
  } else {
    // Load user from localStorage if no token in URL
    const stored = localStorage.getItem('user');
    if (stored) user.value = JSON.parse(stored);
  }
  
  fetchProducts();
});
</script>

<style scoped>
/* ═══ High-End Background Animations ═══ */
@keyframes breathe-slow {
  0%, 100% { 
    transform: translate(0, 0) scale(1) rotate(0deg); 
    opacity: 0.8;
  }
  33% { 
    transform: translate(3vw, -4vh) scale(1.05) rotate(2deg); 
    opacity: 0.95;
  }
  66% { 
    transform: translate(-2vw, 3vh) scale(0.95) rotate(-1deg); 
    opacity: 0.85;
  }
}

.animate-breathe-slow {
  animation: breathe-slow 24s ease-in-out infinite;
}

.animate-breathe-slow-delayed {
  animation: breathe-slow 28s ease-in-out infinite;
  animation-delay: -12s; /* Start halfway through to offset */
}
</style>
