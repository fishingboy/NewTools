<?php

namespace WcStudio\AdminUi\Http\Controllers\Purview;

use WcStudio\Tool\Http\Resources\ApiResponse;
use WcStudio\AdminUi\Http\Controllers\AdminUiIndex;
use WcStudio\AdminUi\Http\Controllers\AdminUiInterface\AdminUi;
use WcStudio\AdminUi\Services\Purview\MenuService;
use WcStudio\AdminUi\Services\Purview\UserGroupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use DB;

class GroupController extends AdminUiIndex
{
    protected $userGroupService;
    protected $menuService;
    protected $response;

    /**
     * @var bool
     */
    public $rootPage = false;

    public function __construct(UserGroupService $userGroupService, MenuService $menuService)
    {
        parent::__construct();
        $this->userGroupService = $userGroupService;
        $this->menuService = $menuService;
        $this->response = new ApiResponse;
    }

    /**
     * Show the application index.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->menuName = 'menu_user_group_management';

        if($this->checkUserPurview() == false){
            return redirect()->route('adminui');
        }

        $pagename = 'user-group-management';
        $langfile = 'groupManagement';

        $groupData = $this->userGroupService->getGroup();

        $result = array_merge($this->handle(),
            ['pagename' => $pagename, 'langfile' => $langfile, 'tablelist' => $groupData]);

        return view('adminui::purview/group', $result);
    }

    public function getGroupUsers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'groupId' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->response->parseStatus(422, 'validation fail');
        }

        $data = $request->all();
        $groupData = $this->userGroupService->getGroupUsers($data['groupId']);
        return $this->response->parseStatus(200, 'OK', $groupData);
    }

    public function removeGroupUsers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'groupId' => 'required',
            'userId' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->response->parseStatus(422, 'validation fail');
        }

        $data = $request->all();
        $groupData = $this->userGroupService->removeGroupUser($data['groupId'], $data['userId']);
        return $this->response->parseStatus(200, 'OK', $groupData);
    }

    public function addGroupUsers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'groupId' => 'required',
            'user' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->response->parseStatus(422, 'validation fail');
        }
        $data = $request->all();
        $data['created_user'] = Auth::user()->name;

        $groupData = $this->userGroupService->addGroupUsers($data);

        if ($groupData == null) {
            return $this->response->parseStatus(404, 'user not found');
        } elseif ($groupData == '200') {
            return $this->response->parseStatus(200, 'data exists');
        } else {
            return $this->response->parseStatus(201, 'OK', $groupData);
        }
    }

    public function getGroupPurview(Request $request)
    {
        $this->menuName = 'menu_group_purview_management';

        if($this->checkUserPurview() == false){
            return redirect()->route('dashboard');
        }

        $pagename = 'group-purview-management';
        $langfile = 'groupManagement';

        $action = (isset($request->all()['group_id'])) ? 'edit' : 'add';

        $menuData = $this->menuService->getAllMenu();

        $groupPurview = array();
        $group = array();
        if ($action == 'edit') {
            $groupId = $request->all()['group_id'];
            $groupPurview = $this->userGroupService->getGroupPurview($groupId);
            $group = $this->userGroupService->getGroup($groupId);
        }

        $result = array_merge($this->handle(), [
            'pagename' => $pagename, 'langfile' => $langfile, 'menuData' => $menuData['data'],
            'groupPurview' => $groupPurview, 'group' => $group
        ]);

        return view('adminui::purview/grouppurview', $result);
    }

    public function updateGroupPurview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_name' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->response->parseStatus(422, 'validation fail');
        }

        $data = $request->all();
        $data['updated_user'] = Auth::user()->name;

        $groupId = $this->userGroupService->addOrUpdateGroup($data);

        if ($groupId == '') {
            return $this->response->parseStatus(500, 'update or add group fail');
        }

        $result = $this->userGroupService->updateGroupPurview($groupId, $data);
        return $result;
    }

    public function updateGroupStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_id' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->response->parseStatus(422, 'validation fail');
        }

        $data = $request->all();
        $groupData = $this->userGroupService->updateGroupStatus($data['group_id'], $data['status']);
        return $this->response->parseStatus(200, 'OK', $groupData);
    }
}
