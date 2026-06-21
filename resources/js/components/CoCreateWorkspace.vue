<template>
  <div class="h-[calc(100vh-8rem)] flex flex-col md:flex-row gap-4 animate-in fade-in duration-500">
    <!-- Main Workspace -->
    <div class="flex-1 bg-white rounded-2xl border border-[#E8DCCB]/60 flex flex-col overflow-hidden relative">
      <div v-if="loading" class="absolute inset-0 bg-white/80 backdrop-blur-sm z-50 flex items-center justify-center">
        <div class="w-10 h-10 border-3 border-[#E8DCCB] border-t-[#B85C38] rounded-full animate-spin"></div>
      </div>

      <!-- Room Header -->
      <div class="px-5 py-3 border-b border-[#E8DCCB]/60 flex items-center justify-between bg-[#FFFCF7]">
        <div class="flex items-center gap-3 min-w-0">
          <button @click="goBack" class="mr-1 text-[#8A7A6C] hover:text-[#2B1E16] transition-colors" title="Kembali">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
          </button>
          <span class="w-2 h-2 rounded-full bg-[#0F8A4B] animate-pulse flex-shrink-0"></span>
          <div class="min-w-0">
            <h2 class="text-sm font-bold text-[#2B1E16] truncate">{{ room?.name || 'Memuat...' }}</h2>
            <p class="text-[10px] text-[#8A7A6C] truncate">{{ room?.description }}</p>
          </div>
        </div>
        <div class="flex items-center gap-2">
          <!-- Auto-save indicator -->
          <transition name="fade">
            <span v-if="showAutoSave" class="text-[9px] font-bold text-[#0F8A4B] bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200 flex items-center gap-1">
              <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
              Tersimpan
            </span>
          </transition>
          <!-- Live Indicator & Close Room Button -->
          <button v-if="isCreator" @click="closeRoom" class="px-3 py-1.5 bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 text-[9px] font-bold uppercase tracking-wider rounded-xl transition-colors">
            Tutup Room
          </button>
          <div class="flex -space-x-1.5 ml-2">
            <div v-for="p in (room?.participants || []).slice(0, 4)" :key="p.id" class="w-7 h-7 rounded-full border-2 border-white bg-[#F8F1E7] flex items-center justify-center text-[10px] font-bold text-[#B85C38]" :title="p.user?.name || p.name">
              {{ (p.user?.name || p.name || 'P').charAt(0).toUpperCase() }}
            </div>
          </div>
        </div>
      </div>

      <!-- Canvas Area -->
      <div ref="containerEl" class="flex-1 bg-[#FAFAF8] relative overflow-hidden">
        <div class="absolute inset-0 opacity-30 bg-[radial-gradient(#E8DCCB_1px,transparent_1px)] [background-size:20px_20px] pointer-events-none"></div>

        <!-- Empty State -->
        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none transition-opacity duration-300" :class="hasContent ? 'opacity-0' : 'opacity-100'">
          <div class="w-16 h-16 bg-[#F8F1E7] rounded-2xl flex items-center justify-center mb-3">
            <svg class="w-7 h-7 text-[#B85C38]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42" /></svg>
          </div>
          <h3 class="text-base font-bold text-[#2B1E16]/30 mb-1">Kanvas Kolaborasi</h3>
          <p class="text-[11px] text-[#8A7A6C]/60 max-w-[240px] text-center">Gunakan toolbar di bawah untuk mendesain bersama.</p>
        </div>

        <canvas ref="canvasEl" class="absolute inset-0 z-10"></canvas>
      </div>

      <!-- Design Toolbar -->
      <div class="px-4 py-3 border-t border-[#E8DCCB]/60 bg-[#FFFCF7] flex items-center justify-between gap-3 flex-wrap z-20">
        <!-- Left: Drawing Tools -->
        <div class="flex items-center gap-1">
          <button v-for="tool in drawingTools" :key="tool.id" @click="setMode(tool.id)" :title="tool.label"
            class="w-9 h-9 rounded-xl flex items-center justify-center transition-all text-[#8A7A6C]"
            :class="mode === tool.id ? 'bg-[#B85C38]/10 text-[#B85C38] shadow-sm' : 'hover:bg-[#F8F1E7] hover:text-[#2B1E16]'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" v-html="tool.path"></svg>
          </button>

          <div class="w-px h-6 bg-[#E8DCCB] mx-1"></div>

          <!-- Color Picker -->
          <div class="flex items-center gap-1">
            <button v-for="c in colors" :key="c" @click="setBrushColor(c)" class="w-6 h-6 rounded-full border-2 transition-all"
              :class="brushColor === c ? 'border-[#2B1E16] scale-110' : 'border-transparent hover:scale-110'"
              :style="{ backgroundColor: c }"></button>
          </div>

          <div class="w-px h-6 bg-[#E8DCCB] mx-1"></div>

          <!-- Brush Size -->
          <div class="flex items-center gap-1.5">
            <button v-for="s in [2, 4, 8, 12]" :key="s" @click="setBrushSize(s)" :title="`${s}px`"
              class="rounded-full bg-[#2B1E16] transition-all"
              :class="brushSize === s ? 'ring-2 ring-[#B85C38] ring-offset-1' : 'opacity-40 hover:opacity-70'"
              :style="{ width: Math.max(s + 4, 10) + 'px', height: Math.max(s + 4, 10) + 'px' }"></button>
          </div>
        </div>

        <!-- Right: Actions -->
        <div class="flex items-center gap-1.5">
          <!-- Shape Tools -->
          <button @click="addRect" title="Kotak" class="w-9 h-9 rounded-xl flex items-center justify-center text-[#8A7A6C] hover:bg-[#F8F1E7] hover:text-[#2B1E16] transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="2" /></svg>
          </button>
          <button @click="addCircle" title="Lingkaran" class="w-9 h-9 rounded-xl flex items-center justify-center text-[#8A7A6C] hover:bg-[#F8F1E7] hover:text-[#2B1E16] transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><circle cx="12" cy="12" r="9" /></svg>
          </button>
          <button @click="addTriangle" title="Segitiga" class="w-9 h-9 rounded-xl flex items-center justify-center text-[#8A7A6C] hover:bg-[#F8F1E7] hover:text-[#2B1E16] transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3L2 21h20L12 3z" /></svg>
          </button>
          <button @click="addText" title="Teks" class="w-9 h-9 rounded-xl flex items-center justify-center text-[#8A7A6C] hover:bg-[#F8F1E7] hover:text-[#2B1E16] transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M8 6v12m4-12v12m4-12v12" /></svg>
          </button>
          <button @click="addLine" title="Garis" class="w-9 h-9 rounded-xl flex items-center justify-center text-[#8A7A6C] hover:bg-[#F8F1E7] hover:text-[#2B1E16] transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" d="M4 20L20 4" /></svg>
          </button>
          <button @click="addArrow" title="Panah" class="w-9 h-9 rounded-xl flex items-center justify-center text-[#8A7A6C] hover:bg-[#F8F1E7] hover:text-[#2B1E16] transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
          </button>

          <div class="w-px h-6 bg-[#E8DCCB] mx-1"></div>

          <!-- Fashion Templates -->
          <div class="relative" ref="templateDropdown">
            <button @click="showTemplates = !showTemplates" title="Template Fashion" class="px-2.5 h-9 rounded-xl flex items-center justify-center gap-1 text-[10px] font-bold text-[#8A7A6C] hover:bg-[#F8F1E7] hover:text-[#2B1E16] transition-all border border-[#E8DCCB]">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" /></svg>
              Template
            </button>
            <div v-if="showTemplates" class="absolute bottom-full mb-2 left-0 bg-white rounded-xl border border-[#E8DCCB] shadow-xl p-2 w-48 z-30">
              <p class="text-[9px] font-bold text-[#8A7A6C] uppercase tracking-wider px-2 mb-1.5">Fashion Template</p>
              <button v-for="t in fashionTemplates" :key="t.name" @click="addFashionTemplate(t.id); showTemplates = false"
                class="w-full text-left px-3 py-2 rounded-lg text-[11px] font-medium text-[#2B1E16] hover:bg-[#F8F1E7] transition-colors flex items-center gap-2">
                <span>{{ t.icon }}</span> {{ t.name }}
              </button>
            </div>
          </div>

          <!-- Pattern Fill -->
          <button @click="addPattern" title="Pattern / Motif" class="w-9 h-9 rounded-xl flex items-center justify-center text-[#8A7A6C] hover:bg-[#F8F1E7] hover:text-[#2B1E16] transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" /></svg>
          </button>

          <!-- Mirror/Flip -->
          <button @click="flipHorizontal" title="Flip Horizontal" class="w-9 h-9 rounded-xl flex items-center justify-center text-[#8A7A6C] hover:bg-[#F8F1E7] hover:text-[#2B1E16] transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
          </button>

          <div class="w-px h-6 bg-[#E8DCCB] mx-1"></div>

          <!-- Import -->
          <button @click="importImage" title="Import Gambar" class="w-9 h-9 rounded-xl flex items-center justify-center text-[#8A7A6C] hover:bg-[#F8F1E7] hover:text-[#2B1E16] transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
          </button>
          <button @click="importFromFigma" title="Import dari Figma (SVG)" class="px-2.5 h-9 rounded-xl flex items-center justify-center gap-1 text-[10px] font-bold text-[#8A7A6C] hover:bg-purple-50 hover:text-purple-600 transition-all border border-[#E8DCCB]">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M8 24c2.2 0 4-1.8 4-4v-4H8c-2.2 0-4 1.8-4 4s1.8 4 4 4zM4 12c0-2.2 1.8-4 4-4h4v8H8c-2.2 0-4-1.8-4-4zM4 4c0-2.2 1.8-4 4-4h4v8H8C5.8 8 4 6.2 4 4zM12 0h4c2.2 0 4 1.8 4 4s-1.8 4-4 4h-4V0zM20 12c0 2.2-1.8 4-4 4s-4-1.8-4-4 1.8-4 4-4 4 1.8 4 4z"/></svg>
            Figma
          </button>

          <div class="w-px h-6 bg-[#E8DCCB] mx-1"></div>

          <!-- Undo/Redo/Clear/Export -->
          <button @click="undo" title="Undo" class="w-9 h-9 rounded-xl flex items-center justify-center text-[#8A7A6C] hover:bg-[#F8F1E7] hover:text-[#2B1E16] transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a5 5 0 015 5v2M3 10l4-4M3 10l4 4" /></svg>
          </button>
          <button @click="redo" title="Redo" class="w-9 h-9 rounded-xl flex items-center justify-center text-[#8A7A6C] hover:bg-[#F8F1E7] hover:text-[#2B1E16] transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 10H11a5 5 0 00-5 5v2M21 10l-4-4M21 10l-4 4" /></svg>
          </button>
          <button @click="clearCanvas" title="Hapus Semua" class="w-9 h-9 rounded-xl flex items-center justify-center text-[#8A7A6C] hover:bg-red-50 hover:text-[#D94A38] transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
          </button>
          <button @click="exportCanvas" title="Export PNG" class="w-9 h-9 rounded-xl flex items-center justify-center text-[#8A7A6C] hover:bg-[#0F8A4B]/10 hover:text-[#0F8A4B] transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
          </button>
        </div>
      </div>

      <!-- Hidden file input -->
      <input ref="fileInput" type="file" accept="image/*" class="hidden" @change="onImageSelected" />
      <input ref="figmaInput" type="file" accept=".svg,image/svg+xml,image/*" class="hidden" @change="onFigmaImport" />
    </div>

    <!-- Chat Sidebar / Mobile Toggle Drawer -->
    <div 
      class="w-full md:w-72 bg-white rounded-2xl border border-[#E8DCCB]/60 flex flex-col overflow-hidden transition-all duration-300 z-10"
      :class="isMobileChatOpen ? 'fixed inset-x-4 bottom-4 top-20 md:relative md:inset-auto md:w-72' : 'hidden md:flex'"
    >
      <div class="px-4 py-3 border-b border-[#E8DCCB]/60 bg-[#FFFCF7] flex items-center justify-between">
        <h3 class="text-[11px] font-bold uppercase tracking-wider text-[#2B1E16]">Live Chat</h3>
        <button @click="isMobileChatOpen = false" class="md:hidden text-[#8A7A6C] hover:text-[#2B1E16]">✕</button>
      </div>

      <div class="flex-1 overflow-y-auto p-4 space-y-3 no-scrollbar" ref="chatContainer">
        <div class="text-center my-2">
          <span class="text-[9px] font-bold uppercase tracking-wider text-[#8A7A6C] bg-[#F8F1E7] px-2.5 py-1 rounded-full">Ruangan Aktif</span>
        </div>
        <div v-for="(msg, idx) in chatMessages" :key="idx" class="flex flex-col" :class="msg.isSelf ? 'items-end' : 'items-start'">
          <span class="text-[9px] text-[#8A7A6C] font-bold mb-0.5 px-1">{{ msg.sender }}</span>
          <div class="px-3 py-2 rounded-2xl max-w-[85%] text-[12px]" :class="msg.isSelf ? 'bg-[#B85C38] text-white rounded-br-sm' : 'bg-[#F8F1E7] text-[#2B1E16] rounded-bl-sm'">
            {{ msg.text }}
          </div>
        </div>
      </div>

      <div class="p-3 border-t border-[#E8DCCB]/60">
        <form @submit.prevent="sendMessage" class="relative">
          <input v-model="newMessage" type="text" placeholder="Ketik pesan..." class="w-full bg-[#FFFCF7] border border-[#E8DCCB] rounded-xl pl-3 pr-10 py-2.5 text-[12px] font-medium text-[#2B1E16] outline-none focus:border-[#B85C38] focus:ring-2 focus:ring-[#B85C38]/10 transition-all" />
          <button type="submit" :disabled="!newMessage.trim()" class="absolute right-2 top-1/2 -translate-y-1/2 w-7 h-7 bg-[#B85C38] text-white rounded-lg flex items-center justify-center disabled:opacity-40 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
          </button>
        </form>
      </div>
    </div>

    <!-- Mobile floating chat toggle -->
    <button 
      @click="isMobileChatOpen = !isMobileChatOpen" 
      class="md:hidden fixed bottom-6 right-6 w-12 h-12 rounded-full bg-[#B85C38] text-white flex items-center justify-center shadow-lg z-30 active:scale-95 transition-transform"
    >
      <svg v-if="!isMobileChatOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
      <span v-else>✕</span>
    </button>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, nextTick, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import * as fabric from 'fabric';

