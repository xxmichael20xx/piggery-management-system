<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pig extends Model
{
    use HasFactory;

    public function breed() {
        return $this->hasOne( Breed::class, 'id', 'breed_id' );
    }

    public function quarantine() {
        return $this->hasOne( Quarantine::class, 'pig_id', 'id' );
    }
}
