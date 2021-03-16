<?php

namespace WcStudio\AdminUi\Repositories;

use Illuminate\Database\Eloquent\Model;
use WcStudio\AdminUi\Repositories\Component\DropDownOption;

class AdminUiComponentConfigure extends Model
{
    //
    protected $table = 'adminui_component_configures';
    protected $primaryKey = 'config_id';



    public function adminui_main()
    {
        return $this->belongsTo(AdminUiPage::class, 'report_id');
    }

    public function dropdown_option()
    {
        return $this->hasMany(DropDownOption::class, 'page_id')->orderBy('id', 'ASC');
    }
}
