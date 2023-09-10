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
        Schema::create('userguide', function (Blueprint $table) {
            $table->id(); // Auto-incremental primary key
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status');
            $table->timestamps(); // Created_at and updated_at timestamps
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