const props = defineProps({
  role: {
    type: String,
    required: true
  }
});

const route = useRoute();
const router = useRouter();
const room = ref(null);
const loading = ref(true);
const user = ref(JSON.parse(localStorage.getItem('user') || '{}'));
const newMessage = ref('');
const chatMessages = ref([
  { sender: 'Sistem', text: 'Selamat datang di Co-Create Room. Kolaborasi desain dimulai di sini.', isSelf: false }
]);
const chatContainer = ref(null);
const canvasEl = ref(null);
const containerEl = ref(null);
const fileInput = ref(null);
const figmaInput = ref(null);
const mode = ref('draw');
const hasContent = ref(false);
const brushColor = ref('#B85C38');
const brushSize = ref(4);
const isMobileChatOpen = ref(false);
const showAutoSave = ref(false);

let canvas = null;
let undoStack = [];
let redoStack = [];
let pollingInterval = null;
let lastCanvasLoadTimestamp = null;
let isLocallyModifying = false; // Flag agar polling tidak overwrite perubahan lokal
let saveDebounceTimer = null; // Debounce timer untuk auto-save ke server

const isCreator = computed(() => room.value?.creator_id === user.value?.id);

const colors = ['#2B1E16', '#B85C38', '#C9A45C', '#0F8A4B', '#243B53', '#D94A38', '#6F7D4C', '#8B5CF6', '#EC4899', '#F59E0B'];
const showTemplates = ref(false);
const templateDropdown = ref(null);

