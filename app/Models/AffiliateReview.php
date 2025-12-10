<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'affiliate_id',
        'rating',
        'review',
        'is_visible',
    ];

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }
}