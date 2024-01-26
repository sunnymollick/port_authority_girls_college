<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::create('pages', function (Blueprint $table) {
         $table->increments('id');
         $table->string('slug')->unique()->nullable();
         $table->string('title')->nullable();
         $table->longText('description')->nullable();
         $table->text('summery')->nullable();
         $table->string('file_path')->default('assets/images/default/featured.png');
         $table->integer('uploaded_by');
         $table->tinyInteger('status')->default(1);
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
      Schema::dropIfExists('pages');
   }
}
