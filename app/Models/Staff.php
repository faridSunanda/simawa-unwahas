<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Staff extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'staffs';

    protected $fillable = [
        'user_id',
        'nip',
        'bagian',
        'no_hp',
    ];

    /**
     * Get the user that owns this staff profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
