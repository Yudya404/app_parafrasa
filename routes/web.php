<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    Log::info('🌐 Akses halaman utama aplikasi parafrasa');
    return view('halamanUtama');
});

Route::post('/parafrase', function (Request $request) {
    Log::info('📥 Menerima permintaan POST /parafrase', [
        'has_text' => $request->has('message'),
        'has_file' => $request->hasFile('file'),
        'client_ip' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);

    $text = $request->input('message');
    $file = $request->file('file');

    // ==========================
    // 🧾 MODE FILE DOCX
    // ==========================
    if ($file) {
        try {
            Log::info('📄 File diterima untuk parafrasa', [
                'filename' => $file->getClientOriginalName(),
                'mime' => $file->getMimeType(),
                'size_kb' => round($file->getSize() / 1024, 2)
            ]);

            $response = Http::timeout(300)
                ->attach(
                    'file',
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                )
                ->post('http://127.0.0.1:5000/parafrase_docx');

            Log::info('📤 File dikirim ke Flask', [
                'status' => $response->status(),
                'duration_ms' => $response->transferStats?->getTransferTime() * 1000 ?? null
            ]);

            if ($response->failed()) {
                Log::error('❌ Flask gagal memproses file', [
                    'status_code' => $response->status(),
                    'body' => $response->body()
                ]);
                return response()->json(['error' => 'Gagal memproses file dari server AI.'], 500);
            }

            // Simpan hasil DOCX sementara
            $filename = 'Parafrase_' . time() . '.docx';
            $path = storage_path('app/public/' . $filename);
            file_put_contents($path, $response->body());

            Log::info('✅ File berhasil diparafrase', [
                'output_path' => $path
            ]);

            // === Kembalikan langsung file (agar fetch() menangkap blob)
            return response()->download($path, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ]);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('⚠️ Tidak dapat terhubung ke Flask (mode file)', [
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Tidak dapat terhubung ke server AI.'], 500);
        } catch (\Exception $e) {
            Log::error('🔥 Error saat memproses file DOCX', [
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ==========================
    // 💬 MODE TEKS
    // ==========================
    if ($text) {
        try {
            Log::info('💬 Teks diterima untuk parafrasa', [
                'length' => strlen($text),
                'preview' => substr($text, 0, 120)
            ]);

            $response = Http::timeout(300)
                ->asForm()
                ->post('http://127.0.0.1:5000/parafrase_text', ['text' => $text]);

            Log::info('📤 Teks dikirim ke Flask', [
                'status' => $response->status(),
                'duration_ms' => $response->transferStats?->getTransferTime() * 1000 ?? null
            ]);

            if ($response->failed()) {
                Log::error('❌ Flask gagal memproses teks', [
                    'status_code' => $response->status(),
                    'body' => $response->body()
                ]);
                return response()->json(['error' => 'Gagal memproses teks dari server AI.'], 500);
            }

            $result = $response->json()['result'] ?? null;
            Log::info('✅ Teks berhasil diparafrase', [
                'result_length' => strlen($result ?? ''),
                'preview' => substr($result ?? '', 0, 120)
            ]);

            // === Return JSON agar JS bisa tampilkan hasil teks
            return response()->json(['result' => $result], 200);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('⚠️ Tidak dapat terhubung ke Flask (mode teks)', [
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Tidak dapat terhubung ke server AI.'], 500);
        } catch (\Exception $e) {
            Log::error('🔥 Error saat memproses teks', [
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ==========================
    // ⚠️ Tidak ada input
    // ==========================
    Log::warning('⚠️ Tidak ada input yang dikirim user.');
    return response()->json(['error' => 'Harap isi teks atau upload file.'], 400);
})->name('parafrase.proses');