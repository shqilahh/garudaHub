<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'client_id', 'title', 'description', 'category',
        'budget', 'bid_deadline', 'status',
    ];

    protected $casts = [
        'bid_deadline' => 'datetime',
    ];

    // Proyek ini milik satu klien (user)
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    // Proyek ini punya banyak penawaran
    public function bids()
    {
        return $this->hasMany(Bid::class);
    }
}
