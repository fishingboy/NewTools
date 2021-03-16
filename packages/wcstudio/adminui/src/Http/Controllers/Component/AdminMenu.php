<?php
namespace WcStudio\AdminUi\Http\Controllers\Component;

use WcStudio\AdminUi\Services\Purview\MenuPurviewService;

/**
 * Class MenuComponent
 * Menu 的 Component
 * 目前只處理資料，需要傳入使用者的 email 來抓取相對應權限的 Menu
 *
 * @package App\Http\Controllers\Dashboard\Component
 */
Class AdminMenu
{
    /**
     * @var string email
     */
    public $user = "";

    /**
     * 設定該 component 的值
     * @param $config['user'] 使用者的 email
     */
    public function setComponentConfig($config){

        $this->user = $config['user'];
    }

    /**
     * 處理 component 的資料
     *
     * @return array Menu 結構
     */
    public function componentHandle(){
        $myService = new MenuPurviewService();
        $result = $myService->MenuList($this->user, true);
        return $result['data'];
    }

}
