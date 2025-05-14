<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // butuh doctrine/dbal kalau mau pakai ->change()
        Schema::table('users', function (Blueprint $table) {
            $table->string('no_wa')
                  ->default('62')
                  ->nullable()
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('no_wa')
                  ->default('+62')   // kembalikan seperti semula
                  ->nullable()
                  ->change();
        });
    }
};
