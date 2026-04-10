<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('id_card_number')->nullable();
            $table->string('company')->nullable();
            $table->string('purpose');
            $table->string('person_to_meet');
            $table->datetime('check_in_time');
            $table->datetime('check_out_time')->nullable();
            $table->string('status')->default('active');
            $table->foreignId('registered_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('visitors');
    }
};