const fashionTemplates = [
  { id: 'tshirt', name: 'Kaos / T-Shirt', icon: '👕' },
  { id: 'shirt', name: 'Kemeja', icon: '👔' },
  { id: 'dress', name: 'Dress / Gaun', icon: '👗' },
  { id: 'pants', name: 'Celana', icon: '👖' },
  { id: 'bag', name: 'Tas / Tote Bag', icon: '👜' },
  { id: 'scarf', name: 'Syal / Hijab', icon: '🧣' },
  { id: 'packaging', name: 'Packaging Box', icon: '📦' },
];

const drawingTools = [
  { id: 'select', label: 'Pilih (V)', path: '<path stroke-linecap="round" stroke-linejoin="round" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5" />' },
  { id: 'draw', label: 'Pen (P)', path: '<path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />' },
  { id: 'eraser', label: 'Eraser (E)', path: '<path stroke-linecap="round" stroke-linejoin="round" d="M4 4l7.07 7.07m0 0L4 18.14m7.07-7.07L18.14 4m-7.07 7.07L18.14 18.14" />' },
];

const goBack = () => {
  router.push(`/${props.role}/cocreate`);
};

const closeRoom = async () => {
  if (!confirm('Apakah Anda yakin ingin menutup ruangan kolaborasi ini secara permanen?')) return;
  try {
    const token = localStorage.getItem('token');
    const res = await fetch(`/api/v1/cocreate/rooms/${route.params.id}/close`, {
      method: 'PATCH',
      headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
    });
    if (res.ok) {
      alert('Room telah ditutup.');
      goBack();
    }
  } catch (e) {
    console.error(e);
  }
};

