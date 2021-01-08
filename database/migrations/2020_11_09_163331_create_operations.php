<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('kode_poli');
            $table->string('dpjp');
            $table->string('reg');
            $table->string('nomr');
            $table->string('nama');
            $table->date('tanggal');
            $table->string('terlaksana');
            $table->string('jenis_tindakan');
            $table->string('nopeserta');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operations');
    }
}
