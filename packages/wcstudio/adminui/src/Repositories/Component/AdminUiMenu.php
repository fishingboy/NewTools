<?php

namespace WcStudio\AdminUi\Repositories\Component;

use Illuminate\Database\Eloquent\Model;

class AdminUiMenu extends Model
{
    protected $table = 'adminui_menus';
    protected $connection = "mysql";

    protected $fillable = [
        'menu_id',
        'menu_name',
        'menu_layer',
        'parent_id',
        'order_by',
        'status',
        'url',
    ];

}
