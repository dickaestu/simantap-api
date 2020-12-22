<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusBagianFieldToTableSubBagian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_bagian', function (Blueprint $table) {
            $table->enum('status_bagian', ['karo', 'kabag', 'kasubag', 'paur', 'pamin', 'bamin']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_bagian', function (Blueprint $table) {
            $table->enum('status_bagian', ['karo', 'kabag', 'kasubag', 'paur', 'pamin', 'bamin']);
        });
    }
}
