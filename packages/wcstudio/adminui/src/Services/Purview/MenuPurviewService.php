<?php

namespace WcStudio\AdminUi\Services\Purview;

use WcStudio\AdminUi\Repositories\Purview\AdminUiGroupUsers;
use WcStudio\AdminUi\Repositories\Purview\AdminUiGroupPurview;
use WcStudio\AdminUi\Repositories\Component\AdminUiMenu;
use WcStudio\AdminUi\Repositories\AdminUser;
use Exception;
use ServiceResponse;
use WcStudio\Tool\Exceptions\AsusGatewayException;

class MenuPurviewService
{
    const MENU_ROOT = "menu_root";
    /**
     * @var string log
     */
    private $log;

    /**
     * 回傳全部menu樹狀結構 by ice
     *
     * @return
     */
    public function AllMenu()
    {
        try {
            $user_menus = AdminUiMenu::where('status', 'Y')->select('menu_id', 'menu_name', 'menu_layer', 'url',
                'parent_id')->orderBy('menu_id', 'ASC')->get()->toArray();

            //menu樹狀結構化
            $menu_tree = $this->get_children_tree($user_menus);

            return ServiceResponse::parseStatus(200, 'Get all menu list success!!', $menu_tree);
        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
            $errorCode = $e->getCode();
            return ServiceResponse::parseStatus($errorCode, $errorMsg, []);
        }
    }

    /**
     * 依據user email查詢 menu 權限，回傳menu樹狀結構 by ice
     * visible為true時, 將加入visible查詢條件, 使回傳結果過濾掉visible為N的menu
     *
     * @param string $user_email
     * @param bool $visible
     *
     * @return array $response
     * @todo 每次都撈 DB，可以改先吃 session 就好
     *
     */
    public function MenuList($user_email, $visible = false)
    {
        $menu_id = [];

        try {
            //檢查傳入參數必填
            if (empty($user_email) OR is_null($user_email)) {
                return ServiceResponse::parseStatus(200, 'No Data', []);
            }
            //user所屬的group => 列出該group所有的menu
            $user_id = AdminUser::where('email', $user_email)->value('id');


            $purview_menu = AdminUiGroupPurview::whereIn(
                        'group_id',
                        AdminUiGroupUsers::join('adminui_groups', 'adminui_group_users.group_id', '=', 'adminui_groups.group_id')
                            ->where('adminui_group_users.user_id', $user_id)
                            ->where('adminui_groups.status', 'Y')
                            ->select('adminui_group_users.group_id')
                        )
                    ->where('status','Y')
                    ->select('menu_id')
                    ->get()
                    ->toArray();

            foreach ($purview_menu as $id) {
                $menu_id[] = $id['menu_id'];
            }

            $parent_menu_id = $this->get_parent_id($menu_id, $menu_id);
            $son_menu_id = $this->get_son_id($menu_id);
            $user_menu_id = array_merge($parent_menu_id, $son_menu_id);

            $user_menus = AdminUiMenu::where('status', 'Y')->whereIn('menu_id', $user_menu_id);

            if ($visible) {
                $user_menus = $user_menus->where('menu_visible','Y');
            }

            $user_menus = $user_menus->select('menu_id', 'menu_name', 'url', 'parent_id', 'order_by')
                ->orderBy('order_by', 'ASC')->get()->toArray();

            //menu樹狀結構化
            $menu_tree = $this->get_children_tree($user_menus);

            return ServiceResponse::parseStatus(200, 'Get menu list success!!', $menu_tree);

        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
            $errorCode = $e->getCode();

            dump($errorMsg);
            return ServiceResponse::parseStatus($errorCode, $errorMsg, []);
        }
    }

    /**
     * get_childs_tree function
     * ( 遞迴 ) 樹狀結構陣列  by ice
     *
     * @param  array  $items
     * @param  int  $parent_id
     *
     * @return array
     */
    private function get_children_tree($items = [], $parent_id = 0): array
    {
        $results = [];

        $items = collect($items)->sortBy('order_by')->toArray();

        foreach ($items as $item) {
            if ($item['parent_id'] === $parent_id) {
                $menu_id = $item['menu_id'];
                $item['sub'] = $this->get_children_tree($items, $menu_id);
                $results[] = $item;
            }
        }
        return $results;
    }

    /**
     * get_parent_id function
     * ( 遞迴 ) 列出有權限的爸爸 by ice
     *
     * @param  array  $menu_ids  => parent_id
     * @param  array  $all_menu_ids  => 各階層parent_id集合，如此才能回傳所有找出的parent_id
     *
     * @return array
     */
    private function get_parent_id($menu_ids = [], $all_menu_ids = []): array
    {
        $results = [];
        $all_ids = [];

        $items = AdminUiMenu::whereIn('menu_id', $menu_ids)->get()->toArray();

        $parent = [];
        foreach ($items as $item) {
            if ($item['parent_id'] != 0) {
                $parent[] = $item['parent_id'];
            }
        }

        if (count($parent) > 0) {
            $all_ids = array_merge($all_menu_ids, $parent);
            $results = $this->get_parent_id($parent, $all_ids);
        } else {
            $results = $all_menu_ids;
        }

        return $results;
    }

    /**
     * get_child_id function
     * ( 遞迴 )列出有權限的小孩 by ice
     *
     * @param  array  $menu_ids  => child menu_id
     * @param  array  $all_menu_ids  => 各階層child menu_id集合，如此才能回傳所有找出的child menu_id
     *
     * @return array
     */
    private function get_son_id($menu_ids = [], $all_menu_ids = []): array
    {
        $results = [];
        $all_ids = [];
        $items = AdminUiMenu::whereIn('parent_id', $menu_ids)->get()->toArray();

        $parent = [];
        foreach ($items as $item) {
            $parent[] = $item['menu_id'];
        }

        if (count($parent) > 0) {
            $all_ids = array_merge($all_menu_ids, $parent);
            $results = $this->get_son_id($parent, $all_ids);
        } else {
            $results = $all_menu_ids;
        }

        return $results;
    }


    /**
     * check if value is in array for menulist
     *
     * @param $value string find
     * @param $arr array found
     *
     * @return boolean
     * */
    public function checkIsExist($value, $arr)
    {

        //如果是根目錄的，開放讓它都可以進來
        if ($value == self::MENU_ROOT) {
            return true;
        }

        for ($i = 0; $i < count($arr); $i++) {
            if ($arr[$i]['menu_name'] == $value) {
                return true;
            }
            if ($arr[$i]['sub'] != null) {
                $result = $this->checkIsExist($value, $arr[$i]['sub']);
                if ($result) {
                    return $result;
                }
            }
        }

        return false;
    }
}
