<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeBannerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_banner', function (Blueprint $table) {
            $table->increments('id');
            $table->text('url')->nullable();
            $table->integer('arrange')->default('0');
            $table->tinyInteger('is_show')->default('1');
            $table->timestamp('publish_at')->nullable();
            $table->timestamp('offline_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('home_banner');
    }
}
