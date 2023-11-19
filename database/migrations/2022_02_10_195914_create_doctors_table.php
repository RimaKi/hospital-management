<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->string('userId')->primary()->unique();
            $table->string('specializationId');
            $table->string('education')->nullable();
            $table->date('graduation');
            $table->string('experience')->nullable();
            $table->string('availableDays')->default("");//1;2;3
            $table->string('availableHours')->default("");//9-10;16-20
            $table->integer('sessionTime')->default(15);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctors');
    }
};
