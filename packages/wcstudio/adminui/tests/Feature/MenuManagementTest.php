<?php

namespace WcStudio\AdminUi\Tests\Feature;

use WcStudio\AdminUi\Repositories\AdminUiMenu;
use WcStudio\AdminUi\Repositories\AdminUser;
use WcStudio\AdminUi\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MenuManagementTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var array
     */
    private $data = [
        'menu_name' => 'foo_bar',
        'menu_layer' => '0',
        'parent_id' => '0',
        'status' => 'Y',
        'menu_visible' => 'Y',
        'url' => '/unitTest/foo/bar'
    ];

    /**
     * @var array
     */
    private $nullValueData = [
        'menu_name' => '',
        'menu_layer' => '',
        'parent_id' => '',
        'status' => '',
        'menu_visible' => '',
        'url' => ''
    ];

//    /**
//     * Menu管理 - 畫面
//     *
//     * @test
//     */
//    public function menuManagementIndex()
//    {
//        $user = factory(User::class)->create([
//            'id' => 0,
//        ]);
//        $this->actingAs($user);
//        $this->get(route('dashboard.menu.management.index'))
//            ->assertStatus(200)
//            ->assertSee('menuManagement');
//    }

    /**
     * menu管理 - 取得menu item
     *
     * @test
     */
    public function getMenuItem()
    {
        $user = factory(AdminUser::class)->create([
            'id' => 0,
        ]);
        $this->actingAs($user);

        $fakeMenu = factory(AdminUiMenu::class)->create();
        $url = route('dashboard.menu.management.get.item', ['id' => $fakeMenu['id']]);

        $response = $this->call('GET', $url);
        $response->assertStatus(200);
        $this->assertIsArray($response['data']);
        $this->assertEquals(1, count($response['data']));
    }

    /**
     * menu管理 - 新增menu item
     *
     * @test
     */
    public function addMenuItem()
    {
        $user = factory(AdminUser::class)->create([
            'id' => 0,
        ]);
        $this->actingAs($user);

        $response = $this->call('POST', route('dashboard.menu.management.add.item'), $this->data);
        $response->assertRedirect(route('dashboard.menu.management.index'));
        $response->assertSessionHas('message');
        $this->assertDatabaseHas('ecmw.menu', $this->data);

        // post add new 測試空值是否回傳錯誤session
        $this->call('POST', route('dashboard.menu.management.add.item'), $this->nullValueData)
            ->assertSessionHasErrors(array_keys($this->nullValueData));
    }

    /**
     * menu管理 - 更新menu item
     *
     * @test
     */
    public function updateMenuItem()
    {
        $user = factory(AdminUser::class)->create([
            'id' => 0,
        ]);
        $this->actingAs($user);

        $fakeMenu = factory(AdminUiMenu::class)->create();
        $url = route('dashboard.menu.management.update.item', ['id' => $fakeMenu['id']]);

        unset($this->data['menu_layer']);
        unset($this->data['parent_id']);

        $response = $this->call('POST', $url, $this->data);
        $response->assertRedirect(route('dashboard.menu.management.index'));
        $response->assertSessionHas('message');

        unset($this->nullValueData['menu_layer']);
        unset($this->nullValueData['parent_id']);

        // post update 測試空值是否回傳錯誤session
        $this->call('POST', $url, $this->nullValueData)
            ->assertSessionHasErrors(array_keys($this->nullValueData));
    }

    /**
     * menu管理 - 更新menu list
     *
     * @test
     */
    public function updateMenuList()
    {
        $user = factory(AdminUser::class)->create([
            'id' => 0,
        ]);
        $this->actingAs($user);

        $fakeMenu = array();
        for ($i = 0; $i < 3; $i++) {
            array_push($fakeMenu, factory(AdminUiMenu::class)->create([
                'menu_layer' => $i,
                'parent_id' => $fakeMenu[$i]['id'] ?? 0
            ]));
        }

        $postData = [
            'menuList' => [
                [
                    'id' => $fakeMenu[0]['id'],
                    'children' => [
                        [
                            'id' => $fakeMenu[1]['id'],
                            'children' => []
                        ],
                        [
                            'id' => $fakeMenu[2]['id'],
                            'children' => []
                        ]
                    ]
                ]
            ]
        ];

        $url = route('dashboard.menu.management.update.list');

        $response = $this->call('POST', $url, $postData);

        $this->assertEquals('200', $response['status']);

        // 如果menu有儲存成功, 異動的資料會更新新的updated_user, 檢查此欄位是否有更新
        $this->assertDatabaseHas('ecmw.menu', [
            'updated_user' => $user['name']
        ]);

        // 嘗試失敗, 判斷只要不是200 就可以
        $errorResponse = $this->call('POST', $url, []);
        $this->assertNotEquals('200', $errorResponse['status']);
    }

}

