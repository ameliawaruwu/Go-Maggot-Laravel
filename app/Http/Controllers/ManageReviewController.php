<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ManageReviewController extends Controller
{
    public function index()
    {
        
        $review = Review::with(['pengguna', 'produk'])->latest()->get();
        return view('manage-review.index', compact('review'));
    }
    public function pending($id_review) 
    {
        $review = Review::findOrFail($id_review);
        $review->update(['status' => 'pending']);
        return redirect('/manageReview')->with('success', 'Ulasan dikembalikan ke status Pending.');
    }

    public function approve($id_review) 
    {
        $review = Review::findOrFail($id_review);
        $review->update(['status' => 'approved']);
        
        return redirect('/manageReview')->with('success', 'Ulasan berhasil disetujui dan akan tampil di produk.');
    }

    public function reject($id_review) 
    {
        $review = Review::findOrFail($id_review);
        $review->update(['status' => 'rejected']);
        
        return redirect('/manageReview')->with('success', 'Ulasan berhasil ditolak.');
    }
}