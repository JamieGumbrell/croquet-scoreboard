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
        Schema::create('scoreboards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('uid');
            $table->string('title');
            $table->string('color');
            $table->string('name1');
            $table->string('name2');
            $table->string('country1');
            $table->string('country2');
            $table->string('ball_color');
            $table->integer('games1');
            $table->integer('games2');
            array_map(fn($i) => $table->integer("score{$i}"), range(1, 10));
            $table->timestamps();
        });

        Schema::create('player_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_list_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('link');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scoreboards');
        Schema::dropIfExists('players');
        Schema::dropIfExists('player_lists');
        Schema::dropIfExists('countries');
    }
};