const fetchRoomDetail = async () => {
  try {
    const token = localStorage.getItem('token');
    const res = await fetch(`/api/v1/cocreate/rooms/${route.params.id}`, {
      headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
    });
    if (res.ok) {
      const data = await res.json();
      room.value = data.data;
    } else {
      goBack();
    }
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
};

const initCanvas = () => {
  if (!canvasEl.value) return;
  canvas = new fabric.Canvas(canvasEl.value, { isDrawingMode: true, selection: true });
  if (canvas.freeDrawingBrush) {
    canvas.freeDrawingBrush.color = brushColor.value;
    canvas.freeDrawingBrush.width = brushSize.value;
  }

  // Interactivity tracking to pause polling updates during active modifications
  const startModify = () => { isLocallyModifying = true; };
  const endModify = () => {
    isLocallyModifying = false;
    hasContent.value = canvas.getObjects().length > 0;
    saveState();
    uploadCanvasDataToServer();
  };

  canvas.on('path:created', () => { hasContent.value = true; saveState(); uploadCanvasDataToServer(); });
  canvas.on('object:added', () => { hasContent.value = true; });
  canvas.on('object:moving', startModify);
  canvas.on('object:scaling', startModify);
  canvas.on('object:rotating', startModify);
  canvas.on('object:modified', endModify);
  canvas.on('selection:created', startModify);
  canvas.on('selection:cleared', () => { isLocallyModifying = false; });

  const ro = new ResizeObserver((entries) => {
    for (let e of entries) {
      if (e.target === containerEl.value) {
        canvas.setDimensions({ width: e.contentRect.width, height: e.contentRect.height });
      }
    }
  });
  if (containerEl.value) ro.observe(containerEl.value);
  onUnmounted(() => { ro.disconnect(); canvas.dispose(); });
};

const saveState = () => {
  undoStack.push(JSON.stringify(canvas.toJSON()));
  if (undoStack.length > 30) undoStack.shift();
  redoStack = [];
};

const undo = () => {
  if (undoStack.length === 0) return;
  redoStack.push(JSON.stringify(canvas.toJSON()));
  const prev = undoStack.pop();
  canvas.loadFromJSON(prev, () => {
    canvas.renderAll();
    uploadCanvasDataToServer();
  });
};

const redo = () => {
  if (redoStack.length === 0) return;
  undoStack.push(JSON.stringify(canvas.toJSON()));
  const next = redoStack.pop();
  canvas.loadFromJSON(next, () => {
    canvas.renderAll();
    uploadCanvasDataToServer();
  });
};

const setMode = (m) => {
  mode.value = m;
  if (!canvas) return;
  if (m === 'draw') {
    canvas.isDrawingMode = true;
    if (canvas.freeDrawingBrush) {
      canvas.freeDrawingBrush.color = brushColor.value;
      canvas.freeDrawingBrush.width = brushSize.value;
    }
  } else if (m === 'eraser') {
    canvas.isDrawingMode = true;
    if (canvas.freeDrawingBrush) {
      canvas.freeDrawingBrush.color = '#FAFAF8';
      canvas.freeDrawingBrush.width = 20;
    }
  } else {
    canvas.isDrawingMode = false;
    canvas.selection = true;
  }
};

const setBrushColor = (c) => {
  brushColor.value = c;
  if (canvas && canvas.freeDrawingBrush && mode.value === 'draw') {
    canvas.freeDrawingBrush.color = c;
  }
};

const setBrushSize = (s) => {
  brushSize.value = s;
  if (canvas && canvas.freeDrawingBrush && mode.value === 'draw') {
    canvas.freeDrawingBrush.width = s;
  }
};

const addRect = () => {
  if (!canvas) return;
  const rect = new fabric.Rect({ left: 100, top: 100, width: 120, height: 80, fill: 'transparent', stroke: brushColor.value, strokeWidth: 2, rx: 8, ry: 8 });
  canvas.add(rect); canvas.setActiveObject(rect); setMode('select'); saveState(); uploadCanvasDataToServer();
};

const addCircle = () => {
  if (!canvas) return;
  const circle = new fabric.Circle({ left: 150, top: 150, radius: 50, fill: 'transparent', stroke: brushColor.value, strokeWidth: 2 });
  canvas.add(circle); canvas.setActiveObject(circle); setMode('select'); saveState(); uploadCanvasDataToServer();
};

const addText = () => {
  if (!canvas) return;
  const text = new fabric.IText('Teks', { left: 100, top: 100, fontSize: 24, fontFamily: 'sans-serif', fill: brushColor.value });
  canvas.add(text); canvas.setActiveObject(text); setMode('select'); saveState(); uploadCanvasDataToServer();
};

const addLine = () => {
  if (!canvas) return;
  const line = new fabric.Line([50, 100, 250, 100], { stroke: brushColor.value, strokeWidth: 2 });
  canvas.add(line); canvas.setActiveObject(line); setMode('select'); saveState(); uploadCanvasDataToServer();
};

const importImage = () => { fileInput.value?.click(); };

const onImageSelected = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = (ev) => {
    fabric.FabricImage.fromURL(ev.target.result, { crossOrigin: 'anonymous' }).then((img) => {
      img.scaleToWidth(300);
      canvas.add(img); canvas.setActiveObject(img); setMode('select'); saveState(); hasContent.value = true; uploadCanvasDataToServer();
    });
  };
  reader.readAsDataURL(file);
  e.target.value = '';
};

