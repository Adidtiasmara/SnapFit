<template>
  <div class="w-full space-y-5">
    <!-- Hero -->
    <div class="bg-gradient-to-br from-[#2B1E16] via-[#3D2B1F] to-[#4A3428] rounded-2xl p-7 text-white relative overflow-hidden">
      <div class="absolute -right-10 -top-10 w-48 h-48 bg-[#B85C38]/20 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-[#C9A45C]/15 rounded-full blur-3xl pointer-events-none"></div>
      <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <div class="flex items-center gap-2 mb-3">
            <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider bg-[#C9A45C]/20 text-[#E8C97A] rounded-lg border border-[#C9A45C]/30">AI Powered</span>
            <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider bg-[#B85C38]/20 text-[#F0A882] rounded-lg border border-[#B85C38]/30 flex items-center gap-1.5">
              <span class="w-1.5 h-1.5 bg-[#0F8A4B] rounded-full animate-pulse"></span>
              Active
            </span>
          </div>
          <h2 class="text-2xl font-bold mb-1.5">AI Product Studio</h2>
          <p class="text-[#C4B5A6] text-sm max-w-lg">Optimalkan visual, caption, dan strategi produk dengan bantuan AI.</p>
        </div>
      </div>
    </div>

    <!-- Step Indicator -->
    <div class="flex items-center gap-2 text-[11px] font-semibold overflow-x-auto no-scrollbar">
      <div class="flex items-center gap-1.5 px-3 py-2 bg-white border border-[#E8DCCB]/60 rounded-xl whitespace-nowrap" :class="selectedFile || form.original_image_url ? 'text-[#B85C38] border-[#B85C38]/30' : 'text-[#8A7A6C]'">
        <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold" :class="selectedFile || form.original_image_url ? 'bg-[#B85C38] text-white' : 'bg-[#F8F1E7] text-[#8A7A6C]'">1</span>
        Upload Foto
      </div>
      <svg class="w-3 h-3 text-[#8A7A6C] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
      <div class="flex items-center gap-1.5 px-3 py-2 bg-white border border-[#E8DCCB]/60 rounded-xl whitespace-nowrap" :class="form.prompt ? 'text-[#B85C38] border-[#B85C38]/30' : 'text-[#8A7A6C]'">
        <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold" :class="form.prompt ? 'bg-[#B85C38] text-white' : 'bg-[#F8F1E7] text-[#8A7A6C]'">2</span>
        Pilih Gaya
      </div>
      <svg class="w-3 h-3 text-[#8A7A6C] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
      <div class="flex items-center gap-1.5 px-3 py-2 bg-white border border-[#E8DCCB]/60 rounded-xl whitespace-nowrap" :class="isGenerating ? 'text-[#B85C38] border-[#B85C38]/30' : 'text-[#8A7A6C]'">
        <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold" :class="isGenerating ? 'bg-[#B85C38] text-white' : 'bg-[#F8F1E7] text-[#8A7A6C]'">3</span>
        Analisis AI
      </div>
      <svg class="w-3 h-3 text-[#8A7A6C] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
      <div class="flex items-center gap-1.5 px-3 py-2 bg-white border border-[#E8DCCB]/60 rounded-xl whitespace-nowrap text-[#8A7A6C]">
        <span class="w-5 h-5 bg-[#F8F1E7] text-[#8A7A6C] rounded-full flex items-center justify-center text-[10px] font-bold">4</span>
        Hasil Siap
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
      <!-- Form -->
      <div class="lg:col-span-1 space-y-4">
        <form @submit.prevent="generateAI" class="bg-white rounded-2xl border border-[#E8DCCB]/60 p-5 space-y-5">

          <!-- Upload Area -->
          <div>
            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8A7A6C] mb-2">Foto Asli Produk</label>
            <div class="border-2 border-dashed border-[#E8DCCB] rounded-xl p-5 text-center hover:border-[#B85C38] hover:bg-[#FFFCF7] transition-all cursor-pointer" @click="$refs.fileInput.click()">
              <input type="file" ref="fileInput" @change="handleFileChange" accept="image/*" class="hidden" />
              <div v-if="previewUrl" class="relative">
                <img :src="previewUrl" class="w-full h-36 object-cover rounded-lg" />
                <button type="button" @click.stop="clearFile" class="absolute top-2 right-2 w-7 h-7 bg-[#2B1E16]/70 backdrop-blur-sm text-white rounded-lg flex items-center justify-center hover:bg-[#D94A38] transition-colors">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
              </div>
              <div v-else class="space-y-2 py-3">
                <div class="w-11 h-11 bg-[#F8F1E7] text-[#B85C38] rounded-xl flex items-center justify-center mx-auto">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                </div>
                <p class="text-[12px] font-semibold text-[#2B1E16]">Klik untuk unggah foto</p>
                <p class="text-[10px] text-[#8A7A6C]">JPG, PNG, WebP (Max 5MB)</p>
              </div>
            </div>

            <div class="mt-3 flex items-center gap-3 before:flex-1 before:h-px before:bg-[#E8DCCB] after:flex-1 after:h-px after:bg-[#E8DCCB]">
              <span class="text-[9px] font-bold text-[#8A7A6C] uppercase tracking-widest">Atau</span>
            </div>

            <div class="mt-3">
              <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8A7A6C] mb-1.5">Gunakan URL Gambar</label>
              <input v-model="form.original_image_url" type="url" placeholder="https://contoh.com/foto.jpg" class="w-full bg-[#FFFCF7] border border-[#E8DCCB] rounded-xl px-3.5 py-2.5 text-[12px] font-medium text-[#2B1E16] outline-none focus:border-[#B85C38] focus:ring-2 focus:ring-[#B85C38]/10 transition-all" />
            </div>
          </div>

          <!-- Style Presets -->
          <div>
            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8A7A6C] mb-2">Pilih Gaya Visual</label>
            <div class="grid grid-cols-2 gap-2">
              <button v-for="preset in stylePresets" :key="preset.id" type="button" @click="form.prompt = preset.prompt"
                class="px-3 py-2 rounded-xl text-[11px] font-bold border transition-all text-left"
                :class="form.prompt === preset.prompt ? 'bg-[#B85C38]/10 text-[#B85C38] border-[#B85C38]/30' : 'bg-[#FFFCF7] text-[#6F6259] border-[#E8DCCB] hover:border-[#B85C38]/30 hover:text-[#B85C38]'">
                {{ preset.label }}
              </button>
            </div>
          </div>

          <!-- Custom Prompt -->
          <div>
            <label class="block text-[10px] font-bold uppercase tracking-wider text-[#8A7A6C] mb-1.5">Konsep / Kebutuhan (Opsional)</label>
            <textarea v-model="form.prompt" rows="3" placeholder="Contoh: Foto elegan untuk Instagram dengan tone hangat..." class="w-full bg-[#FFFCF7] border border-[#E8DCCB] rounded-xl px-3.5 py-2.5 text-[12px] font-medium text-[#2B1E16] outline-none focus:border-[#B85C38] focus:ring-2 focus:ring-[#B85C38]/10 transition-all resize-none"></textarea>
          </div>

          <!-- Submit -->
          <button type="submit" :disabled="isGenerating || (!selectedFile && !form.original_image_url)" class="w-full bg-[#2B1E16] hover:bg-[#B85C38] text-white px-5 py-3.5 rounded-xl text-[12px] font-bold transition-all disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center gap-2 btn-press shadow-md hover:shadow-lg">
            <svg v-if="isGenerating" class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
            <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></svg>
            {{ isGenerating ? 'AI Sedang Menganalisis...' : 'Mulai Analisis AI' }}
          </button>
        </form>

        <!-- Loading Steps & Progress Bar -->
        <div v-if="isGenerating" class="bg-white rounded-2xl border border-[#E8DCCB]/60 p-5 space-y-4 animate-slide-up shadow-sm">
          <div class="flex items-center justify-between">
            <p class="text-[11px] font-bold text-[#2B1E16]">Proses AI Studio</p>
            <span class="text-[10px] font-bold text-[#B85C38]">{{ progressPercent }}%</span>
          </div>
          <!-- Progress Bar -->
          <div class="w-full bg-[#F8F1E7] rounded-full h-1.5 overflow-hidden">
            <div class="bg-[#B85C38] h-1.5 rounded-full transition-all duration-300 ease-out" :style="{ width: progressPercent + '%' }"></div>
          </div>
          <div class="space-y-2">
            <div v-for="(step, idx) in loadingSteps" :key="idx" class="flex items-center gap-2 text-[11px]"
              :class="loadingStep > idx ? 'text-[#0F8A4B] font-semibold' : loadingStep === idx ? 'text-[#B85C38] font-semibold animate-pulse' : 'text-[#8A7A6C]'">
              <span class="w-4 h-4 flex items-center justify-center">
                <svg v-if="loadingStep > idx" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                <span v-else-if="loadingStep === idx" class="w-2 h-2 bg-[#B85C38] rounded-full animate-pulse"></span>
                <span v-else class="w-1.5 h-1.5 bg-[#E8DCCB] rounded-full"></span>
              </span>
              {{ step }}
            </div>
          </div>
        </div>

        <!-- Failure state with Retry Button -->
        <div v-if="hasFailed && !isGenerating" class="bg-red-50 rounded-2xl border border-red-200 p-5 space-y-3 animate-slide-up">
          <div class="flex items-center gap-2 text-red-700">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            <p class="text-xs font-bold">Analisis AI Gagal</p>
          </div>
          <p class="text-[11px] text-red-600 leading-relaxed">{{ errorMessage || 'Terjadi kesalahan saat menghubungi layanan AI.' }}</p>
          <button type="button" @click="generateAI" class="w-full py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-[11px] font-bold transition-all active:scale-[0.98] shadow-sm">
            Coba Lagi
          </button>
        </div>
      </div>

      <!-- History / Results -->
      <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-[#E8DCCB]/60 p-5 min-h-full">
          <div class="flex items-center justify-between mb-5">
            <h3 class="text-[14px] font-bold text-[#2B1E16]">Riwayat Analisis AI</h3>
            <button @click="fetchHistory(1)" class="text-[#8A7A6C] hover:text-[#B85C38] transition-colors" title="Refresh">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
            </button>
          </div>

          <!-- Skeleton Loading Shimmer -->
          <div v-if="loadingHistory" class="space-y-6">
            <div v-for="i in 2" :key="i" class="border-b border-[#E8DCCB]/40 pb-8 last:border-0">
              <div class="flex flex-col sm:flex-row items-start gap-5">
                <div class="w-full sm:w-40 shrink-0 aspect-square skeleton"></div>
                <div class="flex-1 w-full space-y-3">
                  <div class="flex items-center gap-2">
                    <div class="w-16 h-4 skeleton"></div>
                    <div class="w-24 h-3 skeleton"></div>
                  </div>
                  <div class="w-3/4 h-4 skeleton"></div>
                  <div class="space-y-2">
                    <div class="w-full h-3 skeleton"></div>
                    <div class="w-11/12 h-3 skeleton"></div>
                    <div class="w-4/5 h-3 skeleton"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div v-else-if="history.length === 0" class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-16 h-16 bg-[#F8F1E7] rounded-2xl flex items-center justify-center mb-3">
              <svg class="w-7 h-7 text-[#B85C38]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></svg>
            </div>
            <p class="text-[14px] font-bold text-[#2B1E16] mb-1">Belum Ada Analisis AI</p>
            <p class="text-[11px] text-[#8A7A6C] max-w-xs">Upload foto produk dan biarkan SnapFit memberi rekomendasi visual, caption, dan strategi katalog.</p>
          </div>

          <div v-else class="space-y-8">
            <div v-for="item in history" :key="item.id" class="border-b border-[#E8DCCB]/40 pb-8 last:border-0 relative group">
              <button @click="deleteHistory(item.id)" class="absolute top-0 right-0 w-7 h-7 bg-red-50 text-[#D94A38] rounded-lg flex items-center justify-center hover:bg-[#D94A38] hover:text-white transition-colors opacity-0 group-hover:opacity-100 z-10" title="Hapus">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
              </button>

              <div class="flex flex-col sm:flex-row items-start gap-5 mb-4">
                <div class="w-full sm:w-40 shrink-0 relative rounded-xl overflow-hidden border border-[#E8DCCB] bg-[#FFFCF7]">
                  <div class="absolute top-2 left-2 bg-[#2B1E16]/70 backdrop-blur-md text-white text-[9px] font-bold uppercase px-2 py-1 rounded-md">Original</div>
                  <img :src="item.original_image_url" class="w-full h-auto aspect-square object-contain" />
                </div>

                <div class="flex-1 w-full min-w-0">
                  <div class="flex items-center gap-2 mb-2">
                    <span class="bg-[#B85C38]/10 text-[#B85C38] px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider">AI Report</span>
                    <span class="text-[10px] text-[#8A7A6C]">{{ formatDate(item.created_at) }}</span>
                  </div>

                  <p v-if="item.prompt" class="text-[12px] text-[#6F6259] italic mb-3 bg-[#FFFCF7] p-2.5 rounded-lg border-l-2 border-[#B85C38]">
                    "{{ item.prompt }}"
                  </p>

                  <div class="prose prose-sm max-w-none text-[#2B1E16] prose-headings:text-[#2B1E16] prose-headings:font-bold prose-strong:text-[#2B1E16] prose-a:text-[#B85C38] prose-img:rounded-xl">
                    <div v-html="renderMarkdown(item.ai_analysis || '*Tidak ada analisis dari AI.*')"></div>
                  </div>

                  <div v-if="item.generated_images && item.generated_images.length > 0" class="mt-5">
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-[#8A7A6C] mb-2">AI Generated Image</h4>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                      <div v-for="(img, idx) in item.generated_images" :key="idx" class="relative aspect-square rounded-xl overflow-hidden cursor-pointer border border-[#E8DCCB] group/img">
                        <img :src="img" class="w-full h-full object-cover group-hover/img:scale-105 transition-transform duration-500" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div v-if="pagination.last_page > 1" class="flex justify-center pt-2">
              <button v-if="pagination.current_page < pagination.last_page" @click="fetchHistory(pagination.current_page + 1)" class="text-[11px] font-bold text-[#B85C38] hover:underline">
                Muat Lebih Banyak
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { marked } from 'marked';
import { useNotificationStore } from '@/stores/notificationStore';

const notificationStore = useNotificationStore();

const previewUrl = ref(null);
const fileInput = ref(null);
const selectedFile = ref(null);
const isGenerating = ref(false);
const loadingHistory = ref(true);
const history = ref([]);
const pagination = ref({ current_page: 1, last_page: 1 });
const loadingStep = ref(0);
const progressPercent = ref(0);
const hasFailed = ref(false);
const errorMessage = ref('');
let loadingInterval = null;

const loadingSteps = [
  'Menganalisis foto produk...',
  'Membaca konteks produk...',
  'Menyusun rekomendasi visual...',
  'Membuat caption profesional...',
];

const stylePresets = [
  { id: 'minimalis', label: 'Minimalis', prompt: 'Tolong berikan arahan gaya foto minimalis dan elegan dengan background polos.' },
  { id: 'vibrant', label: 'Vibrant', prompt: 'Tolong buatkan konsep foto yang ceria, terang, dan menonjolkan warna produk.' },
  { id: 'warm', label: 'Aesthetic Hangat', prompt: 'Tolong berikan referensi gaya foto aesthetic hangat dengan tone earthy dan natural.' },
  { id: 'studio', label: 'Studio Premium', prompt: 'Tolong arahkan foto produk dengan kualitas studio premium dan lighting profesional.' },
  { id: 'natural', label: 'Natural Light', prompt: 'Berikan arahan untuk foto dengan natural lighting dan suasana outdoor yang segar.' },
  { id: 'catalog', label: 'Catalog Clean', prompt: 'Berikan arahan foto katalog yang clean, white background, dan siap untuk marketplace.' },
];

const form = ref({
  original_image_url: '',
  prompt: ''
});

marked.setOptions({ gfm: true, breaks: true });
const renderMarkdown = (text) => marked.parse(text || '');

const handleFileChange = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  if (file.size > 5 * 1024 * 1024) {
    notificationStore.error('Ukuran file maksimal adalah 5MB.');
    return;
  }
  selectedFile.value = file;
  previewUrl.value = URL.createObjectURL(file);
  form.value.original_image_url = '';
  hasFailed.value = false;
  errorMessage.value = '';
};

const clearFile = () => {
  selectedFile.value = null;
  previewUrl.value = null;
  if (fileInput.value) fileInput.value.value = '';
};

const fetchHistory = async (page = 1) => {
  if (page === 1) loadingHistory.value = true;
  try {
    const token = localStorage.getItem('token');
    const res = await fetch(`/api/v1/umkm/ai-generations?page=${page}`, {
      headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
    });
    if (res.ok) {
      const data = await res.json();
      history.value = page === 1 ? data.data : [...history.value, ...data.data];
      pagination.value = data.pagination;
    }
  } finally {
    loadingHistory.value = false;
  }
};

const uploadFile = async () => {
  const token = localStorage.getItem('token');
  const formData = new FormData();
  formData.append('image', selectedFile.value);

  const res = await fetch('/api/v1/upload/ai-source', {
    method: 'POST',
    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' },
    body: formData,
  });

  if (!res.ok) {
    const error = await res.json();
    throw new Error(error.message || 'Gagal upload gambar');
  }

  const data = await res.json();
  // Make sure URL is absolute
  return data.url.startsWith('http') ? data.url : window.location.origin + data.url;
};

const startLoadingAnimation = () => {
  loadingStep.value = 0;
  progressPercent.value = 8;
  loadingInterval = setInterval(() => {
    if (loadingStep.value < loadingSteps.length - 1) {
      loadingStep.value++;
    }
    // Increment progress percent realistically
    if (progressPercent.value < 90) {
      const increment = Math.floor(Math.random() * 12) + 12; // 12-23% increment
      progressPercent.value = Math.min(progressPercent.value + increment, 92);
    }
  }, 1800);
};

const stopLoadingAnimation = (success = true) => {
  if (loadingInterval) { clearInterval(loadingInterval); loadingInterval = null; }
  if (success) {
    loadingStep.value = loadingSteps.length;
    progressPercent.value = 100;
  } else {
    progressPercent.value = 0;
  }
};

const generateAI = async () => {
  if (!selectedFile.value && !form.value.original_image_url) {
    notificationStore.warning('Harap unggah foto atau masukkan URL gambar produk.');
    return;
  }

  isGenerating.value = true;
  hasFailed.value = false;
  errorMessage.value = '';
  startLoadingAnimation();

  try {
    let finalImageUrl = form.value.original_image_url;
    if (selectedFile.value) finalImageUrl = await uploadFile();

    const token = localStorage.getItem('token');
    const res = await fetch('/api/v1/umkm/ai-generations/generate', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': `Bearer ${token}` },
      body: JSON.stringify({
        original_image_url: finalImageUrl,
        prompt: form.value.prompt || 'Tolong analisis produk ini dan berikan arahan fotografi profesional.',
      }),
    });

    if (res.ok) {
      stopLoadingAnimation(true);
      notificationStore.success('Analisis AI Berhasil Dibuat!', 4000);
      
      // Clear inputs
      clearFile();
      form.value.original_image_url = '';
      form.value.prompt = '';
      
      // Delay history refresh slightly to let animation finish smoothly
      setTimeout(async () => {
        await fetchHistory(1);
      }, 500);
    } else {
      const error = await res.json();
      throw new Error(error.message || error.error || 'Terjadi kesalahan pada server AI.');
    }
  } catch (e) {
    console.error(e);
    stopLoadingAnimation(false);
    hasFailed.value = true;
    errorMessage.value = e.message || 'Gagal memproses analisis AI. Silakan coba kembali.';
    notificationStore.error(errorMessage.value);
  } finally {
    isGenerating.value = false;
  }
};

const deleteHistory = async (id) => {
  if (!confirm('Hapus riwayat AI ini?')) return;
  try {
    const token = localStorage.getItem('token');
    const res = await fetch(`/api/v1/umkm/ai-generations/${id}`, {
      method: 'DELETE',
      headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
    });
    if (res.ok) {
      notificationStore.success('Riwayat berhasil dihapus.');
      fetchHistory(1);
    } else {
      notificationStore.error('Gagal menghapus riwayat.');
    }
  } catch (e) { 
    console.error(e); 
    notificationStore.error('Terjadi kesalahan saat menghapus.');
  }
};

const formatDate = (d) => {
  if (!d) return '-';
  return new Intl.DateTimeFormat('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }).format(new Date(d));
};

onMounted(() => { fetchHistory(); });
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<style>
.prose h1, .prose h2, .prose h3 {
  margin-top: 1.2em;
  margin-bottom: 0.4em;
  line-height: 1.3;
  color: #2B1E16;
}
.prose p { margin-top: 0.4em; margin-bottom: 0.4em; }
.prose ul, .prose ol { margin-top: 0.4em; margin-bottom: 0.4em; padding-left: 1.4em; }
.prose li { margin-top: 0.2em; margin-bottom: 0.2em; }
.prose strong { color: #2B1E16; }
</style>
