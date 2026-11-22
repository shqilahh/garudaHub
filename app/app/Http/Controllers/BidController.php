<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BidController extends Controller
{
    public function store(Request $request, Project $project)
    {
        abort_unless($project->status === 'open', 403);

        $data = $request->validate([
            'price'         => 'required|integer|min:1',
            'duration_days' => 'required|integer|min:1',
            'proposal'      => 'required',
        ]);

        // Satu developer hanya boleh satu penawaran per proyek
        if ($project->bids()->where('developer_id', auth()->id())->exists()) {
            return back()->withErrors(['price' => 'Kamu sudah mengajukan penawaran di proyek ini.']);
        }

        // Developer diambil dari user yang login, BUKAN dari input form
        $project->bids()->create($data + ['developer_id' => auth()->id()]);

        return back()->with('ok', 'Penawaran dikirim.');
    }

    public function accept(Bid $bid)
    {
        // Hanya pemilik proyek yang boleh memilih pemenang
        abort_unless($bid->project->client_id === auth()->id(), 403);
        abort_unless($bid->project->status === 'open', 403);

        DB::transaction(function () use ($bid) {
            $bid->project->bids()->update(['status' => 'rejected']);
            $bid->update(['status' => 'accepted']);
            $bid->project->update(['status' => 'awarded']);
        });

        return back()->with('ok', 'Pemenang dipilih.');
    }

    public function destroy(Bid $bid)
    {
        // Hanya pembuat penawaran yang boleh menghapusnya
        abort_unless($bid->developer_id === auth()->id(), 403);

        $bid->delete();

        return back()->with('ok', 'Penawaran dihapus.');
    }
}
