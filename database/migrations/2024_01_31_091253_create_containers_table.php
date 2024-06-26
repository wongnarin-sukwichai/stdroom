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
        Schema::create('containers', function (Blueprint $table) {
            $table->id();
            $table->integer('loc_id');
            $table->string('pic')->nullable();
            $table->string('title');
            $table->integer('limited')->comment('เข้าใช้บริการได้ไม่ต่ำกว่า');
            $table->integer('time_1')->comment('จ-ศ');
            $table->integer('time_2')->comment('ส-อ');
            $table->string('detail')->nullable();
            $table->integer('status')->comment('0=เปิด,1=ปิด');
            $table->string('created');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('containers');
    }
};
