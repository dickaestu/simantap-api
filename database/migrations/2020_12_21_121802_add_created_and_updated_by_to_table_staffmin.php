<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatedAndUpdatedByToTableStaffmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staffmin_files', function (Blueprint $table) {
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staffmin_files', function (Blueprint $table) {
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
        });
    }
}
