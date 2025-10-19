<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File; 

class ManageProductsController extends Controller
{
    private $initialProducts = [
        
        [
            'idproduk' => 1, 
            'namaproduk' => 'Maggot Fresh', 
            'kategori' => 'BSF', 
            'harga' => 25000,
            'stok' => 50, 
            'merk' => 'GoMaggot', 
            'berat' => 1.0, 
            'satuan_berat' => 'kg',
            'masapenyimpanan' => '7 hari', 
            'pengiriman' => 'Instant', 
            'deskripsi_produk' => 'Maggot segar kualitas super yang sangat baik untuk pakan ternak. Kaya akan protein dan nutrisi esensial.', 
            'gambar' => 'maggot-fresh.jpg'
        ],
        [
            'idproduk' => 2, 
            'namaproduk' => 'Pupuk Kompos Organik', 
            'kategori' => 'Kompos', 
            'harga' => 15000, 
            'stok' => 5, 
            'merk' => 'EcoFarm', 
            'berat' => 5.0, 
            'satuan_berat' => 'kg',
            'masapenyimpanan' => '1 tahun', 
            'pengiriman' => 'Reguler', 
            'deskripsi_produk' => 'Pupuk kompos hasil olahan BSF yang kaya nutrisi untuk kesuburan tanah. Ramah lingkungan.', 
            'gambar' => 'pupuk-kompos.jpg'
        ],
        [
            'idproduk' => 3, 
            'namaproduk' => 'Pelet Maggot Premium', 
            'kategori' => 'Lainnya', 
            'harga' => 45000, 
            'stok' => 0, 
            'merk' => 'PrimaFeed', 
            'berat' => 0.5, 
            'satuan_berat' => 'kg',
            'masapenyimpanan' => '6 bulan', 
            'pengiriman' => 'Reguler', 
            'deskripsi_produk' => 'Pelet protein tinggi dari Maggot kering untuk pertumbuhan ikan dan unggas yang cepat.', 
            'gambar' => 'pelet-maggot.jpg'
        ],
    ];

    private $fixedCategories = ['BSF', 'Kompos', 'Pupuk', 'Lainnya'];

