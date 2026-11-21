<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    protected $fillable = [
        'project_id', 'developer_id', 'price',
        'duration_days', 'proposal', 'status',
    ];

    // Penawaran ini untuk satu proyek
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Penawaran ini dibuat oleh satu developer (user)
    public function developer()
    {
        return $this->belongsTo(User::class, 'developer_id');
    }
}
