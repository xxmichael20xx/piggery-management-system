<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Breed extends Model
{
    use HasFactory;

    public function pigs() {
        return $this->hasMany( Pig::class, 'breed_id' );
    }
}
