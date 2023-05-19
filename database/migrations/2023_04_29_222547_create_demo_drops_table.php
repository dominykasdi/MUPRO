<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemoDropsTable extends Migration
{
    public function up(): void
    {
        Schema::create('demo_drops', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('learner_id');
            $table->unsignedBigInteger('curator_id')->nullable();
            $table->String('file_path');
            $table->String('demo_title');
            $table->text('note')->nullable();
            $table->text('feedback')->nullable();
            $table->boolean('is_checked')->default(false);
            $table->timestamps();

            $table->foreign('learner_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('curator_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demo_drops');
    }
}

