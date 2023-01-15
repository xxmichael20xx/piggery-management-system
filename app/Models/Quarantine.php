<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quarantine extends Model
{
    use HasFactory;

    public function pig() {
        return $this->belongsTo( Pig::class, 'pig_id' );
    }
}
