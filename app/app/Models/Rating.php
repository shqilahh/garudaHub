<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = ['bid_id', 'client_id', 'developer_id', 'score', 'comment'];

    public function bid()
    {
        return $this->belongsTo(Bid::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function developer()
    {
        return $this->belongsTo(User::class, 'developer_id');
    }
}
