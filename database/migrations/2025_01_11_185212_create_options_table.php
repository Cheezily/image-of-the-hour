<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->string('password');
            $table->boolean('use_matomo_tracking')->default(false);
            $table->string('matomo_url')->nullable();
            $table->string('matomo_id')->nullable();
        });

        DB::table('options')->insert([
            'password' => 1234
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('options');
    }
};
