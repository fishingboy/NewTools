<?php

namespace WcStudio\AdminUi\Http\Controllers\Purview;

use WcStudio\Tool\Http\Resources\ApiResponse;
use WcStudio\AdminUi\Http\Controllers\AdminUiIndex;
use WcStudio\AdminUi\Http\Controllers\Dashboard\Index;
use WcStudio\AdminUi\Services\Purview\MenuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use DB;

class MenuManagementController extends AdminUiIndex
{
    protected $menuService;
    protected $response;
    private $menuManagementUrl;

    /**
     * @var bool
     */
    public $rootPage = false;

    public function __construct(MenuService $ecmwMenuService)
    {
        parent::__construct();
        $this->menuService = $ecmwMenuService;
        $this->response = new ApiResponse;
        $this->menuManagementUrl = route('adminui.menu.management.index');
    }

    /**
     * Show the application index.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        $this->init();
        $menuData = $this->menuService->getAllMenu();

        $result = array_merge(
            $this->handle(),
            [
                'pagename' => $this->page,
                'langfile' => $this->langfile,
                'tablelist' => $menuData['data']
            ]);

        return view('adminui::purview.menumanagement', $result);
    }

    public function addMenu(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'menu_name' => 'required|unique:menu|max:255',
            'menu_layer' => 'required|in:0,1,2',
            'parent_id' => 'required',
            'status' => 'required|in:Y,N',
            'menu_visible' => 'required|in:Y,N',
            'url' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors()); // 可加入其他錯誤訊息
        }

        $data = $request->all();

        $data['order_by'] = $this->menuService->getNewSortOrderNumber($data);
        $data['menu_create_usr'] = Auth::user()->name;

        $result = $this->menuService->addMenu($data);

        if ($result) {
            return redirect($this->menuManagementUrl)->with('message', __("menuManagement.Add_new_menu_success"));
        } else {
            return redirect($this->menuManagementUrl)->with('message', __("menuManagement.Add_new_menu_failed"));
        }
    }

    /**
     * 取得單筆 menu資料
     * @param $id
     * @return mixed
     */
    public function getMenu($id)
    {
        $post = $this->menuService;
        return $post->getMenuDetail($id);
    }

    public function updateMenuItem(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'menu_name' => 'required|max:255',
            'status' => 'required|in:Y,N',
            'menu_visible' => 'required|in:Y,N',
            'url' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors()); // 可加入其他錯誤訊息
        }

        $data = $request->all();
        $data['menu_update_usr'] = Auth::user()->name;

        $updatedRow = $this->menuService->updateMenuItem($data, $id);

        if ($updatedRow != false && $updatedRow > 0) {
            return redirect($this->menuManagementUrl)->with('message', __("menuManagement.Update_menu_success"));
        } else {
            return redirect($this->menuManagementUrl)->with('message', __("menuManagement.Update_menu_failed"));
        }
    }

    public function updateMenuList(Request $request)
    {
        $data = $request->all();
        $data['menu_update_usr'] = Auth::user()->name;

        $response = $this->menuService->updateMenuList($data);

        return $response;
    }

}
