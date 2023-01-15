<?php

namespace Database\Seeders;

use App\Models\Breed;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BreedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $initialBreeds = [
            'Aksai Black Pied',
            'American Yorkshire',
            'Bentham Black Pied',
            'Philippine Native',
            'Siberian Black Pied',
            'Sushan pig'
        ];

        foreach ( $initialBreeds as $initialBreed ) {
            if ( Breed::where( 'name', $initialBreed )->count() < 1 ) {
                $breed = new Breed;
                $breed->name= $initialBreed;
                $breed->save();
            }
        }
    }
}
