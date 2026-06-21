<template>
  <div class="space-y-6 max-w-7xl mx-auto">
    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
      <div v-for="(s, i) in stats" :key="i" class="bg-surface rounded-xl sm:rounded-2xl p-4 sm:p-5 border border-borderSoft/60">
        <div class="flex items-center justify-between mb-3 sm:mb-4">
          <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl flex items-center justify-center" :class="s.iconBg">
            <component :is="s.icon" class="w-4 h-4 sm:w-5 sm:h-5" :class="s.iconColor" />
          </div>
          <span class="text-[10px] sm:text-[11px] font-semibold px-2 py-0.5 rounded-md" :class="s.changeBg">{{ s.change }}</span>
        </div>
        <p class="text-lg sm:text-[22px] font-bold text-espresso leading-none mb-1">
          <span v-if="loading" class="inline-block w-14 sm:w-16 h-5 sm:h-6 bg-slate-100 rounded animate-pulse"></span>
          <span v-else>{{ s.value }}</span>
        </p>
        <p class="text-[10px] sm:text-[11px] text-slate-400 font-medium">{{ s.label }}</p>
      </div>
    </div>

    <!-- Chart + Activity Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-4">
      <!-- Revenue Chart -->
      <div class="lg:col-span-2 bg-surface rounded-xl sm:rounded-2xl border border-borderSoft/60 p-4 sm:p-6">
        <div class="flex items-center justify-between mb-4 sm:mb-6 flex-wrap gap-3">
          <div>
            <h3 class="text-[13px] sm:text-[14px] font-bold text-espresso">Revenue</h3>
            <p class="text-[10px] sm:text-[11px] text-slate-400 font-medium">Platform fees dari transaksi marketplace</p>
          </div>
          <div class="flex bg-slate-100 rounded-lg p-0.5">
            <button v-for="period in ['7D','1M','3M','1Y']" :key="period" @click="activePeriod = period"
              class="px-2.5 sm:px-3 py-1 sm:py-1.5 text-[9px] sm:text-[10px] font-bold rounded-md transition-all"
              :class="activePeriod === period ? 'bg-surface text-espresso shadow-sm' : 'text-slate-400 hover:text-muted'">
              {{ period }}
            </button>
          </div>
        </div>

        <!-- Revenue Value -->
        <div class="mb-4 sm:mb-6">
          <p class="text-2xl sm:text-[28px] font-bold text-espresso tracking-tight leading-none">Rp {{ loading ? '—' : '47.850.000' }}</p>
          <p class="text-[10px] sm:text-[11px] text-emerald-500 font-semibold mt-1">↑ 12.5% dari periode sebelumnya</p>
        </div>

        <!-- CSS Chart -->
        <div class="relative h-48 sm:h-52">
          <div class="absolute inset-0 flex flex-col justify-between pointer-events-none">
            <div v-for="i in 4" :key="i" class="w-full border-b border-dashed border-borderSoft"></div>
          </div>
          <div class="h-full flex items-end gap-[2px] sm:gap-[3px] relative z-10">
            <div v-for="(h, i) in barHeights" :key="i" class="flex-1 rounded-t-md transition-all duration-700 hover:opacity-80 cursor-pointer"
              :class="i === barHeights.length - 1 ? 'bg-terracotta' : 'bg-slate-200'" :style="{ height: h + '%' }"></div>
          </div>
        </div>
        <div class="flex justify-between mt-2 sm:mt-3 text-[9px] sm:text-[10px] text-slate-400 font-medium">
          <span v-for="d in ['Sen','Sel','Rab','Kam','Jum','Sab','Min']" :key="d">{{ d }}</span>
        </div>
      </div>

      <!-- Activity Feed -->
      <div class="lg:col-span-1 bg-surface rounded-2xl border border-borderSoft/60 p-5 sm:p-6">
        <div class="flex items-center justify-between mb-4 sm:mb-5">
          <h3 class="text-[13px] sm:text-[14px] font-bold text-espresso">Aktivitas Terbaru</h3>
          <router-link to="/admin/orders" class="text-[10px] sm:text-[11px] font-semibold text-terracotta hover:text-terracotta transition-colors">Lihat Semua</router-link>
        </div>

        <div class="space-y-3 sm:space-y-4">
          <div v-for="a in activities" :key="a.id" class="flex gap-2 sm:gap-3 group">
            <div class="relative flex flex-col items-center">
              <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg flex items-center justify-center text-xs flex-shrink-0" :class="a.dotClass">{{ a.emoji }}</div>
              <div class="w-px flex-1 bg-slate-100 mt-1"></div>
            </div>
            <div class="pb-3 sm:pb-4 flex-1 min-w-0">
              <p class="text-[11px] sm:text-[12px] font-semibold text-espresso leading-snug">{{ a.title }}</p>
              <p class="text-[10px] sm:text-[11px] text-slate-400 mt-0.5 truncate">{{ a.desc }}</p>
              <p class="text-[9px] sm:text-[10px] text-slate-300 font-medium mt-1">{{ a.time }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
      <!-- User Composition -->
      <div class="bg-surface rounded-xl sm:rounded-2xl border border-borderSoft/60 p-4 sm:p-6">
        <h3 class="text-[13px] sm:text-[14px] font-bold text-espresso mb-4 sm:mb-5">Komposisi Pengguna</h3>
        <div class="space-y-3 sm:space-y-4">
          <div v-for="r in userRoles" :key="r.role" class="flex items-center gap-2 sm:gap-3">
            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg flex items-center justify-center text-[10px] sm:text-[11px] font-bold flex-shrink-0" :class="r.bgClass">{{ r.initial }}</div>
            <div class="flex-1 min-w-0">
              <div class="flex items-center justify-between mb-1">
                <span class="text-[11px] sm:text-[12px] font-semibold text-espresso">{{ r.label }}</span>
                <span class="text-[11px] sm:text-[12px] font-bold text-espresso">{{ r.count }}</span>
              </div>
              <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all duration-1000" :class="r.barClass" :style="{ width: r.pct + '%' }"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Top Products -->
      <div class="bg-surface rounded-xl sm:rounded-2xl border border-borderSoft/60 p-4 sm:p-6">
        <h3 class="text-[13px] sm:text-[14px] font-bold text-espresso mb-4 sm:mb-5">Produk Terlaris</h3>
        <div class="space-y-2 sm:space-y-3">
          <div v-for="(p, i) in topProducts" :key="i" class="flex items-center gap-2 sm:gap-3 p-2 -mx-2 rounded-xl hover:bg-slate-50 transition-colors">
            <span class="w-5 h-5 sm:w-6 sm:h-6 rounded-md bg-slate-100 flex items-center justify-center text-[9px] sm:text-[10px] font-bold text-slate-400 flex-shrink-0">{{ i + 1 }}</span>
            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg bg-slate-100 overflow-hidden flex-shrink-0">
              <img :src="p.img" :alt="p.name" class="w-full h-full object-cover" />
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-[11px] sm:text-[12px] font-semibold text-espresso truncate">{{ p.name }}</p>
              <p class="text-[9px] sm:text-[10px] text-slate-400">{{ p.seller }}</p>
            </div>
            <span class="text-[11px] sm:text-[12px] font-bold text-espresso whitespace-nowrap">{{ p.sold }} terjual</span>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="bg-surface rounded-xl sm:rounded-2xl border border-borderSoft/60 p-4 sm:p-6">
        <h3 class="text-[13px] sm:text-[14px] font-bold text-espresso mb-4 sm:mb-5">Perlu Perhatian</h3>
        <div class="space-y-2 sm:space-y-3">
          <div v-for="alert in alerts" :key="alert.id" class="flex items-start gap-2 sm:gap-3 p-2.5 sm:p-3 rounded-xl border" :class="alert.borderClass">
            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg flex items-center justify-center text-sm flex-shrink-0" :class="alert.bgClass">{{ alert.emoji }}</div>
            <div class="flex-1 min-w-0">
              <p class="text-[11px] sm:text-[12px] font-semibold text-espresso">{{ alert.title }}</p>
              <p class="text-[9px] sm:text-[10px] text-slate-400 mt-0.5">{{ alert.desc }}</p>
            </div>
            <router-link :to="alert.link" class="text-[9px] sm:text-[10px] font-bold text-terracotta hover:text-terracotta whitespace-nowrap mt-0.5 flex-shrink-0">Lihat →</router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { h } from 'vue';

const loading = ref(true);
const activePeriod = ref('7D');

onMounted(() => { setTimeout(() => loading.value = false, 800); });

const IconStore = () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24', 'stroke-width': '1.8' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.15c0 .415.336.75.75.75z' })]);
const IconPaint = () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24', 'stroke-width': '1.8' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42' })]);
const IconMoney = () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24', 'stroke-width': '1.8' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z' })]);
const IconBox = () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24', 'stroke-width': '1.8' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4' })]);

