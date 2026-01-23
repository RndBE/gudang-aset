<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\SatuanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;


class BarangController extends Controller
{
    public function index()
    {
        $instansiId = Auth::user()->instansi_id;

        $data = Barang::with(['kategori', 'satuan'])
            ->where('instansi_id', $instansiId)
            ->orderBy('nama')
            ->get();

        return view('barang.index', compact('data'));
    }

    public function create()
    {
        $instansiId = Auth::user()->instansi_id;

        $kategori = KategoriBarang::where('instansi_id', $instansiId)->orderBy('nama')->get();
        $satuan = SatuanBarang::orderBy('nama')->get();

        return view('barang.create', compact('kategori', 'satuan'));
    }

    public function store(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;

        $payload = $request->validate([
            'kategori_id' => ['required', 'integer', 'exists:kategori_barang,id'],
            'satuan_id' => ['required', 'integer', 'exists:satuan_barang,id'],
            'sku' => ['required', 'string', 'max:120'],
            'nama' => ['required', 'string', 'max:255'],
            'merek' => ['nullable', 'string', 'max:160'],
            'model' => ['nullable', 'string', 'max:160'],
            'spesifikasi_json' => ['nullable', 'string'],
            'tipe_barang' => ['required', 'in:habis_pakai,aset,keduanya'],
            'metode_pelacakan' => ['required', 'in:tanpa,lot,kedaluwarsa,serial'],
            'stok_minimum' => ['required', 'numeric'],
            'titik_pesan_ulang' => ['required', 'numeric'],
            'status' => ['required', 'in:aktif,nonaktif'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);


        $exists = Barang::where('instansi_id', $instansiId)
            ->where('sku', $payload['sku'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['sku' => 'SKU sudah digunakan.'])->withInput();
        }

        $spesifikasi = null;

        if (!empty($payload['spesifikasi_json'])) {
            $raw = trim((string) $payload['spesifikasi_json']);

            $decoded = json_decode($raw, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $spesifikasi = $decoded;
            } else {
                $raw = str_replace(["\r\n", "\r"], "\n", $raw);
                $parts = preg_split("/[,\n;]/", $raw);

                $pairs = [];
                foreach ($parts as $part) {
                    $part = trim($part);
                    if ($part === '') continue;

                    if (str_contains($part, ':')) {
                        [$k, $v] = explode(':', $part, 2);
                        $k = trim($k);
                        $v = trim($v);

                        if ($k !== '') {
                            $pairs[$k] = $v;
                        } else {
                            $pairs[] = $part;
                        }
                    } else {
                        $pairs[] = $part;
                    }
                }

                $spesifikasi = $pairs ?: ['keterangan' => $raw];
            }
        }


        unset($payload['spesifikasi_json']);

        $payload['instansi_id'] = $instansiId;
        $payload['spesifikasi'] = $spesifikasi;

        if ($request->hasFile('gambar')) {
            $ext = $request->file('gambar')->getClientOriginalExtension();
            $filename = Str::uuid()->toString() . '.' . $ext;
            $path = $request->file('gambar')->storeAs('barang', $filename, 'public');
            $payload['gambar'] = $path;
        }

        Barang::create($payload);

        return redirect()->route('barang.index')
            ->with('success', 'Barang Berhasil Ditambahkan');
    }

    public function edit(Barang $barang)
    {
        $instansiId = Auth::user()->instansi_id;
        abort_unless($barang->instansi_id == $instansiId, 404);

        $kategori = KategoriBarang::where('instansi_id', $instansiId)->orderBy('nama')->get();
        $satuan = SatuanBarang::orderBy('nama')->get();

        return view('barang.edit', compact('barang', 'kategori', 'satuan'));
    }

    public function update(Request $request, Barang $barang)
    {
        $instansiId = Auth::user()->instansi_id;
        abort_unless($barang->instansi_id == $instansiId, 404);

        $payload = $request->validate([
            'kategori_id' => ['required', 'integer', 'exists:kategori_barang,id'],
            'satuan_id' => ['required', 'integer', 'exists:satuan_barang,id'],
            'sku' => ['required', 'string', 'max:120'],
            'nama' => ['required', 'string', 'max:255'],
            'merek' => ['nullable', 'string', 'max:160'],
            'model' => ['nullable', 'string', 'max:160'],
            'spesifikasi_json' => ['nullable', 'string'],
            'tipe_barang' => ['required', 'in:habis_pakai,aset,keduanya'],
            'metode_pelacakan' => ['required', 'in:tanpa,lot,kedaluwarsa,serial'],
            'stok_minimum' => ['required', 'numeric'],
            'titik_pesan_ulang' => ['required', 'numeric'],
            'status' => ['required', 'in:aktif,nonaktif'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $exists = Barang::where('instansi_id', $instansiId)
            ->where('sku', $payload['sku'])
            ->where('id', '!=', $barang->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['sku' => 'SKU sudah digunakan.'])->withInput();
        }

        $spesifikasi = null;

        if (!empty($payload['spesifikasi_json'])) {
            $raw = trim((string) $payload['spesifikasi_json']);

            $decoded = json_decode($raw, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $spesifikasi = $decoded;
            } else {
                $raw = str_replace(["\r\n", "\r"], "\n", $raw);
                $parts = preg_split("/[,\n;]/", $raw);

                $pairs = [];
                foreach ($parts as $part) {
                    $part = trim($part);
                    if ($part === '') continue;

                    if (str_contains($part, ':')) {
                        [$k, $v] = explode(':', $part, 2);
                        $k = trim($k);
                        $v = trim($v);

                        if ($k !== '') {
                            $pairs[$k] = $v;
                        } else {
                            $pairs[] = $part;
                        }
                    } else {
                        $pairs[] = $part;
                    }
                }

                $spesifikasi = $pairs ?: ['keterangan' => $raw];
            }
        }


        unset($payload['spesifikasi_json']);

        $payload['spesifikasi'] = $spesifikasi;

        if ($request->hasFile('gambar')) {
            if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
                Storage::disk('public')->delete($barang->gambar);
            }

            $ext = $request->file('gambar')->getClientOriginalExtension();
            $filename = Str::uuid()->toString() . '.' . $ext;
            $path = $request->file('gambar')->storeAs('barang', $filename, 'public');
            $payload['gambar'] = $path;
        }

        $barang->update($payload);

        return redirect()->route('barang.index')
            ->with('success', 'Barang Berhasil diubah');
    }
    public function importOcr()
    {
        $instansiId = Auth::user()->instansi_id;

        $kategori = KategoriBarang::where('instansi_id', $instansiId)->orderBy('nama')->get(['id', 'nama']);
        $satuan = SatuanBarang::orderBy('nama')->get(['id', 'nama']);

        return view('barang.import_ocr', compact('kategori', 'satuan'));
    }

    public function importOcrStore(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;

        $payload = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.nama' => ['required', 'string', 'max:255'],
            'items.*.kategori_id' => ['required', 'integer', 'exists:kategori_barang,id'],
            'items.*.satuan_id' => ['required', 'integer', 'exists:satuan_barang,id'],
        ]);

        $items = $payload['items'];

        $created = 0;

        DB::transaction(function () use ($items, $instansiId, &$created) {
            foreach ($items as $row) {
                $nama = trim((string) $row['nama']);
                if ($nama === '') continue;

                $sku = $this->generateUniqueSkuForInstansi($instansiId);

                Barang::create([
                    'instansi_id' => $instansiId,
                    'kategori_id' => (int) $row['kategori_id'],
                    'satuan_id' => (int) $row['satuan_id'],
                    'sku' => $sku,
                    'nama' => $nama,
                ]);

                $created++;
            }
        });

        return redirect()->route('barang.index')->with('success', "Berhasil import {$created} barang.");
    }

    private function generateUniqueSkuForInstansi(int $instansiId): string
    {
        do {
            $sku = 'OCR-' . strtoupper(Str::random(10));
        } while (
            Barang::where('instansi_id', $instansiId)->where('sku', $sku)->exists()
        );

        return $sku;
    }
    public function scanOcr(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'max:10240'],
            'handwritten' => ['nullable', 'boolean'],
        ]);
        $isHandwritten = (bool) $request->boolean('handwritten');

        $url = $isHandwritten
            ? config('services.ocr_barang.handwritten_url')
            : config('services.ocr_barang.url');

        if (!$url) {
            return response()->json(['message' => 'OCR endpoint belum dikonfigurasi.'], 422);
        }

        $file = $request->file('image');

        $masterKategori = KategoriBarang::query()
            ->select(['id', 'nama'])
            ->orderBy('nama', 'asc')
            ->get()
            ->values()
            ->toJson(JSON_UNESCAPED_UNICODE);

        $masterSatuan = SatuanBarang::query()
            ->select(['id', 'nama'])
            ->orderBy('nama', 'asc')
            ->orderBy('id', 'asc')
            ->get()
            ->values()
            ->toJson(JSON_UNESCAPED_UNICODE);

        $client = new Client([
            'timeout' => 180,
            'connect_timeout' => 10,
            'http_errors' => false,
        ]);

        try {
            $resp = $client->post($url, [
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen($file->getRealPath(), 'r'),
                        'filename' => $file->getClientOriginalName(),
                        'headers' => [
                            'Content-Type' => $file->getMimeType(),
                        ],
                    ],
                    [
                        'name' => 'master_kategori',
                        'contents' => $masterKategori,
                    ],
                    [
                        'name' => 'master_satuan',
                        'contents' => $masterSatuan,
                    ],
                ],
            ]);

            $status = $resp->getStatusCode();
            $bodyStr = (string) $resp->getBody();
            $bodyJson = json_decode($bodyStr, true);

            if ($status >= 400) {
                return response()->json([
                    'message' => 'OCR gagal.',
                    'status' => $status,
                    'body' => $bodyJson ?? $bodyStr,
                ], 422);
            }

            return response()->json($bodyJson ?? []);
        } catch (RequestException $e) {
            $errBody = $e->hasResponse()
                ? (string) $e->getResponse()->getBody()
                : null;

            return response()->json([
                'message' => 'Request ke OCR error.',
                'error' => $e->getMessage(),
                'body' => $errBody,
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Terjadi error.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
