<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request, Bid $bid)
    {
        // Hanya klien pemilik proyek yang boleh memberi rating
        abort_unless($bid->project->client_id === auth()->id(), 403);

        // Hanya penawaran pemenang yang boleh dirating
        abort_unless($bid->status === 'accepted', 403);

        // Satu penawaran hanya boleh dirating sekali
        if ($bid->rating()->exists()) {
            return back()->withErrors(['score' => 'Penawaran ini sudah dirating.']);
        }

        $data = $request->validate([
            'score'   => 'required|integer|between:1,5',
            'comment' => 'nullable|max:500',
        ]);

        // client_id dan developer_id diambil dari server, BUKAN dari input form
        $bid->rating()->create($data + [
            'client_id'    => auth()->id(),
            'developer_id' => $bid->developer_id,
        ]);

        return back()->with('ok', 'Rating dikirim.');
    }
}
