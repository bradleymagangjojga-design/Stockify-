<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $defaults = [
            'base_currency'     => 'IDR',
            'exchange_rate_USD' => '16000',
            'exchange_rate_SGD' => '11800',
            'exchange_rate_MYR' => '3400',
            'exchange_rate_EUR' => '17000',
            'exchange_rate_GBP' => '20000',
        ];

        foreach ($defaults as $key => $value) {
            $exists = DB::table('settings')->where('key', $key)->exists();
            if (!$exists) {
                DB::table('settings')->insert([
                    'key'        => $key,
                    'value'      => $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'base_currency',
            'exchange_rate_USD',
            'exchange_rate_SGD',
            'exchange_rate_MYR',
            'exchange_rate_EUR',
            'exchange_rate_GBP',
        ])->delete();
    }
};
