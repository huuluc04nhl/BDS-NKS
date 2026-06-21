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
        if (DB::getDriverName() === 'pgsql') {
            // Alter properties table: is_verified to integer
            DB::statement('ALTER TABLE properties ALTER COLUMN is_verified DROP DEFAULT');
            DB::statement('ALTER TABLE properties ALTER COLUMN is_verified TYPE integer USING (CASE WHEN is_verified THEN 1 ELSE 0 END)');
            DB::statement('ALTER TABLE properties ALTER COLUMN is_verified SET DEFAULT 0');
            
            // Alter messages table: is_read to integer
            DB::statement('ALTER TABLE messages ALTER COLUMN is_read DROP DEFAULT');
            DB::statement('ALTER TABLE messages ALTER COLUMN is_read TYPE integer USING (CASE WHEN is_read THEN 1 ELSE 0 END)');
            DB::statement('ALTER TABLE messages ALTER COLUMN is_read SET DEFAULT 0');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE properties ALTER COLUMN is_verified DROP DEFAULT');
            DB::statement('ALTER TABLE properties ALTER COLUMN is_verified TYPE boolean USING (CASE WHEN is_verified = 1 THEN true ELSE false END)');
            DB::statement('ALTER TABLE properties ALTER COLUMN is_verified SET DEFAULT false');
            
            DB::statement('ALTER TABLE messages ALTER COLUMN is_read DROP DEFAULT');
            DB::statement('ALTER TABLE messages ALTER COLUMN is_read TYPE boolean USING (CASE WHEN is_read = 1 THEN true ELSE false END)');
            DB::statement('ALTER TABLE messages ALTER COLUMN is_read SET DEFAULT false');
        }
    }
};
