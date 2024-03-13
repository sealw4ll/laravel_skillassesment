<?php

namespace App\Models;

use App\Events\BidCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $table = "bids";

    protected static function boot()
    {
        parent::boot();

        static::created(function ($bid) {
            event(new BidCreated($bid));
        });
    }
}
