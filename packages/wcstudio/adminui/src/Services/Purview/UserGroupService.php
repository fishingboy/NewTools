<?php

namespace WcStudio\AdminUi\Services\Purview;

use WcStudio\AdminUi\Repositories\Purview\AdminUiGroup;
use WcStudio\AdminUi\Repositories\Purview\AdminUiGroupPurview;
use WcStudio\AdminUi\Repositories\Purview\AdminUiGroupUsers;
use WcStudio\AdminUi\Repositories\Component\AdminUiMenu;
use WcStudio\AdminUi\Repositories\AdminUser;

class UserGroupService
{
    public function getGroup($groupId = '')
    {
        if ($groupId == '') {
            $groupData = AdminUiGroup::get()->all();
        } else {
            $groupData = AdminUiGroup::where('group_id', '=', $groupId)->first();
        }

        return $groupData;
    }

    public function addOrUpdateGroup($data)
    {
        if (isset($data['group_id'])) {
            $group = AdminUiGroup::where('group_id', '=', $data['group_id']);
            if ($group->exists()) {
                $group->update([
                    'group_name' => $data['group_name'],
                    'status' => $data['status'],
                    'updated_user' => $data['updated_user'],
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                return $data['group_id'];
            }
        } else {
            AdminUiGroup::insert([
                'group_name' => $data['group_name'],
                'status' => $data['status'],
                'created_user' => $data['updated_user'],
                'updated_user' => $data['updated_user'],
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return AdminUiGroup::where('group_name', '=', $data['group_name'])->first()->group_id;
        }
    }

    public function getGroupUsers($groupId)
    {
        $group = AdminUiGroup::leftjoin('group_users', 'group.group_id', '=', 'group_users.group_id')
            ->leftjoin('users', 'group_users.user_id', '=', 'users.id')
            ->where('group.group_id', '=', $groupId)
            ->get([
                'users.id AS user_id', 'users.email', 'users.name', 'group_users.created_at', 'group.group_id',
                'group.group_name'
            ]);

        return $group;
    }

    public function removeGroupUser($groupId, $userId)
    {
        AdminUiGroupUsers::where('user_id', '=', $userId)
            ->where('group_id', '=', $groupId)
            ->delete();

        return $this->getGroupUsers($groupId);
    }

    public function addGroupUsers($data)
    {
        $user = AdminUser::where('email', '=', $data['user'])
            ->orWhere('name', 'like', '%'.$data['user'].'%')
            ->first();

        if (!$user) {
            return null;
        } else {
            if (AdminUiGroupUsers::where('user_id', '=', $user->id)->where('group_id', '=', $data['groupId'])->exists()) {
                return 200;
            } else {
                AdminUiGroupUsers::insert([
                    'group_id' => $data['groupId'],
                    'user_id' => $user->id,
                    'created_user' => $data['created_user'],
                    'updated_user' => $data['created_user'],
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                return $this->getGroupUsers($data['groupId']);
            }
        }
    }

    public function getGroupPurview($groupId)
    {
        return AdminUiGroupPurview::where('group_id', '=', $groupId)->get();
    }

    public function updateGroupPurview($groupId, $data)
    {
        // check if exist and insert all menu
        $insertMenu = array();
//        $user = (isset($data['created_user'])) ? $data['created_user'] : $data['updated_user'];
        $allMenu = AdminUiMenu::get()->all();
        $groupPurview = AdminUiGroupPurview::where('group_id', '=', $groupId)->pluck('menu_id')->toArray();
        foreach ($allMenu as $menu) {
            if (!in_array($menu['menu_id'], $groupPurview)) {
                $insertMenu[] = [
                    'group_id' => $groupId,
                    'menu_id' => $menu['menu_id'],
                    'created_user' => $data['updated_user'],
                    'updated_user' => $data['updated_user'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
            }
        }

        AdminUiGroupPurview::insert($insertMenu);

        AdminUiGroupPurview::where('group_id', '=', $groupId)->update([
            'status' => 'N', 'updated_user' => $data['updated_user']
        ]);

        // update status
        foreach ($data as $menuId => $status) {
            if (is_numeric($menuId)) {
                AdminUiGroupPurview::where('group_id', '=', $groupId)
                    ->where('menu_id', '=', $menuId)
                    ->update(['status' => 'Y']);
            }
        }

//        return GroupPurview::where('group_id', '=', $groupId)->get();
        return $groupId;
    }

    public function updateGroupStatus($groupId, $status)
    {
        AdminUiGroup::where('group_id', '=', $groupId)
            ->update(['status' => $status]);
        return AdminUiGroup::where('group_id', '=', $groupId)->first();
    }

}