const importFromFigma = () => { figmaInput.value?.click(); };

const onFigmaImport = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  if (file.type === 'image/svg+xml' || file.name.endsWith('.svg')) {
    const reader = new FileReader();
    reader.onload = (ev) => {
      fabric.loadSVGFromString(ev.target.result).then((result) => {
        const group = fabric.util.groupSVGElements(result.objects, result.options);
        group.scaleToWidth(Math.min(400, canvas.width * 0.6));
        canvas.add(group); canvas.centerObject(group); canvas.setActiveObject(group); setMode('select'); saveState(); hasContent.value = true; uploadCanvasDataToServer();
      });
    };
    reader.readAsText(file);
  } else {
    const reader = new FileReader();
    reader.onload = (ev) => {
      fabric.FabricImage.fromURL(ev.target.result, { crossOrigin: 'anonymous' }).then((img) => {
        img.scaleToWidth(300);
        canvas.add(img); canvas.setActiveObject(img); setMode('select'); saveState(); hasContent.value = true; uploadCanvasDataToServer();
      });
    };
    reader.readAsDataURL(file);
  }
  e.target.value = '';
};

const addTriangle = () => {
  if (!canvas) return;
  const tri = new fabric.Triangle({ left: 120, top: 120, width: 100, height: 100, fill: 'transparent', stroke: brushColor.value, strokeWidth: 2 });
  canvas.add(tri); canvas.setActiveObject(tri); setMode('select'); saveState(); uploadCanvasDataToServer();
};

