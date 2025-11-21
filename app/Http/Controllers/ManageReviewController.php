<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ManageReviewController extends Controller
{
    // Struktur data review
    private $initialReviews = [
        ['id' => 1, 'product_name' => 'Pupuk Kompos A', 'reviewer_name' => 'Wirda', 'rating' => 5, 'komentar' => 'Kulitas pupuk bagus', 'status' => 'approved', 'tanggal' => '13/06/2025', 'product_image' => 'maggot 2.jpg'],
        ['id' => 2, 'product_name' => 'Pelet Maggot', 'reviewer_name' => 'Ahmad', 'rating' => 5, 'komentar' => 'Produknya bagus', 'status' => 'approved', 'tanggal' => '13/06/2025', 'product_image' => 'Kompos.jpg'],
        ['id' => 3, 'product_name' => 'Maggot Segar', 'reviewer_name' => 'Amelia', 'rating' => 4, 'komentar' => 'mantap', 'status' => 'approved', 'tanggal' => '13/06/2025', 'product_image' => 'bibit-remove bg.jpg'],
        ['id' => 4, 'product_name' => 'Pupuk Kompos B', 'reviewer_name' => 'Roro', 'rating' => 5, 'komentar' => 'bagus', 'status' => 'approved', 'tanggal' => '13/06/2025', 'product_image' => 'maggor 2.jpg'],
        ['id' => 5, 'product_name' => 'Maggot Segar', 'reviewer_name' => 'Annisa', 'rating' => 2, 'komentar' => 'Maggotnya pas nyampe mati', 'status' => 'rejected', 'tanggal' => '13/06/2025', 'product_image' => 'kompos.jpg'],
        ['id' => 6, 'product_name' => 'Pelet Maggot', 'reviewer_name' => 'Budi', 'rating' => 3, 'komentar' => 'Butuh perbaikan pengiriman', 'status' => 'pending', 'tanggal' => '14/06/2025', 'product_image' => 'maggot 2.jpg'],
    ];

    /**
     * 
     * @return array
     */
    private function getReviews()
    {
        if (!Session::has('reviews')) {
            Session::put('reviews', $this->initialReviews);
        }
        return Session::get('reviews');
    }

    /**
     * Menyimpan data review ke session.
     * @param array $reviews
     */
    private function saveReviews(array $reviews)
    {
        // Gunakan array_values untuk memastikan key array berurutan (0, 1, 2...)
        Session::put('reviews', array_values($reviews));
    }

    /**
     * Menampilkan daftar review dengan filter status.
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Ambil semua data dari session
        $reviews = $this->getReviews();
        
        // Ambil nilai filter dari query string, default 'all'
        $statusFilter = $request->query('status', 'all');
        
        // Lakukan filtering menggunakan Collection
        $filteredReviews = collect($reviews)->filter(function ($review) use ($statusFilter) {
            if ($statusFilter === 'all') {
                return true;
            }
            // Bandingkan status, pastikan kedua nilai dalam lowercase
            return strtolower($review['status']) === strtolower($statusFilter);
        })->values()->all();

        // Hitung total keseluruhan review
        $totalReviews = count($this->getReviews());
        
        return view('manage-review.index', [ 
            'reviews' => $filteredReviews, 
            'totalReviews' => $totalReviews, 
            'statusFilter' => $statusFilter, 
        ]);
    }

    /**
     * Memperbarui status review (Approve/Reject) melalui AJAX.
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {
        // Validasi input status. Ganti 'new_status' menjadi 'status'
        $request->validate([
            'status' => 'required|in:approved,rejected,pending',
        ]);
        
        // Ambil status dan ubah ID menjadi integer
        $id = (int) $id;
        $newStatus = $request->input('status');
        $reviews = $this->getReviews();
        
        // 💡 Cari indeks review yang sesuai
        $indexToUpdate = array_search($id, array_column($reviews, 'id'));

        if ($indexToUpdate === false) {
            return response()->json(['success' => false, 'message' => 'Review tidak ditemukan.'], 404);
        }
        
        // Cek apakah status perlu diubah
        if (strtolower($reviews[$indexToUpdate]['status']) === strtolower($newStatus)) {
            // Status sama, tidak perlu update
            $message = 'Status review sudah ' . ucfirst($newStatus) . '. Tidak ada perubahan dilakukan.';
        } else {
            // Lakukan update status
            $reviews[$indexToUpdate]['status'] = $newStatus;
            $this->saveReviews($reviews);
            $message = 'Status review berhasil diperbarui menjadi ' . ucfirst($newStatus) . '.';
        }
        
        // Response sukses (JSON)
        return response()->json([
            'success' => true, 
            'message' => $message,
            'new_status' => $newStatus,
            'review_id' => $id,
        ]);
    }

    /**
     * Menghapus review dari sesi.
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $id = (int) $id; 
        $reviews = $this->getReviews();
        $initialCount = count($reviews);

        
        $reviews = array_filter($reviews, fn($review) => $review['id'] !== $id);

        if (count($reviews) < $initialCount) {
           
            $this->saveReviews(array_values($reviews)); 
            return redirect()->route('managereview')->with('status_message', "Review ID **$id** berhasil dihapus!");
        }

        return redirect()->route('managereview')->withErrors('Review tidak ditemukan.');
    }
}