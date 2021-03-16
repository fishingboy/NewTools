<?php

namespace WcStudio\AdminUi\Tests\Feature;

use WcStudio\AdminUi\Repositories\Group;
use WcStudio\AdminUi\Repositories\GroupUsers;
use WcStudio\AdminUi\Repositories\AdminUiMenu;
use WcStudio\AdminUi\Repositories\AdminUser;
use WcStudio\AdminUi\Services\Purview\UserGroupService;
use WcStudio\AdminUi\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserGroupServiceTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Get group
     *
     * @test
     */
    public function testGetGroup()
    {
        // get all group
        $groupData = Group::get()->all();

        $userGroupService = new UserGroupService();
        $allGroup = $userGroupService->getGroup();

        $this->assertEquals($groupData, $allGroup);

        // get group by id
        $insertGroup = factory(Group::class)->make();
        $groupId = Group::insertGetId($insertGroup->toArray());
        $expectGroupData = Group::where('group_id', $groupId)->first();

        $getGroupById = $userGroupService->getGroup($groupId);

        $this->assertEquals($expectGroupData, $getGroupById);
    }

    /**
     * add or updateGroup
     *
     * @test
     */
    public function testAddOrUpdateGroup()
    {
        // add
        $insert = factory(Group::class)->make()->toArray();

        $userGroupService = new UserGroupService();
        $groupId = $userGroupService->addOrUpdateGroup($insert);

        $groupData = Group::where('group_id', $groupId)->first()->toArray();
        $groupData['created_at'] = date("Y-m-d H:i:s", strtotime($groupData['created_at']));
        $this->assertDatabaseHas('group', $groupData);

        // update
        $groupData['group_name'] = 'test update group';
        $updateReturnGroupId = $userGroupService->addOrUpdateGroup($groupData);

        $updatedGroupData = Group::where('group_id', $updateReturnGroupId)->first()->toArray();

        $updatedGroupData['created_at'] = date("Y-m-d H:i:s", strtotime($updatedGroupData['created_at']));
        $updatedGroupData['updated_at'] = date("Y-m-d H:i:s", strtotime($updatedGroupData['updated_at']));

        $this->assertDatabaseHas('group', $updatedGroupData);
    }

//    /**
//     * user-group-management - 畫面
//     *
//     * @test
//     */
//    public function userGroupManagementIndex()
//    {
//        $user = factory(User::class)->create([
//            'id' => 0,
//        ]);
//        $this->actingAs($user);
//
//        $this->get(route('dashboard.group.management.index'))
//            ->assertStatus(200)
//            ->assertSee('groupManagement');
//    }

    /**
     * get Group & Users
     *
     * @test
     */
    public function getGroupUsers()
    {
        $user = factory(AdminUser::class)->create([
            'id' => 0,
        ]);
        $this->actingAs($user);

        $fakeGroup = factory(Group::class)->create();

        factory(GroupUsers::class)->create([
            'group_id' => $fakeGroup['id'],
            'user_id' => $user['id']
        ]);

        $postData = [
            'groupId' => $fakeGroup['id']
        ];

        $response = $this->call('GET', route('dashboard.group.management.get.member'), $postData);

        $response->assertStatus(200);
        $this->assertEquals('200', $response['code']);
        $this->assertIsArray($response['data']);
        $this->assertEquals($user['id'], $response['data'][0]['user_id']);

    }

    /**
     * remove group member
     *
     * @test
     */
    public function removeGroupMember()
    {
        $user = factory(AdminUser::class)->create([
            'id' => 0,
        ]);
        $this->actingAs($user);

        $fakeGroup = factory(Group::class)->create();

        factory(GroupUsers::class)->create([
            'group_id' => $fakeGroup['id'],
            'user_id' => $user['id']
        ]);

        $postData = [
            'groupId' => $fakeGroup['id'],
            'userId' => $user['id']
        ];

        $response = $this->call('POST', route('dashboard.group.management.remove.member'), $postData);

        $response->assertStatus(200);
        $this->assertEquals('200', $response['code']);
        $this->assertEquals('OK', $response['comment']);
        $this->assertEquals($fakeGroup['id'], $response['data'][0]['group_id']);
    }

    /**
     * add group member
     *
     * @test
     */
    public function addGroupMember()
    {
        $user = factory(AdminUser::class)->create([
            'id' => 0,
        ]);
        $this->actingAs($user);

        $fakeGroup = factory(Group::class)->create();

//        $fakeGroupUsers = factory(GroupUsers::class)->create([
//            'group_id' => $fakeGroup['id'],
//            'user_id' => $user['id']
//        ]);

        $postData = [
            'groupId' => $fakeGroup['id'],
            'user' => $user['name']
        ];

        $response = $this->call('POST', route('dashboard.group.management.add.member'), $postData);

        $response->assertStatus(201);
        $this->assertEquals('201', $response['code']);
        $this->assertEquals('OK', $response['comment']);
        $this->assertEquals($user['id'], $response['data'][0]['user_id']);
    }

    /**
     * update-group-status
     *
     * @test
     */
    public function updateGroupStatus()
    {
        $user = factory(AdminUser::class)->create([
            'id' => 0,
        ]);
        $this->actingAs($user);

        $fakeGroup = factory(Group::class)->create();

        $postData = [
            'group_id' => $fakeGroup['id'],
            'status' => "Y"
        ];

        $response = $this->call('POST', route('dashboard.group.management.update.status'), $postData);

        $response->assertStatus(200);
        $this->assertEquals('200', $response['code']);
        $this->assertEquals('OK', $response['comment']);
        $this->assertEquals($fakeGroup['id'], $response['data']['group_id']);
    }

//    /**
//     * group-purview - index
//     *
//     * @test
//     */
//    public function groupPurviewIndex()
//    {
//        $user = factory(User::class)->create([
//            'id' => 0,
//        ]);
//        $this->actingAs($user);
//
//        $this->call('get', route('dashboard.group.management.purview.index'))
//            ->assertStatus(200)
//            ->assertSee('group_purview_title');
//    }

    /**
     * edit-group-purview
     *
     * @test
     */
    public function editGroupPurview()
    {
        $user = factory(AdminUser::class)->create([
            'id' => 0,
        ]);
        $this->actingAs($user);

        $fakeMenu = factory(AdminUiMenu::class)->create();

        $postData = [
            'group_name' => "Unit test",
            'status' => "Y",
            $fakeMenu['id'] => "on"
        ];

        $response = $this->call('POST', route('dashboard.group.management.purview.edit'), $postData);
        $response->assertStatus(200);
    }

}
