<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    /**
     * Mark a single notification as read.
     */
    public function markRead(Notifikasi $notifikasi)
    {
        // Pastikan notifikasi milik user yang login
        abort_if($notifikasi->user_id !== Auth::id(), 403);

        $notifikasi->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Notifikasi ditandai sebagai dibaca.');
    }

    /**
     * Mark all notifications of the user as read.
     */
    public function markAllRead()
    {
        Auth::user()->notifikasis()->where('is_read', false)->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Semua notifikasi ditandai sebagai dibaca.');
    }
}
