<?php

namespace WcStudio\AdminUi\Services\Purview;

use ServiceResponse;
use NewLog;
use WcStudio\Tool\Http\Resources\ApiResponse;
use WcStudio\AdminUi\Repositories\Component\AdminUiMenu;
use Exception;

class MenuService
{

    /**
     * @var Menus
     */
    private $menus;

    /**
     * EcmwMenuService constructor.
     * @param ApiResponse $ApiResponse
     */
    public function __construct()
    {
        $this->response = new ApiResponse;
    }

    /**
     * return all menu data, regardless their status
     */
    public function getAllMenu()
    {
        try {
            $user_menus = AdminUiMenu::select(
                'menu_id',
                'menu_name',
                'menu_layer',
                'url',
                'parent_id',
                'order_by',
                'status',
                'menu_visible'
            )->get()->toArray();
            $menu_tree = $this->getChildrenTree($user_menus);

            return ServiceResponse::parseStatus(200, 'Get all menu list success!!', $menu_tree);
        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
            $errorCode = $e->getCode();
            return ServiceResponse::parseStatus($errorCode, $errorMsg, array());
        }
    }

    /**
     * 以遞迴的方式去取得menu下的Children
     *
     * @param array $items
     * @param int $parent_id
     * @return array
     */
    private function getChildrenTree($items = array(), $parent_id = 0): array
    {
        $results = [];
        $items = collect($items)->sortBy('order_by')->toArray();
        foreach ($items as $item) {
            if ($item['parent_id'] === $parent_id) {
                $menu_id = $item['menu_id'];
                $item['sub'] = $this->getChildrenTree($items, $menu_id);
                $results[] = $item;
            }
        }
        return $results;
    }

    /**
     * 取得menu資料
     *
     * @param $id
     * @return array
     */
    public function getMenuDetail($id)
    {
        $post = AdminUiMenu::where('menu_id', $id)->get();

        if ($post){
            return $this->response->parseStatus(200, null, $post);
        } else{
            return $this->response->parseStatus(500, null, $post);
        }
    }

    /**
     * 新增menu
     *
     * @param $data
     * @return mixed
     */
    public function addMenu($data)
    {
        $result = AdminUiMenu::insert([
            'menu_name' => $data['menu_name'],
            'menu_layer' => $data['menu_layer'],
            'parent_id' => $data['parent_id'],
            'order_by' => $data['order_by'],
            'status' => $data['status'],
            'menu_visible' => $data['menu_visible'],
            'url' => $data['url'],
            'created_user' => $data['menu_create_usr'],
            'updated_user' => $data['menu_create_usr'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return $result;
    }

    /**
     * 更新menu item
     *
     * @param $data
     * @param $id
     * @return mixed
     */
    public function updateMenuItem($data, $id)
    {
        $updatedRows = AdminUiMenu::where('menu_id', '=', $id)
            ->update([
                'menu_name' => $data['menu_name'],
                'status' => $data['status'],
                'menu_visible' => $data['menu_visible'],
                'url' => $data['url'],
                'updated_user' => $data['menu_update_usr'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        return $updatedRows;
    }

    /**
     * 更新menu list
     *
     * @param $data
     * @return mixed
     */
    public function updateMenuList($data)
    {
        try {
            $this->menus = AdminUiMenu::get()->toArray();

            $this->updateMenuListChildren($data['menuList'], 0, -1, $data['menu_update_usr']);

            return ServiceResponse::parseStatus(200, 'Update menu list success', array());
        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
            $errorCode = $e->getCode();
            return ServiceResponse::parseStatus($errorCode, $errorMsg, array());
        }
    }

    /**
     * 以遞迴的方式去更新menu底下的Children
     *
     * @param $lists
     * @param $parentId
     * @param $menuLayer
     * @param $updateUser
     */
    public function updateMenuListChildren($lists, $parentId, $menuLayer, $updateUser)
    {
        $menuLayer++;
        $orderBy = 0;
        foreach ($lists as $list) {
            $orderBy++;

            $key = array_search($list['id'], array_column($this->menus, 'menu_id'));
            $menu = $this->menus[$key];

            if ($menu['parent_id'] != $parentId || $menu['menu_layer'] != $menuLayer || $menu['order_by'] != $orderBy) {
                AdminUiMenu::where('menu_id', '=', $list['id'])
                    ->update([
                        'parent_id' => $parentId,
                        'menu_layer' => $menuLayer,
                        'order_by' => $orderBy,
                    ]);
            }

            if (array_key_exists('children', $list)) {
                $this->updateMenuListChildren($list['children'], $list['id'], $menuLayer, $updateUser);
            }
        }
    }

    public function getNewSortOrderNumber($data)
    {
        return (AdminUiMenu::where('parent_id', '=', $data['parent_id'])->count()) + 1;
    }
}
