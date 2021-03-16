<?php

namespace WcStudio\AdminUi\Repositories\Purview;

use Illuminate\Database\Eloquent\Model;

class AdminUiGroupPurview extends Model
{
    protected $table = 'adminui_group_purview';
    protected $connection = "mysql";

    protected $fillable = [
        'group_id',
        'menu_id',
        'status'
    ];

}
