<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Tags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag', function (Blueprint $table) {
            $table->id();
            $table->string('name', 1024)->comment('名稱');
            $table->string('show_space', 1024)->comment('哪裡呈現');

            $table->string('slug', 1024)->comment('slug');
            $table->timestamps();
        });

        Schema::create('article_tag_relations', function (Blueprint $table) {
            $table->id();
            $table->string('article_id', 1024)->comment('文章 id');
            $table->string('tag_id', 1024)->comment('tag id');
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
        Schema::dropIfExists('tag');
        Schema::dropIfExists('article_tag_relations');
    }
}
