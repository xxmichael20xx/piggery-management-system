<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pigs', function (Blueprint $table) {
            $table->id();
            $table->string( 'pig_no' );
            $table->foreignId( 'breed_id' )->constrained( 'breeds' );
            $table->integer( 'weight' )->default( 0 );
            $table->string( 'weight_unit' )->default( 'kg' );
            $table->text( 'image' );
            $table->string( 'gender' );
            $table->date( 'date_arrived' );
            $table->text( 'notes' )->nullable();
            $table->string( 'status' );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pigs');
    }
};