const stats = ref([
  { label: 'Total UMKM', value: '0', change: '+0%', icon: IconStore, iconBg: 'bg-sand', iconColor: 'text-terracotta', changeBg: 'bg-emerald-50 text-emerald-600' },
  { label: 'Total Desainer', value: '0', change: '+0%', icon: IconPaint, iconBg: 'bg-blue-50', iconColor: 'text-blue-500', changeBg: 'bg-emerald-50 text-emerald-600' },
  { label: 'Revenue', value: 'Rp 0', change: '+0%', icon: IconMoney, iconBg: 'bg-emerald-50', iconColor: 'text-emerald-500', changeBg: 'bg-emerald-50 text-emerald-600' },
  { label: 'Produk Terdaftar', value: '0', change: '+0', icon: IconBox, iconBg: 'bg-violet-50', iconColor: 'text-violet-500', changeBg: 'bg-violet-50 text-violet-600' },
]);

const userRoles = ref([
  { role: 'umkm', label: 'UMKM Kreator', count: 0, pct: 0, initial: 'U', bgClass: 'bg-sand text-terracotta', barClass: 'bg-orange-400' },
  { role: 'desainer', label: 'Desainer', count: 0, pct: 0, initial: 'D', bgClass: 'bg-blue-50 text-blue-600', barClass: 'bg-blue-400' },
  { role: 'pembeli', label: 'Pembeli', count: 0, pct: 0, initial: 'P', bgClass: 'bg-emerald-50 text-emerald-600', barClass: 'bg-emerald-400' },
]);