const addArrow = () => {
  if (!canvas) return;
  const line = new fabric.Line([50, 150, 250, 150], { stroke: brushColor.value, strokeWidth: 2 });
  const head = new fabric.Triangle({ left: 240, top: 140, width: 16, height: 20, fill: brushColor.value, angle: 90 });
  const group = new fabric.Group([line, head], { left: 100, top: 100 });
  canvas.add(group); canvas.setActiveObject(group); setMode('select'); saveState(); uploadCanvasDataToServer();
};

const addFashionTemplate = (templateId) => {
  if (!canvas) return;
  const templates = {
    tshirt: 'M12 3C9.5 3 7.5 4 6 5L3 8v4l3-1v10h12V11l3 1V8l-3-3c-1.5-1-3.5-2-6-2z',
    shirt: 'M8 3l-5 3v3l3-1v13h12V8l3 1V6l-5-3c-1 0-2 1-4 1s-3-1-4-1z',
    dress: 'M9 3h6l2 5-2 1v12H9V9L7 8l2-5z',
    pants: 'M7 3h10v8l-2 10h-2l-1-10-1 10h-2L7 11V3z',
    bag: 'M6 8h12v13H6V8zm3-5c0-1 1.5-2 3-2s3 1 3 2v5H9V3z',
    scarf: 'M4 6c2-2 5-2 8 0s6 2 8 0v3c-2 2-5 2-8 0s-6-2-8 0v9c2-2 5-2 8 0s6 2 8 0v3c-2 2-5 2-8 0s-6-2-8 0V6z',
    packaging: 'M3 8l9-5 9 5v10l-9 5-9-5V8zm9-5v20m-9-15l9 5 9-5',
  };
  const pathData = templates[templateId] || templates.tshirt;
  const path = new fabric.Path(pathData, {
    left: 100, top: 80, fill: 'transparent', stroke: brushColor.value, strokeWidth: 2,
    scaleX: 8, scaleY: 8,
  });
  canvas.add(path); canvas.setActiveObject(path); setMode('select'); saveState(); hasContent.value = true; uploadCanvasDataToServer();
};

