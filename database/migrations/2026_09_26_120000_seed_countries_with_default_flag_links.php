<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $flags = [
            'Australia' => 'aus.jpg',
            'Austria' => 'aut.jpg',
            'Belgium' => 'bel.jpg',
            'Canada' => 'can.jpg',
            'Switzerland' => 'che.jpg',
            'Czech Republic' => 'cze.jpg',
            'Germany' => 'deu.jpg',
            'Egypt' => 'egy.jpg',
            'England' => 'eng.jpg',
            'Spain' => 'esp.jpg',
            'Finland' => 'fin.jpg',
            'Hong Kong' => 'hkg.jpg',
            'India' => 'ind.jpg',
            'Ireland' => 'ire.jpg',
            'Italy' => 'ita.jpg',
            'Jersey' => 'jey.jpg',
            'Japan' => 'jpn.jpg',
            'Latvia' => 'lva.jpg',
            'Mexico' => 'mex.jpg',
            'Norway' => 'nor.jpg',
            'New Zealand' => 'nzd.jpg',
            'Portugal' => 'prt.jpg',
            'Scotland' => 'sco.jpg',
            'Sweden' => 'swe.jpg',
            'Uruguay' => 'ury.jpg',
            'United States' => 'usa.jpg',
            'Wales' => 'wal.jpg',
            'South Africa' => 'zaf.jpg',
        ];

        foreach ($flags as $name => $filename) {
            $country = DB::table('countries')->where('name', $name);
            $link = 'countries/'.$filename;

            if ($country->exists()) {
                $country->update([
                    'link' => $link,
                    'updated_at' => now(),
                ]);
                continue;
            }

            DB::table('countries')->insert([
                'name' => $name,
                'link' => $link,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Country defaults can be assigned to players or edited after this migration.
     * Keep that data intact if this migration is rolled back.
     */
    public function down(): void
    {
    }
};