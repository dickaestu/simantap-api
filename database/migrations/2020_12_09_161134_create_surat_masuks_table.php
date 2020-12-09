<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratMasuksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('no_agenda',50);
            $table->string('no_surat',50)->unique()->index();
            $table->date('tanggal_surat');
            $table->date('tanggal_terima');
            $table->string('sumber_surat');
            $table->string('tujuan_surat');
            $table->string('perihal');
            $table->text('keterangan')->nullable();
            $table->text('file');
            $table->string('status')->default('manager');
            $table->bigInteger('bagian_is')->unsigned();
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
        Schema::dropIfExists('surat_masuk');
    }
}
