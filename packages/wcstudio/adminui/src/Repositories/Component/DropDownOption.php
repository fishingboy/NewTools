<?php

namespace WcStudio\AdminUi\Repositories\Component;

use Illuminate\Database\Eloquent\Model;

class DropDownOption extends Model
{

    protected $table = 'adminui_component_dropdown_options';
    protected $primaryKey = 'id';
    protected $fillable = ['page_id', 'option_type', 'option_key', 'option_value'];

}
