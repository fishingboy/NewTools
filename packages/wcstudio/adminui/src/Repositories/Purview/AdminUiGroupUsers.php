<?php

namespace WcStudio\AdminUi\Repositories\Purview;

use Illuminate\Database\Eloquent\Model;

class AdminUiGroupUsers extends Model
{
    protected $table = 'adminui_group_users';
    protected $connection = "mysql";

    protected $fillable = [
        'group_id',
        'user_id',
        'created_user',
        'updated_user',
        'created_at',
        'updated_at'
    ];

}
