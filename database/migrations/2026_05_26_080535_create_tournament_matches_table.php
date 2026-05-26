<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('tournament_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            $table->integer('round');

            $table->unsignedBigInteger('team1_id')->nullable();
            $table->unsignedBigInteger('team2_id')->nullable();

            $table->integer('team1_score')->nullable();
            $table->integer('team2_score')->nullable();

            $table->unsignedBigInteger('winner_id')->nullable();
            $table->unsignedBigInteger('next_match_id')->nullable();
            $table->timestamps();

            $table->foreign('team1_id')->references('id')->on('teams')->onDelete('set null');
            $table->foreign('team2_id')->references('id')->on('teams')->onDelete('set null');
            $table->foreign('winner_id')->references('id')->on('teams')->onDelete('set null');
            $table->foreign('next_match_id')->references('id')->on('tournament_matches')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournament_matches');
    }
};
