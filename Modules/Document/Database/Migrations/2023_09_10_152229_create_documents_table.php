<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('original_filename');
            $table->unsignedBigInteger('user_id')->index('user_id')->nullable();
            $table->unsignedBigInteger('application_id')->index('application_id')->nullable();
            $table->string('application_type')->index('application_type')->nullable();
            $table->unsignedInteger('document_type_id')->index('document_type_id');
            $table->unsignedBigInteger('created_by')->index('created_by')->nullable();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('document_type_id')->references('id')->on('document_types');
            $table->foreign('created_by')->references('id')->on('users');
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
        Schema::dropIfExists('documents');
    }
};
