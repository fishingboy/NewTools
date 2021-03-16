<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Article extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title', 1024)->comment('標題');
            $table->string('content', 1024)->comment('內容');
            $table->integer('global_category_id')->nullable()->comment('大分類');
            $table->integer('category_id')->nullable()->comment('分類');
            $table->string('tag_id', 10)->nullable()->comment('標籤');
            $table->char('publish', 10)->default('DRAFT')->comment("發佈狀態: PUBLISH, DRAFT, PRIVATE, SCHEDULE");
            $table->dateTime('schedule_start')->nullable()->comment("發佈時間 起");
            $table->dateTime('schedule_end')->nullable()->comment("發佈時間 迄");

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
        Schema::dropIfExists('articles');
    }
}
