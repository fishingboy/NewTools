<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminUIDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::connection('mysql')->hasTable('adminui_pages')){

            Schema::create('adminui_pages', function(Blueprint $table){
                $table->bigIncrements('page_id')->comment("系統流水號");
                $table->string('name', 255)->comment("report name, Ex: Inbound, Outbound");
                $table->string('type', 255)->comment("report type, Ex: Logistic, Statment");
                $table->string('description', 255)->comment("describe report purpose");
                $table->string('table_class', 255)->comment("Export Entities file path");
                $table->timestamps();
            });
        }

        if(!Schema::connection('mysql')->hasTable('adminui_component_configures')){

            Schema::create('adminui_component_configures', function(Blueprint $table){
                $table->bigIncrements('config_id')->comment("系統流水號");
                $table->unsignedBigInteger('page_id')->comment("");
                $table->string('date_pick_start', 255)->comment("date range picker start time");
                $table->string('date_pick_end', 255)->comment("date range picker end time");
                $table->mediumText('date_pick_range')->comment("");
                $table->mediumText('date_pick_field')->comment("");
                $table->mediumText('table_list_select_columns')->comment("which columns you want");
                $table->string('table_list_func', 255)->comment("");
                $table->mediumText('table_list_invisible_columns')->comment('which columns will invisible by default');
                $table->mediumText('table_list_order_set')->nullable()->comment("Table Order Set");
                $table->string('footer_arr', 255)->nullable()->comment("");
                $table->string('footer_func', 255)->nullable()->comment("");

                $table->timestamps();

            });
        }

        if(!Schema::connection('mysql')->hasTable('adminui_component_dropdown_options')){

            Schema::create('adminui_component_dropdown_options', function(Blueprint $table){
                $table->bigIncrements('id')->comment("系統流水號");
                $table->bigInteger('page_id')->comment("page id");
                $table->string('option_type', 64);
                $table->string('option_key', 64);
                $table->string('option_value', 64);
                $table->timestamps();
            });
        }

        if(!Schema::connection('mysql')->hasTable('adminui_groups')){

            Schema::connection('mysql')->create('adminui_groups', function(Blueprint $table){
                $table->bigIncrements('group_id')->comment("系統流水號");
                $table->string('group_name', 20)->comment("群組名稱");
                $table->string('status', 1)->comment("是否啟用：Y/N");
                $table->timestamps();

            });
        }

        if(!Schema::connection('mysql')->hasTable('adminui_group_purview')){

            Schema::connection('mysql')->create('adminui_group_purview', function(Blueprint $table){
                $table->unsignedBigInteger('group_id')->comment("group id");
                $table->unsignedBigInteger('menu_id')->comment("寫入時，如有子女menu_id，父親menu_id也需寫入;有權限才寫入，無權限刪除");
                $table->string('status', 1)->default('Y')->comment("是否啟用：Y/N");
                $table->primary(array('group_id','menu_id'));
                $table->timestamps();

            });
        }

        if(!Schema::connection('mysql')->hasTable('adminui_group_users')){

            Schema::connection('mysql')->create('adminui_group_users', function(Blueprint $table){
                $table->unsignedBigInteger('group_id')->comment("group id");
                $table->unsignedBigInteger('user_id')->comment("user可所屬多個群組，權限取聯集； 有群組才寫入，無群組刪除");
                $table->primary(array('group_id','user_id'));
                $table->timestamps();
            });
        }

        if(!Schema::connection('mysql')->hasTable('adminui_menus')){
            Schema::connection('mysql')->create('adminui_menus', function(Blueprint $table){
                $table->bigIncrements('menu_id')->comment('menu_id');
                $table->string('menu_name', 30)->comment('menu名稱');
                $table->integer('menu_layer')->comment('menu層級，從0開始');
                $table->unsignedBigInteger('parent_id')->nullable()->comment('父親ID，menu_layer <> 0時為必填');
                $table->integer('order_by')->nullable()->comment('排序編號，未填就依據id排序');
                $table->string('status', 1)->comment('是否啟用：Y/N');
                $table->string('url', 255)->comment('URL');
                $table->string('menu_visible', 1)->default('Y')->comment('是否顯示：Y/N');
                $table->timestamps();
            });
        }


        if( ! Schema::connection('mysql')->hasTable('adminui_users')){
            Schema::connection('mysql')->create('adminui_users', function (Blueprint $table) {
                $table->id()->autoIncrement();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adminui_pages');
        Schema::dropIfExists('adminui_component_configures');
        Schema::dropIfExists('adminui_component_dropdown_options');
        Schema::dropIfExists('adminui_groups');
        Schema::dropIfExists('adminui_group_purview');
        Schema::dropIfExists('adminui_group_users');
        Schema::dropIfExists('adminui_menus');
        Schema::dropIfExists('adminui_users');

    }
}
