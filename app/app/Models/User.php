<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
        // User (klien) punya banyak proyek
    public function projects()
    {
        return $this->hasMany(Project::class, 'client_id');
    }

    // User (developer) punya banyak penawaran
    public function bids()
    {
        return $this->hasMany(Bid::class, 'developer_id');
    }

    // Rating yang diterima user ini sebagai developer
    public function ratingsReceived()
    {
        return $this->hasMany(Rating::class, 'developer_id');
    }
}