const barHeights = [35, 52, 45, 78, 62, 90, 70, 42, 88, 55, 72, 60, 82, 48, 68, 75, 58, 92, 65, 80, 45, 70, 85, 50, 78, 62, 95, 72];

const activities = [
  { id: 1, title: 'UMKM baru terdaftar', desc: 'Batik Cap Bunga Malang bergabung ke platform', time: '2 menit lalu', emoji: '🏪', dotClass: 'bg-sand' },
  { id: 2, title: 'Produk pending moderasi', desc: '3 produk baru menunggu review dari UMKM Keramik', time: '15 menit lalu', emoji: '📦', dotClass: 'bg-amber-50' },
  { id: 3, title: 'Transaksi selesai', desc: 'Order #SF-8842 completed', time: '1 jam lalu', emoji: '✅', dotClass: 'bg-emerald-50' },
];

const topProducts = [
  { name: 'Batik Tulis Motif Arema', seller: 'Batik Sari Malang', sold: 128, img: 'https://images.unsplash.com/photo-1558618666-fcd25c85f82e?w=80&h=80&fit=crop' },
  { name: 'Tas Anyaman Rotan Natural', seller: 'Rotan Craft Arjosari', sold: 96, img: 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=80&h=80&fit=crop' },
  { name: 'Keramik Vas Modern', seller: 'Kampoeng Keramik', sold: 84, img: 'https://images.unsplash.com/photo-1578749556568-bc2c40e68b61?w=80&h=80&fit=crop' },
];

const alerts = [
  { id: 1, title: 'Produk Menunggu Moderasi', desc: 'Perlu review admin', emoji: '⚠️', bgClass: 'bg-amber-50', borderClass: 'border-amber-200/60', link: '/admin/products' },
];

// Fetch real data
onMounted(async () => {
  try {
    const token = localStorage.getItem('token') || '';
    const res = await fetch('/api/v1/admin/stats', {
      headers: { 'Authorization': `Bearer ${token}` }
    });
    if (res.ok) {
      const data = await res.json();
      
      // Update stats
      stats.value[0].value = data.users.umkm.toString();
      stats.value[1].value = data.users.desainer.toString();
      
      // Format revenue safely based on backend type
      const rev = Number(data.orders.revenue || 0);
      stats.value[2].value = 'Rp ' + (rev > 1000000 ? (rev/1000000).toFixed(1) + 'M' : rev.toLocaleString('id-ID'));
      
      stats.value[3].value = data.products.total.toString();

      // Update user roles
      const totalUsers = data.users.total || 1; // prevent div by zero
      userRoles.value[0].count = data.users.umkm;
      userRoles.value[0].pct = Math.round((data.users.umkm / totalUsers) * 100);
      
      userRoles.value[1].count = data.users.desainer;
      userRoles.value[1].pct = Math.round((data.users.desainer / totalUsers) * 100);
      
      userRoles.value[2].count = data.users.pembeli;
      userRoles.value[2].pct = Math.round((data.users.pembeli / totalUsers) * 100);
    }
  } catch (e) {
    console.error('Failed to fetch stats:', e);
  } finally {
    loading.value = false;
  }
});
</script>
