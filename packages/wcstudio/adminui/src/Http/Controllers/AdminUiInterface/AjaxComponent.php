<?php


namespace WcStudio\AdminUi\Http\Controllers\AdminUiInterface;


interface AjaxComponent
{
    public function setAjaxModel(Object $service);

    public function setParameter($parameter = []);

    public function outputResponse($code = '', $msg = '', $data = []);

    public function doAction();

}
