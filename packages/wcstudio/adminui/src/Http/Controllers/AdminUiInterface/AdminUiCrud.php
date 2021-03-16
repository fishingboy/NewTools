<?php


namespace WcStudio\AdminUi\Http\Controllers\AdminUiInterface;


interface AdminUiCrud
{

    public function edit($id);
    public function create();
    public function delete($id);

    public function showEditForm($id);
    public function showCreateForm();
    public function validator();

}
