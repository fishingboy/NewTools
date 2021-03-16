<?php
/**
 * Basic Dashboard 的基本頁面元素
 * 如果需要登入、生成 Menu，都可以繼承此頁
 *
 */

namespace WcStudio\AdminUi\Http\Controllers;

use WcStudio\AdminUi\Http\Controllers\Component\AdminMenu;
use WcStudio\AdminUi\Http\Controllers\AdminUiInterface\AdminUi;
use WcStudio\AdminUi\Services\Purview\MenuPurviewService;
use Illuminate\Http\Request;
use Log;

/**
 * Dashboard 的基底
 * 一定由各 Component 組成
 * 一定有 Menu 這個 Component
 * 一定要登入才能進來
 *
 * Class DashboardController
 * @package App\Http\Controllers\Dashboard
 */
class AdminUiIndex extends Controller implements AdminUi
{
    const MENU_ROOT = "menu_root";
    /**
     * @var string
     */
    public $view_path;

    /**
     * Component 的列表
     * @var object[]|string[]
     */
    public $components = [
        'menulist' => AdminMenu::class,
    ];

    /**
     * 要丟入 component 的參數
     * @var array
     */
    public $components_parameter = [
        'menulist' => [],
    ];

    /**
     * 要生成的樣版
     * @var string
     */
    public $view_base_path = 'adminui::';

    /**
     * @var string
     */
    public $page;

    /**
     * @var
     */
    public $menuName;

    protected $langfile = "menuManagement";



    public $rootPage = false;
    public $user_email;

    public $assignment = [

    ];

    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $this->init();

        return view($this->view_base_path . 'dashboard', $this->handle());
    }

    public function init()
    {
        $request = Request::capture();

        $this->getRequestParams($request);

        $this->isRootPage($request);

        $this->getPageName();

        $this->getMenuName();

        $this->checkUserPurview();

    }


    /**
     * @return string menu name
     */
    public function getMenuName()
    {
        if ($this->rootPage) {
            return $this->menuName = self::MENU_ROOT;
        }

        return $this->menuName = 'menu_';
    }


    /**
     *
     * @param  string  $view_path
     */
    public function setView($view_path = '')
    {
        $this->view_path = $view_path;

    }

    /**
     * 設定 component (由上方定義的 component 來決定此頁面要有哪些元件
     *
     * @param  array  $components
     * @param  array  $parameter
     *
     * @return mixed
     */
    public function setComponent($components = [], $parameter = [])
    {
        foreach ($components as $name => $path) {
            if (is_string($path)){
                $component_obj = app($path);
            }else{
                $component_obj = new $path();

            }
            $component_obj->setComponentConfig($parameter[$name]);
            $result[$name] = $component_obj->componentHandle();
        }

        return $result;

    }

    /**
     * 處理此頁料
     *
     * @return mixed 回傳 component 的結果，全部丟進樣版吃此結果
     */
    public function handle()
    {
        $this->components_parameter['menulist']['user'] = $this->user_email;
        $components_result = $this->setComponent($this->components, $this->components_parameter);

        return $components_result;

    }


    /**
     * @param  Request  $request
     */
    public function getRequestParams(Request $request)
    {

    }


    /**
     * @return string
     */
    public function getPageName()
    {
        $this->page = 'adminui';

        return $this->page;
    }

    /**
     * check user pruview of dashboard
     * 檢查使用者有無能用該 menu 的功能
     * 開放讓只要能登入的使用者可以到首頁
     *
     * @param $region_name string url上的資料 eu
     * @param $kind string url上的資料 statement
     * @param $file string url上的資料 inbound
     *
     * @return bool
     *
     */
    public function checkUserPurview()
    {
        $menuService = new MenuPurviewService();
        //$this->user_email = Auth::user()->email;
        $this->user_email = 'cloud.liao@gmail.com';

        if ($this->rootPage == true){
            return true;
        }


        $result = $menuService->MenuList($this->user_email);
        $check_result = $menuService->checkIsExist($this->menuName, $result['data']);


        return $check_result;

    }

    protected function isRootPage(Request $request)
    {
        if ($request->getBasePath() == ""){
            return true;
        }
    }

}
