<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaksiDafulsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_daful', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('keterangan');
            $table->integer('jumlah_bayar');
            $table->tinyInteger('cicilan');
            $table->tinyInteger('lunas');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_daful');
    }
}
