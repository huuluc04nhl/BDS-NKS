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
        Schema::table('users', function (Blueprint $table) {
            $table->string('firstname')->nullable()->after('status');
            $table->string('lastname')->nullable()->after('firstname');
            $table->text('intro')->nullable()->after('lastname');
            $table->integer('gender')->default(0)->after('intro');
            $table->string('website')->nullable()->after('gender');
            $table->string('dob')->nullable()->after('website');
            $table->string('pob')->nullable()->after('dob');
            $table->string('id_number')->nullable()->after('pob');
            $table->string('id_date')->nullable()->after('id_number');
            $table->string('id_place')->nullable()->after('id_date');
            $table->string('province')->nullable()->after('id_place');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'firstname',
                'lastname',
                'intro',
                'gender',
                'website',
                'dob',
                'pob',
                'id_number',
                'id_date',
                'id_place',
                'province'
            ]);
        });
    }
};
