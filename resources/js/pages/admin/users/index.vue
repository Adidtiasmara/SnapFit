<template>
  <div class="space-y-5 max-w-[1400px]">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
      <div class="flex items-center gap-4">
        <div class="flex bg-slate-100 rounded-lg p-0.5">
          <button v-for="t in roleTabs" :key="t.key" @click="roleFilter = t.key"
            class="px-3.5 py-2 text-[11px] font-semibold rounded-md transition-all"
            :class="roleFilter === t.key ? 'bg-surface text-espresso shadow-sm' : 'text-slate-400 hover:text-muted'">
            {{ t.label }}
            <span v-if="t.count" class="ml-1 text-[10px] font-bold px-1.5 py-0.5 rounded-md" :class="roleFilter === t.key ? 'bg-sand text-terracotta' : 'bg-slate-200/60 text-slate-400'">{{ t.count }}</span>
          </button>
        </div>
      </div>
      <div class="flex items-center gap-2.5 w-full md:w-auto">
        <div class="flex-1 md:w-56 flex items-center bg-surface rounded-lg px-3.5 py-2.5 gap-2 border border-borderSoft/60">
          <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
          <input v-model="search" @input="debounceSearch" type="text" placeholder="Cari user..." class="bg-transparent outline-none text-[12px] font-medium text-muted w-full placeholder:text-slate-400" />
        </div>
        <router-link to="/admin/users/create" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2.5 rounded-lg text-[11px] font-bold transition-colors flex items-center gap-1.5 whitespace-nowrap">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
          Tambah User
        </router-link>
      </div>
    </div>

    <!-- Table -->
    <div class="bg-surface rounded-2xl border border-borderSoft/60 overflow-hidden">
      <table class="w-full text-left">
        <thead>
          <tr class="border-b border-borderSoft">
            <th class="px-5 py-3.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">Pengguna</th>
            <th class="px-5 py-3.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">Role</th>
            <th class="px-5 py-3.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 hidden md:table-cell">Status</th>
            <th class="px-5 py-3.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 hidden lg:table-cell">Terdaftar</th>
            <th class="px-5 py-3.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
          <tr v-if="loading">
            <td colspan="5" class="px-5 py-12 text-center text-slate-400 text-[13px]">Memuat data...</td>
          </tr>
          <tr v-else-if="filteredUsers.length === 0">
            <td colspan="5" class="px-5 py-12 text-center text-slate-400 text-[13px]">Tidak ada data ditemukan.</td>
          </tr>
          <tr v-else v-for="u in filteredUsers" :key="u.id" class="hover:bg-slate-50/60 transition-colors group">
            <td class="px-5 py-3.5">
              <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center font-bold text-[12px] flex-shrink-0" :class="avatarClass(u.role)">
                  {{ u.name.charAt(0) }}
                </div>
                <div class="min-w-0">
                  <p class="text-[13px] font-semibold text-espresso truncate">{{ u.name }}</p>
                  <p class="text-[11px] text-slate-400 truncate">{{ u.email }}</p>
                </div>
              </div>
            </td>
            <td class="px-5 py-3.5">
              <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide rounded-md" :class="roleClass(u.role)">{{ u.role }}</span>
            </td>
            <td class="px-5 py-3.5 hidden md:table-cell">
              <div class="flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full" :class="u.active ? 'bg-emerald-400' : 'bg-slate-300'"></span>
                <span class="text-[11px] font-medium" :class="u.active ? 'text-emerald-600' : 'text-slate-400'">{{ u.active ? 'Aktif' : 'Nonaktif' }}</span>
              </div>
            </td>
            <td class="px-5 py-3.5 hidden lg:table-cell">
              <span class="text-[11px] text-slate-400 font-medium">{{ u.joined }}</span>
            </td>
            <td class="px-5 py-3.5 text-right">
              <div class="flex items-center justify-end gap-1 opacity-100">
                <router-link :to="`/admin/users/edit/${u.id}`" class="w-7 h-7 rounded-md bg-slate-100 hover:bg-slate-200 text-muted flex items-center justify-center transition-colors">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                </router-link>
                <button type="button" @click="deleteUser(u.id)"
                  class="w-7 h-7 rounded-md hover:bg-red-50 text-slate-400 hover:text-red-500 flex items-center justify-center transition-colors">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="px-5 py-3.5 border-t border-borderSoft flex items-center justify-between">
        <p class="text-[11px] text-slate-400 font-medium">Menampilkan {{ filteredUsers.length }} dari {{ allUsers.length }} user</p>
        <div class="flex items-center gap-1">
          <button v-for="p in pagination.last_page" :key="p" type="button" @click="fetchUsers(p)"
            class="w-8 h-8 rounded-md text-[11px] font-bold transition-colors"
            :class="p === pagination.current_page ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-100'">{{ p }}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';

const loading = ref(false);
const search = ref('');
const roleFilter = ref('all');
let searchTimeout = null;

const roleTabs = [
  { key: 'all', label: 'Semua', count: 257 },
  { key: 'umkm', label: 'UMKM', count: 142 },
  { key: 'desainer', label: 'Desainer', count: 67 },
  { key: 'pembeli', label: 'Pembeli', count: 48 },
];

const allUsers = ref([]);
const pagination = ref({ current_page: 1, last_page: 1, total: 0 });

const fetchUsers = async (page = 1) => {
  try {
    const token = localStorage.getItem('token') || '';
    const url = new URL(window.location.origin + '/api/v1/admin/users');
    url.searchParams.append('page', page);
    if (roleFilter.value !== 'all') url.searchParams.append('role', roleFilter.value);
    if (search.value) url.searchParams.append('search', search.value);
    
    const res = await fetch(url, {
      headers: { 'Authorization': `Bearer ${token}` }
    });
    if (res.ok) {
      const data = await res.json();
      allUsers.value = data.data.map(u => ({
        id: u.id,
        name: u.name,
        email: u.email,
        role: u.role,
        active: true, // Assuming all active for now
        joined: new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }).format(new Date(u.created_at))
      }));
      pagination.value = data.pagination;
    }
  } catch (e) {
    console.error('Failed to fetch users:', e);
  }
};

const deleteUser = async (id) => {
  if (!confirm('Hapus user ini?')) return;
  try {
    const token = localStorage.getItem('token') || '';
    const res = await fetch(`/api/v1/admin/users/${id}`, {
      method: 'DELETE',
      headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
    });
    if (res.ok) {
      allUsers.value = allUsers.value.filter(u => u.id !== id);
    }
  } catch (e) {
    console.error('Failed to delete user:', e);
  }
};

onMounted(() => {
  fetchUsers();
});

const filteredUsers = computed(() => allUsers.value);

const debounceSearch = () => { 
  clearTimeout(searchTimeout); 
  searchTimeout = setTimeout(() => {
    fetchUsers();
  }, 300); 
};

watch(roleFilter, () => {
  fetchUsers();
});

const avatarClass = (role) => ({
  umkm: 'bg-sand text-terracotta',
  desainer: 'bg-blue-50 text-blue-600',
  pembeli: 'bg-emerald-50 text-emerald-600',
  admin: 'bg-slate-800 text-white',
}[role] || 'bg-slate-100 text-muted');

const roleClass = (role) => ({
  umkm: 'bg-sand text-terracotta',
  desainer: 'bg-blue-50 text-blue-600',
  pembeli: 'bg-emerald-50 text-emerald-600',
  admin: 'bg-slate-100 text-espresso',
}[role] || 'bg-slate-100 text-muted');
</script>
