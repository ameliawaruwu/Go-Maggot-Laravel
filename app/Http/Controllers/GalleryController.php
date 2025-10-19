<?php

namespace App\Http\Controllers; 

use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Menampilkan halaman galeri dengan data dummy.
     */
    public function index()
    {
        // Data Dummy untuk item galeri 
        $galleryItems = [
            [
                'name' => 'Maggot BSF',
                'description' => 'Maggot BSF yang dibudidayakan dengan baik dan diperjual belikan.',
                "imageUrl" => 'https://i.pinimg.com/736x/39/4a/bd/394abd48202566de25266bf9c6da61be.jpg',
                'link' => route('article.show', ['slug' => 'artikeltiga']), 
            ],
            [
                'name' => 'Pemisahan Bibit Maggot BSF',
                'description' => 'Ini adalah bibit maggot BSF yang kami jual dengan kualitas yang terjamin baik, dan bisa digunakan apabila ingin membudidayakan maggot dari rumah.',
                "imageUrl" => 'https://i.pinimg.com/736x/6c/89/ba/6c89bad30ab0d6d0d299d1796f62fd85.jpg',
                'link' => route('article.show', ['slug' => 'artikelsatu']),
            ],
            [
                'name' => 'Pengolahan Maggot BSF Menjadi Kompos',
                'description' => 'Kompos untuk kesuburan tanaman yang dibuat dari maggot.',
                "imageUrl" => 'https://i.pinimg.com/736x/00/d9/aa/00d9aaf34517fce48ff846442d2977ad.jpg',
                'link' => route('article.show', ['slug' => 'artikeltiga']),
            ],
            [
                'name' => 'Budidaya Maggot BSF',
                'description' => 'Salah satu kegiatan kami dalam upaya melestarikan keberlangsungan maggot dengan melakukan pembudidayaan.',
                "imageUrl" => 'https://asset-2.tstatic.net/jogja/foto/bank/images/Salah-satu-petugas-Kandang-Maggot-Jogja-tengah.jpg',
                'link' => route('article.show', ['slug' => 'artikelsatu']),
            ],
            [
                'name' => 'Maggot Sebagai Pakan Hewan',
                'description' => 'Pemanfaatan maggot sebagai pakan hewan, salah satunya untuk pakan ayam.',
                "imageUrl" => 'https://i.pinimg.com/736x/40/74/52/4074522e424f0db127a6cda1895b65fc.jpg',
                'link' => route('article.show', ['slug' => 'artikeldua']),
            ],
        ];

        // Mengirim data ke view 'gallery'
        return view('gallery.gallery', compact('galleryItems'));
    }

    /**
     * Fungsi untuk menampilkan detail artikel.
     * 
     */
    public function showArtikel($slug)
    {
        // Data dummy untuk semua artikel
        $allArticles = [
            'artikelsatu' => [ 
                'judul' => 'Melakukan Budidaya Maggot Untuk Pemula', 
                'penulis' => 'Tim GoMaggot',
                'tanggal' => '2024-05-15',
                'hak_cipta' => '',
                'konten' => "
                    <div class='deskripsi'>
                        <div class='box'>
                            <h4>Apa Itu Maggot BSF</h4>
                            <p>
                                Maggot merupakan larva dari jenis lalat Black Soldier Fly (BSF) sehingga sering disebut maggot BSF.
                                Lalat BSF memiliki nama latin <em>Hermetia illucens</em>. Bentuknya mirip ulat, dengan ukuran 
                                larva dewasa 15–22 mm dan berwarna coklat. Siklus hidup lalat BSF sekitar 40–43 hari. 
                                Larva bertahan 14–18 hari sebelum bermetamorfosis menjadi pupa dan lalat dewasa.
                            </p>
                            <br>

                            <h4>Manfaat Budidaya Maggot BSF</h4>
                            <ul>
                                <li>Pengelola Sampah Organik</li>
                                <p>
                                    Maggot BSF pemakan ulung sampah organik seperti sisa makanan dan kotoran hewan.
                                    Mereka menguraikan sampah dengan cepat dan efisien, sehingga mengurangi volume limbah.
                                </p>

                                <li>Pakan Ternak</li>
                                <p>
                                    Maggot BSF kaya protein dan nutrisi, cocok untuk pakan ikan, unggas, dan hewan ternak lain.
                                </p>

                                <li>Pupuk Organik</li>
                                <p>
                                    Frass (kotoran maggot) dapat dijadikan pupuk organik berkualitas tinggi untuk menyuburkan tanah.
                                </p>
                            </ul>
                            <br>

                            <div id='image2' style='text-align:center;'>
                                <img src='" . asset('images/esa/Maggot1.jpg') . "' alt='Gambar Maggot BSF' width='300' height='250'>
                            </div>
                            <br>

                            <h4>Bagaimana Cara Melakukan Budidaya Maggot Untuk Pemula</h4>

                            <p><em>1. Persiapan Awal</em></p>
                            <ul>
                                <li>Kandang</li>
                                <p>Gunakan bahan yang tahan lama dan mudah dibersihkan, misalnya kotak plastik atau rak khusus.</p>

                                <li>Media</li>
                                <p>Campuran dedak, buah busuk, atau sisa makanan yang lembap adalah media ideal.</p>

                                <li>Bibit Maggot</li>
                                <p>Bisa diperoleh dari peternak maggot atau beli online di toko kami.</p>
                            </ul>
                            <br>

                            <p><em>2. Proses Budidaya</em></p>
                            <ul>
                                <li>Penetesan Telur</li>
                                <p>Letakkan telur maggot di atas media lembap, dengan suhu dan kelembapan terjaga.</p>

                                <li>Pemberian Makan</li>
                                <p>Beri makanan secara rutin sesuai jumlah maggot dan kondisi media.</p>

                                <li>Pemisahan Pupa</li>
                                <p>Pisahkan pupa dari media saat siap bermetamorfosis menjadi lalat dewasa.</p>

                                <li>Perkembangbiakan</li>
                                <p>Lalat dewasa akan bertelur kembali, melanjutkan siklus budidaya.</p>
                            </ul>
                            <br>

                            <p><em>3. Perawatan</em></p>
                            <ul>
                                <li>Kebersihan</li>
                                <p>Jaga kebersihan kandang, bersihkan dan ganti media secara rutin.</p>

                                <li>Suhu dan Kelembapan</li>
                                <p>Pertahankan suhu ideal 25–30°C agar maggot tumbuh optimal.</p>

                                <li>Pengendalian Hama</li>
                                <p>Gunakan perangkap atau insektisida alami untuk mencegah hama seperti semut atau lalat lain.</p>
                            </ul>
                        </div>
                    </div>
                ",
            ],
            'artikeldua' => [ 
                'judul' => 'Pemanfaatan Maggot BSF untuk Pakan Ternak',
                'penulis' => 'Penulis Internal',
                'tanggal' => '2024-06-01',
                'hak_cipta' => '',
                'konten' => "
                    <div class='deskripsi'>
                        <div class='box'><br>
                            <h4>Mengenal Lebih Lanjut Maggot BSF</h4>
                            <p>
                                Maggot BSF (Black Soldier Fly) adalah larva dari lalat tentara hitam (<em>Hermetia illucens</em>)
                                yang dikenal sebagai solusi pengelolaan limbah organik dan pakan alternatif.
                            </p>
                            <br>

                            <h4>Karakteristik Maggot BSF</h4>
                            <ul>
                                <li>1. Ukuran :</li>
                                <p>Sekitar 1–2 cm saat siap panen.</p>

                                <li>2. Warna :</li>
                                <p>Putih krem hingga kekuningan, berbentuk silindris meruncing di ujung.</p>

                                <li>3. Siklus Hidup :</li>
                                <p>Melewati empat tahap: telur, larva, pupa, dan lalat dewasa (10–14 hari fase larva).</p>

                                <li>4. Habitat :</li>
                                <p>Lingkungan lembap dengan bahan organik tinggi seperti limbah dapur atau pertanian.</p>
                            </ul>
                            <br>

                            <div id='image2' style='text-align:center;'>
                                <img src='" . asset('images/esa/pakan ayam.jpg') . "' alt='Pakan Ayam' width='300' height='250'>
                            </div>
                            <br>

                            <h4>Manfaat Maggot BSF</h4>
                            <ul>
                                <li>1. Pengelolaan Limbah</li>
                                <p>Mengurai limbah organik seperti sisa makanan dan kotoran hewan.</p><br>

                                <li>2. Pakan Ternak</li>
                                <p>Kaya protein dan lemak, ideal untuk ikan, unggas, dan reptil.</p><br>

                                <li>3. Produk Tambahan</li>
                                <p>Frass (kotoran larva) bisa dijadikan pupuk organik berkualitas.</p>
                            </ul><br>

                            <h3><center>Kesimpulan</center></h3>
                            <p>
                                Maggot BSF adalah solusi inovatif pengelolaan limbah dan penyedia pakan ternak
                                berprotein tinggi untuk pertanian berkelanjutan.
                            </p>
                        </div>
                    </div>
                ",
            ],
            'artikeltiga' => [ 
                'judul' => 'Mengenal Lebih Dalam Maggot BSF', 
                'penulis' => 'Admin GoMaggot',
                'tanggal' => '2024-07-10',
                'hak_cipta' => 'Sumber: Artikel Maggot BSF. Hak Cipta oleh Admin.',
                'konten' => "
                    <div class='deskripsi'>
                        <div class='box'>
                            <h4>Apa Itu Maggot BSF</h4>
                            <p>
                                Maggot merupakan larva dari jenis lalat Black Soldier Fly (BSF) sehingga sering disebut maggot BSF.
                                Lalat BSF memiliki nama latin <em>Hermetia illucens</em>, berbentuk seperti ulat, panjang 15–22 mm,
                                dan berwarna coklat. Siklus hidupnya sekitar 40–43 hari.
                            </p><br>

                            <h4>Manfaat Maggot BSF Dalam Segi Kehidupan</h4>
                            <p>
                                Maggot BSF memiliki banyak manfaat di bidang lingkungan, pertanian, dan ekonomi.
                            </p>
                            <ul>
                                <li>1. Pengelolaan Limbah Organik</li>
                                <p>Mengurai sisa makanan dan limbah pertanian, mengurangi emisi gas metana dari sampah organik.</p><br>

                                <li>2. Pakan Ternak Berkualitas Tinggi</li>
                                <p>Sumber protein tinggi untuk unggas, ikan, dan reptil.</p><br>

                                <li>3. Penghasil Kompos Berkualitas</li>
                                <p>Sisa konsumsi maggot bisa diolah menjadi pupuk organik kaya nutrisi.</p>
                            </ul>
                            <br>

                            <div id='image2' style='text-align:center;'>
                                <img src='" . asset('images/esa/hama.jpg') . "' alt='Hama' width='250' height='200'>
                                <img src='" . asset('images/esa/limbah.jpg') . "' alt='Limbah' width='250' height='200'>
                                <img src='" . asset('images/esa/ayam.jpg') . "' alt='Ayam' width='250' height='200'>
                            </div>
                            <br>

                            <ul>
                                <li>4. Ekonomi Sirkular dan Usaha Mikro</li>
                                <p>Peluang usaha menjanjikan dengan modal rendah bagi petani dan pelaku mikro.</p><br>

                                <li>5. Pengendalian Hama dan Penyakit</li>
                                <p>Maggot BSF tidak membawa patogen, dan dapat menekan populasi lalat rumah.</p><br>

                                <li>6. Konservasi Lingkungan</li>
                                <p>Mendukung pertanian berkelanjutan dan mengurangi pencemaran akibat sampah organik.</p>
                            </ul><br>

                            <center><h3>Kesimpulan</h3></center>
                            <p>
                                Maggot BSF berperan penting dalam pengelolaan limbah, peningkatan kualitas pakan ternak,
                                dan pembangunan ekonomi sirkular berkelanjutan.
                            </p>
                        </div>
                    </div>
                ",
            ],
        ];

        // Melakukan pengecekan apakah artikel dengan $slug ini ada
        if (!isset($allArticles[$slug])) {
            abort(404, 'Artikel tidak ditemukan.'); 
        }

        // Ambil data artikel yang sesuai dengan $slug
        $article = $allArticles[$slug];

        // Mengirim data artikel ke view dengan nama variabel 'article'
        return view('study.article-detail', compact('article'));
    }
}