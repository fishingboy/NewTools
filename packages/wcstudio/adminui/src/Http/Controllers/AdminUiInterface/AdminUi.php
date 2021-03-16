<?php


namespace WcStudio\AdminUi\Http\Controllers\AdminUiInterface;


interface AdminUi
{
    public function setComponent($components = [], $parameter = []);

    public function setView($view_path = '');

    public function handle();

}
