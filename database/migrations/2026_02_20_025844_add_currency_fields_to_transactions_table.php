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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('original_currency', 3)->nullable()->after('amount');
            $table->decimal('original_amount', 12, 2)->nullable()->after('original_currency');
            $table->decimal('exchange_rate', 10, 4)->nullable()->after('original_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['original_currency', 'original_amount', 'exchange_rate']);
        });
    }
};
