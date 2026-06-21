<template>
  <div class="space-y-5 max-w-[1400px]">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
      <div class="flex items-center gap-3">
        <div class="flex bg-slate-100 rounded-lg p-0.5">
          <button v-for="t in statusTabs" :key="t.key" @click="activeTab = t.key"
            class="px-3.5 py-2 text-[11px] font-semibold rounded-md transition-all"
            :class="activeTab === t.key ? 'bg-surface text-espresso shadow-sm' : 'text-slate-400 hover:text-muted'">
            {{ t.label }}
            <span v-if="t.count" class="ml-1 text-[10px] font-bold px-1.5 py-0.5 rounded-md"
              :class="activeTab === t.key ? (t.key === 'pending' ? 'bg-amber-50 text-amber-600' : 'bg-slate-100 text-muted') : 'bg-slate-200/60 text-slate-400'">{{ t.count }}</span>
          </button>
        </div>
      </div>
      <div class="flex items-center gap-2.5 w-full md:w-auto">
        <div class="flex-1 md:w-56 flex items-center bg-surface rounded-lg px-3.5 py-2.5 gap-2 border border-borderSoft/60">
          <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
          <input v-model="searchQuery" type="text" placeholder="Cari produk..." class="bg-transparent outline-none text-[12px] font-medium text-muted w-full placeholder:text-slate-400" />
        </div>
        <select v-model="categoryFilter" class="bg-surface border border-borderSoft/60 rounded-lg px-3.5 py-2.5 text-[11px] font-semibold text-muted outline-none cursor-pointer">
          <option value="">Semua Kategori</option>
          <option v-for="c in ['Batik','Kerajinan','Aksesoris','Fashion','Dekorasi']" :key="c" :value="c">{{ c }}</option>
        </select>
      </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
      <div v-for="p in filteredProducts" :key="p.id"
        class="bg-surface rounded-2xl border border-borderSoft/60 overflow-hidden group hover:shadow-md transition-all duration-300">
        <!-- Image -->
        <div class="relative h-44 bg-slate-100 overflow-hidden">
          <img :src="p.image" :alt="p.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
          <div class="absolute top-3 left-3">
            <span class="px-2.5 py-1 text-[9px] font-bold uppercase tracking-wide rounded-md bg-surface/90 backdrop-blur-sm"
              :class="p.status === 'pending' ? 'text-amber-600' : p.status === 'approved' ? 'text-emerald-600' : 'text-red-500'">
              {{ p.status === 'pending' ? '⏳ Pending' : p.status === 'approved' ? '✓ Approved' : '✕ Rejected' }}
            </span>
          </div>
          <div class="absolute top-3 right-3">
            <span class="px-2 py-1 text-[9px] font-bold uppercase tracking-wide rounded-md bg-slate-800/70 text-white backdrop-blur-sm">{{ p.category }}</span>
          </div>
        </div>

        <!-- Content -->
        <div class="p-4">
          <div class="flex items-start justify-between gap-2 mb-2">
            <div class="min-w-0">
              <h4 class="text-[13px] font-bold text-espresso truncate">{{ p.name }}</h4>
              <p class="text-[11px] text-slate-400 font-medium">oleh {{ p.seller }}</p>
            </div>
            <p class="text-[13px] font-bold text-terracotta whitespace-nowrap">Rp {{ p.price.toLocaleString('id-ID') }}</p>
          </div>

          <p class="text-[11px] text-muted leading-relaxed line-clamp-2 mb-3">{{ p.description }}</p>

          <div class="flex items-center justify-between pt-3 border-t border-borderSoft">
            <span class="text-[10px] text-slate-400 font-medium">{{ p.date }}</span>
            <div class="flex items-center gap-1.5">
              <template v-if="p.status === 'pending'">
                <button @click="approveProduct(p.id)" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-[10px] font-bold rounded-md transition-colors">Approve</button>
                <button @click="rejectProduct(p.id)" class="px-3 py-1.5 bg-surface hover:bg-red-50 text-red-500 text-[10px] font-bold rounded-md transition-colors border border-borderSoft hover:border-red-200">Reject</button>
              </template>
              <template v-else>
                <router-link :to="`/${p.id}`" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-muted text-[10px] font-bold rounded-md transition-colors">Detail</router-link>
              </template>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="filteredProducts.length === 0" class="bg-surface rounded-2xl border border-borderSoft/60 p-12 text-center">
      <p class="text-slate-400 text-[13px]">Tidak ada produk ditemukan.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

const activeTab = ref('all');
const searchQuery = ref('');
const categoryFilter = ref('');

const statusTabs = ref([
  { key: 'all', label: 'Semua', count: 0 },
  { key: 'pending', label: 'Draft', count: 0 },
  { key: 'approved', label: 'Published', count: 0 },
]);

const products = ref([]);

const getProductImage = (p) => {
  const imgs = p?.images;
  if (Array.isArray(imgs) && imgs.length > 0) return imgs[0];
  if (typeof imgs === 'string') {
    try {
      const parsed = JSON.parse(imgs);
      if (Array.isArray(parsed) && parsed.length > 0) return parsed[0];
      if (typeof parsed === 'string' && parsed) return parsed;
    } catch {
      if (imgs) return imgs;
    }
  }
  if (p?.image_url) return p.image_url;
  return '/images/products/kerajinan_fallback.png';
};

const fetchProducts = async () => {
  try {
    const token = localStorage.getItem('token') || '';
    const res = await fetch('/api/v1/admin/products', {
      headers: { 'Authorization': `Bearer ${token}` }
    });
    if (res.ok) {
      const data = await res.json();
      products.value = data.data.map(p => ({
        id: p.id,
        name: p.name,
        seller: p.owner?.name || 'Unknown',
        category: p.category || 'General',
        price: p.price,
        status: p.is_published ? 'approved' : 'pending',
        date: new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }).format(new Date(p.created_at)),
        description: p.description,
        image: getProductImage(p)
      }));
      
      statusTabs.value[0].count = products.value.length;
      statusTabs.value[1].count = products.value.filter(p => p.status === 'pending').length;
      statusTabs.value[2].count = products.value.filter(p => p.status === 'approved').length;
    }
  } catch (e) {
    console.error('Failed to fetch products:', e);
  }
};

onMounted(() => {
  fetchProducts();
});

const filteredProducts = computed(() => {
  let r = products.value;
  if (activeTab.value !== 'all') r = r.filter(p => p.status === activeTab.value);
  if (searchQuery.value) r = r.filter(p => p.name.toLowerCase().includes(searchQuery.value.toLowerCase()));
  if (categoryFilter.value) r = r.filter(p => p.category === categoryFilter.value);
  return r;
});

const approveProduct = (id) => { const p = products.value.find(p => p.id === id); if (p) p.status = 'approved'; };
const rejectProduct = (id) => { const p = products.value.find(p => p.id === id); if (p) p.status = 'rejected'; };
</script>
