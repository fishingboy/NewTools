<?php

namespace WcStudio\AdminUi\Repositories\Purview;

use Illuminate\Database\Eloquent\Model;

class AdminUiGroup extends Model
{
    protected $table = 'adminui_groups';
    protected $connection = "mysql";

    protected $fillable = [
        'group_id',
        'group_name',
        'status',
    ];

}
