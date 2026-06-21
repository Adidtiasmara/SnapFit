<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiImageGenerationService
{
    /**
     * Generate product images using AI
     */
    public function generateProductImages(string $originalImageUrl, string $prompt): array
    {
        $analysis = null;
        $images = [];

        // Step 1: Get AI text analysis from Groq
        if ($groqKey = config('services.groq.api_key')) {
            $analysis = $this->getTextAnalysis($originalImageUrl, $prompt, $groqKey);
        }

        // Step 2: Generate images with Replicate (Flux)
        if ($replicateKey = config('services.replicate.api_key')) {
            $images = $this->generateWithReplicate($originalImageUrl, $prompt, $replicateKey);
        }

        // Fallback: Jika API eksternal gagal atau tidak terkonfigurasi, buat data fallback yang realistis
        if (empty($analysis) || empty($images)) {
            $fallback = $this->generateFallbackData($originalImageUrl, $prompt);
            if (empty($analysis)) {
                $analysis = $fallback['analysis'];
            }
            if (empty($images)) {
                $images = $fallback['images'];
            }
        }

        return [
            'images' => $images,
            'analysis' => $analysis,
        ];
    }

    /**
     * Generate fallback mock analysis and images when APIs fail or are unconfigured.
     */
    public function generateFallbackData(string $originalImageUrl, string $prompt): array
    {
        // Deteksi kategori produk dari URL atau prompt
        $category = 'fashion'; // default
        $promptLower = strtolower($prompt);
        if (str_contains($promptLower, 'batik') || str_contains($originalImageUrl, 'batik')) {
            $category = 'batik';
        } elseif (str_contains($promptLower, 'kebaya') || str_contains($originalImageUrl, 'kebaya')) {
            $category = 'kebaya';
        } elseif (str_contains($promptLower, 'aksesoris') || str_contains($promptLower, 'gelang') || str_contains($promptLower, 'bros') || str_contains($originalImageUrl, 'aksesoris') || str_contains($originalImageUrl, 'gelang')) {
            $category = 'aksesoris';
        } elseif (str_contains($promptLower, 'tenun') || str_contains($promptLower, 'songket') || str_contains($originalImageUrl, 'tenun') || str_contains($originalImageUrl, 'songket')) {
            $category = 'tenun';
        }

        // Deteksi gaya visual yang dipilih
        $style = 'studio'; // default
        if (str_contains($promptLower, 'minimalis')) {
            $style = 'minimalis';
        } elseif (str_contains($promptLower, 'vibrant') || str_contains($promptLower, 'warna')) {
            $style = 'vibrant';
        } elseif (str_contains($promptLower, 'hangat') || str_contains($promptLower, 'warm') || str_contains($promptLower, 'aesthetic')) {
            $style = 'warm';
        } elseif (str_contains($promptLower, 'natural') || str_contains($promptLower, 'outdoor')) {
            $style = 'natural';
        } elseif (str_contains($promptLower, 'katalog') || str_contains($promptLower, 'clean') || str_contains($promptLower, 'white')) {
            $style = 'catalog';
        }

        // Gunakan gambar original sebagai fallback (bukan path statis yang tidak ada di server)
        $fallbackImages = [$originalImageUrl];

        // Buat markdown report yang sangat profesional dalam Bahasa Indonesia
        $styleTitle = ucfirst($style);
        if ($style === 'warm') $styleTitle = 'Aesthetic Hangat';
        if ($style === 'natural') $styleTitle = 'Natural Light';
        if ($style === 'catalog') $styleTitle = 'Catalog Clean';
        if ($style === 'studio') $styleTitle = 'Studio Premium';

        $analysis = "### 🌟 Analisis AI Product Studio (Mode Fallback Kreatif)

Laporan arahan kreatif dan optimasi visual untuk produk Anda dengan gaya **{$styleTitle}**:

#### 1. 📸 Rekomendasi Visual & Penataan Gaya
- **Latar Belakang**: Latar belakang disesuaikan dengan konsep *{$styleTitle}*. Penggunaan elemen alam atau pencahayaan studio terfokus untuk menonjolkan detail jahitan dan motif produk.
- **Pencahayaan**: Menggunakan teknik pencahayaan lembut (*soft diffuse lighting*) dari samping untuk meminimalkan bayangan keras dan memunculkan keaslian warna kain.
- **Sudut Pengambilan Gambar (Angle)**: Rekomendasi sudut *eye-level* untuk menonjolkan siluet pakaian, diikuti dengan *close-up shot* pada detail kerah, manset, atau motif tenunan.
- **Properti Pendukung**: Gantungan kayu estetik, tanaman hijau minimalis (*monstera* atau *eucalyptus*), dan lipatan kain bertekstur netral di latar belakang.

#### 2. ✍️ Pilihan Caption Pemasaran (Instagram / TikTok)
- **Opsi 1 (Casual & Relatable)**:
  > Tampil percaya diri di setiap kesempatan dengan koleksi terbaik kami! Didesain khusus menggunakan bahan premium yang super adem dan nyaman dipakai seharian. Siap-siap jadi pusat perhatian ya! ✨ #GayaLokal #FashionModern
- **Opsi 2 (Profesional & Edukatif)**:
  > **Sentuhan Tradisi untuk Gaya Hidup Modern.** Produk ini memadukan keindahan warisan budaya dengan potongan modern yang ergonomis. Dibuat secara handmade oleh perajin lokal pilihan untuk memastikan presisi di setiap jahitan. Investasi gaya terbaik untuk lemari pakaian Anda. 💼
- **Opsi 3 (Persuasif & Promosional)**:
  > *STOK TERBATAS!* Dapatkan kenyamanan dan kemewahan dalam satu balutan produk premium. Khusus minggu ini, nikmati penawaran spesial potongan harga 15% untuk pembelian pertama Anda. Klik link di bio untuk order sekarang sebelum kehabisan! 🛍️✨

#### 3. 🎨 Target Audiens & Vibe
- **Target Pasar**: Profesional muda, milenial, dan pencinta mode lokal yang peduli dengan kualitas bahan dan keberlanjutan produk (sustainable fashion).
- **Vibe / Nuansa**: Elegan, modern, ramah lingkungan, dan premium.

#### 4. 🏷️ Rekomendasi Hashtag Trending
`#snapfitai` `#lokalbrand` `#indonesianheritage` `#ootdindo` `#fashionpremium` `#batikmodern` `#umkmnaikkelas` `#supportlocal` `#modernstyle` `#kreatiflokal`";

        return [
            'images' => $fallbackImages,
            'analysis' => $analysis,
        ];
    }

    /**
     * Get text analysis from Groq (menggunakan Vision model agar bisa menganalisis gambar produk)
     */
    private function getTextAnalysis(string $imageUrl, string $prompt, string $apiKey): ?string
    {
        try {
            // Gunakan vision model agar AI bisa melihat dan menganalisis gambar produk secara langsung
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
            ])->timeout(60)->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.2-11b-vision-preview',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Kamu adalah seorang senior creative director dan ahli fotografi produk profesional yang membantu UMKM Indonesia. Berikan analisis dalam Bahasa Indonesia yang detail, profesional, dan actionable. Gunakan format Markdown yang rapi.',
                    ],
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => "Analisis gambar produk ini dan berikan laporan AI Product Studio berdasarkan arahan gaya: '{$prompt}'.\n\nBerikan laporan lengkap dalam format Markdown meliputi:\n\n### 📸 Analisis Visual Produk\n- Deskripsi detail apa yang terlihat di gambar (warna dominan, bentuk, tekstur, detail produk)\n- Rekomendasi pencahayaan (lighting setup ideal)\n- Rekomendasi sudut kamera (angle) terbaik\n- Rekomendasi background/latar belakang\n- Rekomendasi properti pendukung styling\n\n### ✍️ Caption Pemasaran\nBuatkan 3 variasi caption untuk Instagram/TikTok:\n- **Opsi 1 (Casual & Relatable):** caption santai dengan emoji\n- **Opsi 2 (Profesional & Edukatif):** caption formal dan informatif\n- **Opsi 3 (Persuasif & Promosional):** caption untuk hard selling\n\n### 🏷️ Hashtag Trending\n10 hashtag relevan dan trending untuk produk ini\n\n### 🎨 Target Audiens & Vibe\n- Target pasar ideal\n- Aesthetic/vibe yang cocok\n- Platform terbaik untuk promosi",
                            ],
                            [
                                'type' => 'image_url',
                                'image_url' => [
                                    'url' => $imageUrl,
                                ],
                            ],
                        ],
                    ],
                ],
                'max_tokens' => 2000,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content', '');
            }

            Log::error('Groq Analysis Error', ['status' => $response->status(), 'body' => $response->body()]);
        } catch (\Exception $e) {
            Log::error('Groq Analysis Exception', ['error' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * Generate images using Replicate API (Flux Schnell - free model)
     */
    private function generateWithReplicate(string $imageUrl, string $prompt, string $apiKey): array
    {
        try {
            $enhancedPrompt = $this->enhancePrompt($prompt);

            // Use flux-schnell which is free on Replicate
            $response = Http::withHeaders([
                'Authorization' => "Token {$apiKey}",
                'Content-Type' => 'application/json',
                'Prefer' => 'wait',
            ])->timeout(120)->post('https://api.replicate.com/v1/models/black-forest-labs/flux-schnell/predictions', [
                'input' => [
                    'prompt' => $enhancedPrompt,
                    'num_outputs' => 1,
                    'aspect_ratio' => '1:1',
                    'output_format' => 'webp',
                    'output_quality' => 80,
                ],
            ]);

            if ($response->successful()) {
                $output = $response->json('output');

                if ($output) {
                    $images = is_array($output) ? $output : [$output];
                    Log::info('Replicate Flux Schnell success', ['count' => count($images)]);
                    return $images;
                }

                // If async, poll
                $predictionUrl = $response->json('urls.get');
                if ($predictionUrl) {
                    return $this->pollReplicate($predictionUrl, $apiKey);
                }
            }

            Log::error('Replicate API Error', ['status' => $response->status(), 'body' => $response->body()]);
        } catch (\Exception $e) {
            Log::error('Replicate Exception', ['error' => $e->getMessage()]);
        }

        return [];
    }

    /**
     * Poll Replicate for async prediction result
     */
    private function pollReplicate(string $url, string $apiKey): array
    {
        for ($i = 0; $i < 30; $i++) {
            sleep(2);
            try {
                $res = Http::withHeaders(['Authorization' => "Token {$apiKey}"])->get($url);
                if ($res->successful()) {
                    $status = $res->json('status');
                    if ($status === 'succeeded') {
                        $output = $res->json('output');
                        return is_array($output) ? $output : [$output];
                    }
                    if ($status === 'failed' || $status === 'canceled') break;
                }
            } catch (\Exception $e) {
                Log::error('Replicate poll error', ['error' => $e->getMessage()]);
                break;
            }
        }
        return [];
    }

    /**
     * Generate with OpenRouter as fallback for text analysis
     */
    private function generateWithOpenRouter(string $imageUrl, string $prompt, string $apiKey): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
                'HTTP-Referer' => config('app.url'),
                'X-Title' => 'SnapFit AI Studio',
            ])->timeout(60)->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'openrouter/free',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a professional product photographer and creative director helping Indonesian UMKM. Answer in Indonesian.',
                    ],
                    [
                        'role' => 'user',
                        'content' => "Analyze this product photography prompt: '{$prompt}'. Image URL: {$imageUrl}\n\nProvide a detailed report in Markdown with: styling recommendations, 3 caption variations, 10 hashtags, and target audience analysis.",
                    ],
                ],
                'max_tokens' => 2000,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                return ['images' => [], 'analysis' => $response->json('choices.0.message.content', '')];
            }
        } catch (\Exception $e) {
            Log::error('OpenRouter Exception', ['error' => $e->getMessage()]);
        }

        return ['images' => [], 'analysis' => null];
    }

    /**
     * Enhance prompt for image generation
     */
    private function enhancePrompt(string $prompt): string
    {
        $base = "Professional product photography, high quality, studio lighting, clean background, e-commerce catalog style";
        return empty($prompt) ? $base : "{$prompt}, {$base}";
    }
}
