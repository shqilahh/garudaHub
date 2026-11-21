<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BidController extends Controller
{
    // Kirim penawaran baru ke sebuah proyek
    public function store(Request $request, Project $project)
    {
        $data = $request->validate([
            'developer_id'  => [
                'required',
                'exists:users,id',
                // satu developer hanya boleh satu penawaran per proyek
                Rule::unique('bids')->where('project_id', $project->id),
            ],
            'price'         => 'required|integer|min:1',
            'duration_days' => 'required|integer|min:1',
            'proposal'      => 'required',
        ]);

        $project->bids()->create($data);

        return back()->with('ok', 'Penawaran dikirim.');
    }

    // Klien memilih pemenang
    public function accept(Bid $bid)
    {
        // Transaction: semua perubahan berhasil bersama, atau batal semua
        DB::transaction(function () use ($bid) {
            $bid->project->bids()->update(['status' => 'rejected']);
            $bid->update(['status' => 'accepted']);
            $bid->project->update(['status' => 'awarded']);
        });

        return back()->with('ok', 'Pemenang dipilih.');
    }

    // Hapus penawaran
    public function destroy(Bid $bid)
    {
        $bid->delete();

        return back()->with('ok', 'Penawaran dihapus.');
    }
}
