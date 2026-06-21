<template>
  <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700 w-full">
    <!-- Header -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
      <div>
        <h2 class="text-2xl font-bold text-espresso tracking-tight">Portofolio Desain</h2>
        <p class="text-sm text-muted font-medium mt-1">Showcase karya terbaik Anda kepada UMKM</p>
      </div>
      <button @click="showUploadModal = true" class="px-6 py-3.5 bg-terracotta hover:bg-terracotta text-white text-[10px] font-bold uppercase tracking-wider rounded-2xl transition-all shadow-sm flex items-center gap-2 active:scale-95">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
        Tambah Karya
      </button>
    </div>

    <!-- Stats Bar -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
      <div v-for="s in portfolioStats" :key="s.label" class="bg-surface rounded-2xl border border-borderSoft p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg" :class="s.bgClass">{{ s.emoji }}</div>
        <div>
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ s.label }}</p>
          <p class="text-xl font-bold text-espresso">{{ s.value }}</p>
        </div>
      </div>
    </div>

    <!-- Category Filter -->
    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1">
      <button v-for="cat in categories" :key="cat" @click="activeCategory = cat"
        class="px-4 py-2.5 text-[10px] font-bold uppercase tracking-wider rounded-xl transition-all whitespace-nowrap border"
        :class="activeCategory === cat ? 'bg-terracotta text-white border-orange-500 shadow-sm shadow-terracotta/20' : 'bg-surface text-muted border-borderSoft hover:border-terracotta/30 hover:text-terracotta'">
        {{ cat }}
      </button>
    </div>

    <!-- Portfolio Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      <div v-for="item in filteredPortfolio" :key="item.id"
        class="bg-surface rounded-2xl border border-borderSoft/60 overflow-hidden group hover:shadow-lg transition-all duration-500 cursor-pointer">
        <!-- Image -->
        <div class="relative aspect-[4/3] bg-slate-100 overflow-hidden">
          <img :src="item.image" :alt="item.title" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
          <!-- Overlay on Hover -->
          <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-5">
            <div class="flex items-center gap-3">
              <button type="button" @click.stop="selectedPortfolio = item"
                class="w-10 h-10 bg-surface/20 backdrop-blur-md rounded-xl flex items-center justify-center text-white hover:bg-surface/30 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
              </button>
              <button type="button" @click.stop="toggleLike(item)"
                class="w-10 h-10 bg-surface/20 backdrop-blur-md rounded-xl flex items-center justify-center text-white hover:bg-surface/30 transition-colors"
                :class="likedPortfolioIds.includes(item.id) ? 'text-pink-300' : ''">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
              </button>
            </div>
          </div>
          <!-- Category Badge -->
          <span class="absolute top-4 left-4 px-3 py-1.5 bg-surface/90 backdrop-blur-md text-[9px] font-bold uppercase tracking-wider text-terracotta rounded-xl">{{ item.category }}</span>
        </div>
        <!-- Content -->
        <div class="p-5">
          <h3 class="text-base font-bold text-espresso mb-1 truncate group-hover:text-terracotta transition-colors">{{ item.title }}</h3>
          <p class="text-xs text-muted font-medium mb-4 line-clamp-2 leading-relaxed">{{ item.description }}</p>
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="flex items-center gap-1 text-xs text-slate-400 font-medium">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                {{ item.views }}
              </div>
              <div class="flex items-center gap-1 text-xs text-slate-400 font-medium">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                {{ item.likes }}
              </div>
            </div>
            <span class="text-[10px] font-medium text-slate-400">{{ item.date }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Upload Modal -->
    <Teleport to="body">
      <Transition name="fade">
        <div v-if="showUploadModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click.self="showUploadModal = false">
          <div class="bg-surface rounded-2xl w-full max-w-lg p-8 shadow-2xl">
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-lg font-bold text-espresso">Tambah Karya Baru</h3>
              <button @click="showUploadModal = false" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-muted transition-colors">✕</button>
            </div>
            <div class="space-y-5">
              <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Judul Karya</label>
                <input v-model="portfolioForm.title" type="text" placeholder="Nama project / desain" class="w-full bg-slate-50 border border-borderSoft rounded-2xl px-5 py-3.5 text-sm font-medium text-espresso outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all" />
              </div>
              <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Kategori</label>
                <select v-model="portfolioForm.category" class="w-full bg-slate-50 border border-borderSoft rounded-2xl px-5 py-3.5 text-sm font-medium text-espresso outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all cursor-pointer">
                  <option v-for="c in categories.filter(c => c !== 'Semua')" :key="c">{{ c }}</option>
                </select>
              </div>
              <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Deskripsi</label>
                <textarea v-model="portfolioForm.description" rows="3" placeholder="Ceritakan tentang karya ini..." class="w-full bg-slate-50 border border-borderSoft rounded-2xl px-5 py-3.5 text-sm font-medium text-espresso outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all resize-none"></textarea>
              </div>
              <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Upload Gambar</label>
                <div class="border-2 border-dashed border-borderSoft rounded-2xl p-8 text-center hover:border-orange-300 transition-colors cursor-pointer">
                  <div class="text-3xl mb-2">🖼️</div>
                  <p class="text-sm font-bold text-muted">Drag & drop atau klik untuk upload</p>
                  <p class="text-xs text-slate-400 font-medium mt-1">PNG, JPG, WebP · Max 5MB</p>
                </div>
              </div>
              <button type="button" @click="savePortfolio"
                class="w-full bg-terracotta text-white py-4 rounded-2xl text-[10px] font-bold uppercase tracking-wider hover:from-orange-700 hover:to-amber-600 transition-all shadow-sm active:scale-[0.98]">
                Simpan Karya
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <Teleport to="body">
      <Transition name="fade">
        <div v-if="selectedPortfolio" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click.self="selectedPortfolio = null">
          <div class="bg-surface rounded-2xl w-full max-w-2xl overflow-hidden shadow-2xl">
            <img :src="selectedPortfolio.image" :alt="selectedPortfolio.title" class="w-full aspect-video object-cover bg-slate-100" />
            <div class="p-6">
              <div class="flex items-start justify-between gap-4">
                <div>
                  <p class="text-[10px] font-bold uppercase tracking-wider text-terracotta mb-1">{{ selectedPortfolio.category }}</p>
                  <h3 class="text-xl font-bold text-espresso">{{ selectedPortfolio.title }}</h3>
                </div>
                <button type="button" @click="selectedPortfolio = null" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-muted transition-colors">x</button>
              </div>
              <p class="text-sm text-muted leading-relaxed mt-4">{{ selectedPortfolio.description }}</p>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const showUploadModal = ref(false);
const activeCategory = ref('Semua');
const selectedPortfolio = ref(null);
const likedPortfolioIds = ref([]);
const portfolioForm = ref({
  title: '',
  category: 'Packaging',
  description: '',
});
const categories = ['Semua', 'Packaging', 'Branding', 'Social Media', 'Logo', 'Illustration', 'Photo'];

const portfolioStats = [
  { label: 'Total Karya', value: 12, emoji: '🎨', bgClass: 'bg-sand' },
  { label: 'Total Views', value: '2.4K', emoji: '👁️', bgClass: 'bg-blue-50' },
  { label: 'Total Likes', value: 186, emoji: '❤️', bgClass: 'bg-pink-50' },
  { label: 'Rata-rata Rating', value: '4.8', emoji: '⭐', bgClass: 'bg-amber-50' },
];

const portfolio = ref([
  { id: 1, title: 'Packaging Batik Tulis Premium', description: 'Desain kemasan premium untuk Batik Sari Malang dengan sentuhan modern dan elegan.', category: 'Packaging', image: '/images/products/batik_pekalongan.png', views: 342, likes: 28, date: 'Mei 2026' },
  { id: 2, title: 'Brand Identity Rotan Craft', description: 'Logo, color palette, dan panduan visual untuk brand kerajinan rotan artisanal.', category: 'Branding', image: '/images/products/tas_goni.png', views: 267, likes: 31, date: 'Apr 2026' },
  { id: 3, title: 'Instagram Kit Kopi Arjuno', description: 'Template visual untuk Instagram feed, story, dan carousel highlight brand kopi lokal.', category: 'Social Media', image: '/images/products/kopi_arjuno.png', views: 456, likes: 42, date: 'Apr 2026' },
  { id: 4, title: 'Logo Kampoeng Keramik', description: 'Redesign logo untuk sentra kerajinan keramik Dinoyo Malang.', category: 'Logo', image: '/images/products/keramik_dinoyo.png', views: 198, likes: 15, date: 'Mar 2026' },
  { id: 5, title: 'Product Catalog Digital', description: 'Katalog produk digital interaktif untuk Anyaman Bamboo Batu.', category: 'Branding', image: '/images/products/runner_meja.png', views: 312, likes: 24, date: 'Mar 2026' },
  { id: 6, title: 'Packaging Kripik Tempe', description: 'Kemasan standing pouch dengan desain vintage-modern untuk produk kripik tempe.', category: 'Packaging', image: '/images/products/kripik_tempe.png', views: 423, likes: 38, date: 'Feb 2026' },
]);

const filteredPortfolio = computed(() => {
  if (activeCategory.value === 'Semua') return portfolio.value;
  return portfolio.value.filter(p => p.category === activeCategory.value);
});

const toggleLike = (item) => {
  const index = likedPortfolioIds.value.indexOf(item.id);
  if (index >= 0) {
    likedPortfolioIds.value.splice(index, 1);
    item.likes = Math.max(0, item.likes - 1);
    return;
  }
  likedPortfolioIds.value.push(item.id);
  item.likes += 1;
};

const savePortfolio = () => {
  if (!portfolioForm.value.title.trim()) return;
  portfolio.value.unshift({
    id: Date.now(),
    title: portfolioForm.value.title,
    description: portfolioForm.value.description || 'Karya portofolio baru.',
    category: portfolioForm.value.category,
    image: '/images/products/batik_pekalongan.png',
    views: 0,
    likes: 0,
    date: 'Jun 2026',
  });
  portfolioForm.value = { title: '', category: 'Packaging', description: '' };
  showUploadModal.value = false;
};
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
.fade-enter-active, .fade-leave-active { transition: all 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
