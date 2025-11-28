<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Review;

class ManageReviewController extends Controller
{
    function index(){
        $review = Review::all();
        return view('manage-review.index', compact('review'));
    }

    function approve($id_review) {
        $review = Review::findOrFail($id_review);
        $review->update(['status' => 'approved']);
        
        return redirect('manageReview')->with('status_message', 'Ulasan berhasil disetujui!');
    }

    function reject($id_review) {
        $id_review = Review::findOrFail($id_review);
        $id_review->update(['status' => 'rejected']);
        
        return redirect('/manageReview')->with('status_message', 'Ulasan ditolak!');
    }

}