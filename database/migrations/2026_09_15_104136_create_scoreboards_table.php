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
            $table->string('uid')->unique();
            $table->string('title')->default('New Event');
            $table->string('color')->default('#00ff00');
            $table->string('name1')->nullable();
            $table->string('name2')->nullable();
            $table->foreignId('country1')->nullable()->constrained('countries')->nullOnDelete();
            $table->foreignId('country2')->nullable()->constrained('countries')->nullOnDelete();
            $table->string('ball_color')->default('primary');
            $table->integer('games1')->default('0');
            $table->integer('games2')->default('0');
            array_map(fn($i) => $table->integer("score{$i}")->default('0'), range(1, 10));
            $table->string('design')->default('default');
            $table->integer('width')->default('800');
            $table->string('scoretype')->default('single');
            $table->string('gametype')->default('0');
            $table->timestamps();
        });

        Schema::create('player_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->boolean('enabled')->default(false);
            $table->timestamps();
        });

        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_list_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->foreignId('country')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
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