    // Metode ini sudah OK
    private function getAvailableImages()
    {
        $imagesPath = public_path('images');
        $imageFiles = [];
        // Menggunakan File::isDirectory yang lebih spesifik
        if (File::isDirectory($imagesPath)) { 
            // Filter untuk hanya mengambil file (bukan folder)
            $files = File::files($imagesPath); 
            foreach ($files as $file) {
                // Filter tambahan agar hanya file gambar yang masuk
                if (in_array($file->getExtension(), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $imageFiles[] = $file->getFilename(); 
                }
            }
        }
        
        // Pastikan default-product.jpg tersedia
        if (!in_array('default-product.jpg', $imageFiles)) {
             $imageFiles[] = 'default-product.jpg';
        }

        sort($imageFiles); 
        return array_unique($imageFiles); 
    }

    
    // Metode getProducts, saveProducts, getNextId tidak diubah

    private function getProducts()
    {
        if (!Session::has('dataProduk')) {
            Session::put('dataProduk', $this->initialProducts);
        }
        return Session::get('dataProduk');
    }


    private function saveProducts($products)
    {
        Session::put('dataProduk', $products);
    }
    
    private function getNextId($products)
    {
        $maxId = 0;
        foreach ($products as $product) {
            if ($product['idproduk'] > $maxId) {
                $maxId = $product['idproduk'];
            }
        }
        return $maxId + 1;
    }

    public function index()
    {
        $dataProduk = $this->getProducts();
        $fixedCategories = $this->fixedCategories; 
        $categories = collect($dataProduk)->pluck('kategori')->unique()->map(fn($item) => ['kategori' => $item])->all();
        // $availableImages = $this->getAvailableImages(); // Tidak perlu di index view

        // Hapus availableImages dari compact di index view
        return view('manage-products.index', compact('dataProduk', 'categories', 'fixedCategories')); 
    }

    // FIX: Mengirim availableImages ke view create
    public function create()
    {
        return view('manage-products.create-product-admin', [
            'fixedCategories' => $this->fixedCategories,
            'imageFiles' => $this->getAvailableImages() // Nama variabel disesuaikan menjadi imageFiles
        ]);
    }

    // Metode store tidak diubah

    public function store(Request $request)
    {
        $validated = $request->validate([
            'namaproduk' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'merk' => 'nullable|string|max:100',
            'berat' => 'required|numeric|min:0',
            'satuan_berat' => 'required|string|in:gr,kg',
            'masapenyimpanan' => 'required|string|max:100',
            'pengiriman' => 'required|string|max:100',
            'deskripsi_produk' => 'nullable|string|max:500',
            'gambar' => 'nullable|string', 
        ]);
        
        $dataProduk = $this->getProducts();
        $newId = $this->getNextId($dataProduk);

        $newProduct = [
            'idproduk' => $newId,
            'namaproduk' => $validated['namaproduk'],
            'kategori' => $validated['kategori'],
            'harga' => (int) $validated['harga'],
            'stok' => (int) $validated['stok'],
            'merk' => $validated['merk'],
            'berat' => (float) $validated['berat'],
            'satuan_berat' => $validated['satuan_berat'],
            'masapenyimpanan' => $validated['masapenyimpanan'],
            'pengiriman' => $validated['pengiriman'],
            'deskripsi_produk' => $validated['deskripsi_produk'],
            // Menggunakan data dari dropdown (validated['gambar'])
            'gambar' => $validated['gambar'] ?? 'default-product.jpg', 
        ];

        $dataProduk[] = $newProduct;
        $this->saveProducts($dataProduk);

        return redirect()->route('products.index')->with('status_message', 'Produk **' . $newProduct['namaproduk'] . '** berhasil ditambahkan!');
    }

    // FIX: Mengirim availableImages ke view edit
    public function edit($id)
    {
        $id = (int) $id;
        $dataProduk = $this->getProducts();
        
        $product = collect($dataProduk)->firstWhere('idproduk', $id);

        if (!$product) {
            return redirect()->route('products.index')->withErrors('Produk tidak ditemukan.');
        }

        return view('manage-products.edit-product-admin', [
            'product' => $product,
            'fixedCategories' => $this->fixedCategories,
            'imageFiles' => $this->getAvailableImages() 
        ]);
    }

    // Metode update dan destroy tidak diubah
    
    public function update(Request $request, $id)
    {
        $id = (int) $id;
        
        $validated = $request->validate([
            'namaproduk' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'merk' => 'nullable|string|max:100',
            'berat' => 'required|numeric|min:0',
            'satuan_berat' => 'required|string|in:gr,kg',
            'masapenyimpanan' => 'required|string|max:100',
            'pengiriman' => 'required|string|max:100',
            'deskripsi_produk' => 'nullable|string|max:500',
            'gambar' => 'nullable|string', 
        ]);

        $dataProduk = $this->getProducts();
        $indexToUpdate = -1;

        foreach ($dataProduk as $index => $product) {
            if ($product['idproduk'] === $id) {
                $indexToUpdate = $index;
                break;
            }
        }

        if ($indexToUpdate === -1) {
            return redirect()->route('products.index')->withErrors('Produk yang akan diperbarui tidak ditemukan.');
        }
        
        $dataProduk[$indexToUpdate] = array_merge($dataProduk[$indexToUpdate], [
            'namaproduk' => $validated['namaproduk'],
            'kategori' => $validated['kategori'],
            'harga' => (int) $validated['harga'],
            'stok' => (int) $validated['stok'],
            'merk' => $validated['merk'],
            'berat' => (float) $validated['berat'],
            'satuan_berat' => $validated['satuan_berat'],
            'masapenyimpanan' => $validated['masapenyimpanan'],
            'pengiriman' => $validated['pengiriman'],
            'deskripsi_produk' => $validated['deskripsi_produk'],
            // Menggunakan data dari dropdown (validated['gambar'])
            'gambar' => $validated['gambar'] ?? $dataProduk[$indexToUpdate]['gambar'], 
        ]);

        $this->saveProducts($dataProduk);

        return redirect()->route('products.index')->with('status_message', 'Produk **' . $validated['namaproduk'] . '** (ID ' . $id . ') berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $id = (int) $id;
        $dataProduk = $this->getProducts();
        
        $initialCount = count($dataProduk);
        $dataProduk = array_filter($dataProduk, fn($product) => $product['idproduk'] !== $id);
        $finalCount = count($dataProduk);
        
        if ($initialCount === $finalCount) {
             return redirect()->route('products.index')->withErrors('Produk yang akan dihapus tidak ditemukan.');
        }

        $this->saveProducts(array_values($dataProduk)); 

        return redirect()->route('products.index')->with([
            'status_message' => 'Produk ID **' . $id . '** berhasil dihapus!', 
            'status_type' => 'error'
        ]);
    }
}