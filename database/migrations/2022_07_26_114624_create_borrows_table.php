<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBorrowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('borrows', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->comment('รหัสอ้างอิงการยืม');
            $table->dateTime('borrow_date')->comment('วัน-เวลา ที่ยืม');
            $table->unsignedBigInteger('student_id')->nullable()->comment('นักศึกษา');
            $table->foreign('student_id')->references('id')->on('students');
            $table->unsignedBigInteger('equipment_id')->nullable()->comment('อุปกรณ์');
            $table->foreign('equipment_id')->references('id')->on('equipment');
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
        Schema::dropIfExists('borrows');
    }
}
