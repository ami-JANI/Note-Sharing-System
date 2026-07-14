<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Replaces the old "Semester 1/2/3" naming with the standard 8-semester
     * year-term format (1-1, 1-2, ... 4-2). Existing rows are renamed in
     * place (not recreated) so notes already pointing at a semester_id keep
     * working.
     */
    public function up(): void
    {
        $existing = DB::table('semesters')->orderBy('order')->pluck('id')->all();

        // Nothing to migrate on a fresh install (empty table) — the seeder
        // creates the 8 semesters directly in that case.
        if ($existing === []) {
            return;
        }

        $names = ['1-1', '1-2', '2-1', '2-2', '3-1', '3-2', '4-1', '4-2'];

        foreach ($names as $order => $name) {
            if (isset($existing[$order])) {
                DB::table('semesters')->where('id', $existing[$order])->update([
                    'name' => $name,
                    'order' => $order,
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('semesters')->insert([
                    'name' => $name,
                    'order' => $order,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        $rows = DB::table('semesters')->orderBy('order')->get();

        if ($rows->isEmpty()) {
            return;
        }

        $oldNames = ['Semester 1', 'Semester 2', 'Semester 3'];

        foreach ($rows as $i => $row) {
            if (isset($oldNames[$i])) {
                DB::table('semesters')->where('id', $row->id)->update(['name' => $oldNames[$i]]);
            } else {
                DB::table('semesters')->where('id', $row->id)->delete();
            }
        }
    }
};
