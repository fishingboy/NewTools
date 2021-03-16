<?php

namespace WcStudio\AdminUi\Repositories;

use Illuminate\Database\Eloquent\Model;

class AdminUiPage extends Model
{
    protected $table = 'adminui_pages';
    protected $primaryKey = 'page_id';
    protected $guarded = [];

}