const addPattern = () => {
  if (!canvas) return;
  const objects = [];
  for (let i = 0; i < 5; i++) {
    for (let j = 0; j < 5; j++) {
      objects.push(new fabric.Circle({
        left: i * 30, top: j * 30, radius: 8,
        fill: 'transparent', stroke: brushColor.value, strokeWidth: 1,
      }));
      objects.push(new fabric.Circle({
        left: i * 30 + 5, top: j * 30 + 5, radius: 3,
        fill: brushColor.value,
      }));
    }
  }
  const group = new fabric.Group(objects, { left: 100, top: 100 });
  canvas.add(group); canvas.setActiveObject(group); setMode('select'); saveState(); hasContent.value = true; uploadCanvasDataToServer();
};

const flipHorizontal = () => {
  if (!canvas) return;
  const obj = canvas.getActiveObject();
  if (obj) { obj.set('flipX', !obj.flipX); canvas.renderAll(); saveState(); uploadCanvasDataToServer(); }
};

const clearCanvas = () => {
  if (!confirm('Hapus seluruh kanvas?')) return;
  if (canvas) { saveState(); canvas.clear(); hasContent.value = false; uploadCanvasDataToServer(); }
};

const exportCanvas = () => {
  if (!canvas) return;
  const dataURL = canvas.toDataURL({ format: 'png', quality: 1 });
  const link = document.createElement('a');
  link.download = `cocreate-${room.value?.name || 'design'}.png`;
  link.href = dataURL;
  link.click();
};

const sendMessage = async () => {
  if (!newMessage.value.trim()) return;
  const text = newMessage.value.trim();
  newMessage.value = '';
  
  chatMessages.value.push({ sender: user.value.name, text, isSelf: true });
  nextTick(() => { if (chatContainer.value) chatContainer.value.scrollTop = chatContainer.value.scrollHeight; });

  try {
    const token = localStorage.getItem('token');
    await fetch(`/api/v1/cocreate/rooms/${route.params.id}/messages`, {
      method: 'POST',
      headers: { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify({ message: text }),
    });
  } catch (e) { console.error('Failed to send message:', e); }
};

const fetchMessages = async () => {
  try {
    const token = localStorage.getItem('token');
    const res = await fetch(`/api/v1/cocreate/rooms/${route.params.id}/messages`, {
      headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
    });
    if (res.ok) {
      const data = await res.json();
      if (data.data) {
        const newMsgs = [
          { sender: 'Sistem', text: 'Selamat datang di Co-Create Room. Kolaborasi desain dimulai di sini.', isSelf: false },
          ...data.data.map(m => ({
            sender: m.user?.name || 'Unknown',
            text: m.message,
            isSelf: m.user_id === user.value.id,
          }))
        ];
        
        // Only update if message count is different to avoid layout flickering
        if (chatMessages.value.length !== newMsgs.length) {
          chatMessages.value = newMsgs;
          nextTick(() => { if (chatContainer.value) chatContainer.value.scrollTop = chatContainer.value.scrollHeight; });
        }
      }
    }
  } catch (e) { console.error('Failed to fetch messages:', e); }
};

/* ─── REALTIME COLLABORATION / CANVAS SYNC FUNCTIONS ─── */

// Debounced save: menunggu 1 detik setelah terakhir mengedit sebelum upload ke server
const uploadCanvasDataToServer = () => {
  if (!canvas) return;
  // Cancel debounce sebelumnya
  if (saveDebounceTimer) clearTimeout(saveDebounceTimer);
  saveDebounceTimer = setTimeout(async () => {
    try {
      const token = localStorage.getItem('token');
      const canvasJSON = JSON.stringify(canvas.toJSON());
      const res = await fetch(`/api/v1/cocreate/rooms/${route.params.id}/canvas`, {
        method: 'POST',
        headers: { 
          'Authorization': `Bearer ${token}`, 
          'Content-Type': 'application/json', 
          'Accept': 'application/json' 
        },
        body: JSON.stringify({ canvas_data: canvasJSON }),
      });
      if (res.ok) {
        const data = await res.json();
        lastCanvasLoadTimestamp = data.canvas_updated_at;
        // Tampilkan notifikasi auto-save
        showAutoSave.value = true;
        setTimeout(() => { showAutoSave.value = false; }, 2000);
      }
    } catch (e) {
      console.error('Failed to save canvas state:', e);
    }
  }, 1000); // Debounce 1 detik
};

const fetchCanvasFromServer = async () => {
  if (!canvas || isLocallyModifying) return;
  try {
    const token = localStorage.getItem('token');
    const res = await fetch(`/api/v1/cocreate/rooms/${route.params.id}/canvas`, {
      headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
    });
    if (res.ok) {
      const data = await res.json();
      
      // Update local canvas hanya jika server state lebih baru
      if (data.canvas_updated_at && data.canvas_updated_at !== lastCanvasLoadTimestamp) {
        lastCanvasLoadTimestamp = data.canvas_updated_at;
        
        if (data.canvas_data) {
          canvas.loadFromJSON(JSON.parse(data.canvas_data), () => {
            canvas.renderAll();
            hasContent.value = canvas.getObjects().length > 0;
          });
        } else {
          canvas.clear();
          hasContent.value = false;
        }
      }
    }
  } catch (e) {
    console.error('Failed to load canvas state:', e);
  }
};

// Hapus objek yang terpilih (untuk keyboard shortcut Delete/Backspace)
const deleteSelected = () => {
  if (!canvas) return;
  const active = canvas.getActiveObjects();
  if (active.length > 0) {
    active.forEach(obj => canvas.remove(obj));
    canvas.discardActiveObject();
    canvas.renderAll();
    hasContent.value = canvas.getObjects().length > 0;
    saveState();
    uploadCanvasDataToServer();
  }
};

// Handler keyboard shortcuts global
const handleKeyboard = (e) => {
  // Jangan tangkap shortcut saat user mengetik di input/textarea
  if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;

  // Ctrl+Z = Undo
  if ((e.ctrlKey || e.metaKey) && e.key === 'z' && !e.shiftKey) {
    e.preventDefault();
    undo();
  }
  // Ctrl+Y atau Ctrl+Shift+Z = Redo
  if ((e.ctrlKey || e.metaKey) && (e.key === 'y' || (e.key === 'z' && e.shiftKey))) {
    e.preventDefault();
    redo();
  }
  // Delete / Backspace = Hapus objek terpilih
  if (e.key === 'Delete' || e.key === 'Backspace') {
    // Pastikan tidak sedang mengedit teks di canvas
    if (canvas && !canvas.getActiveObject()?.isEditing) {
      e.preventDefault();
      deleteSelected();
    }
  }
};

onMounted(() => {
  fetchRoomDetail();
  initCanvas();
  fetchMessages();
  fetchCanvasFromServer();

  // Register keyboard shortcuts
  window.addEventListener('keydown', handleKeyboard);

  // Polling: cek setiap 3 detik untuk sinkronisasi real-time
  pollingInterval = setInterval(() => {
    fetchMessages();
    fetchCanvasFromServer();
  }, 3000);
});

onUnmounted(() => {
  if (pollingInterval) clearInterval(pollingInterval);
  if (saveDebounceTimer) clearTimeout(saveDebounceTimer);
  window.removeEventListener('keydown', handleKeyboard);
});
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
