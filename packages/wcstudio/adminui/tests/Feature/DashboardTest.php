<?php

namespace WcStudio\AdminUi\Tests\Feature;

use WcStudio\AdminUi\Repositories\Group;
use WcStudio\AdminUi\Repositories\GroupPurview;
use WcStudio\AdminUi\Repositories\GroupUsers;
use WcStudio\AdminUi\Repositories\AdminUiMenu;
use WcStudio\AdminUi\Repositories\Report\AdminUiMain;
use WcStudio\AdminUi\Repositories\Report\AdminUiPageConfig;
use WcStudio\AdminUi\Repositories\AdminUser;
use WcStudio\AdminUi\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DashboardTest extends TestCase
{
    use DatabaseTransactions;

    //protected $connectionsToTransact = ['mysql','reportsql'];

    protected $baseUrl;

    /**
     * Admin index - 畫面
     * 沒權限時到首頁登入
     * @test
     */
    public function dashBoardLogin()
    {

        $result = $this->get('/dashboard')
            ->assertRedirect('/dashboard/login');
    }

    /**
     * 需登入
     * @test
     */
    public function dashboardUser()
    {
        $user = factory(AdminUser::class)->create();

        factory(GroupUsers::class)->create([
            'user_id' => $user['id'],
        ]);

        $this->actingAs($user);
        $this->get('/dashboard/')
            ->assertSuccessful();

    }


    /**
     * report controller
     * 進到一頁是 reportsample 的列表頁
     * @test
     */
    public function reportPage()
    {

        $table_class = 'WcStudio\AdminUi\Entities\ReportSample';
        $url = '/dashboard/report-eu-logistic-sample';
        $name = 'eu-logistic-sample';
        $menu_name = "menu_logistic_sample";

        $user = factory(AdminUser::class)->create([
            'id' => 0,
        ]);

        $group = factory(Group::class)->create();

        $group_user = factory(GroupUsers::class)->create([
            'user_id' => $user['id'],
            'group_id' => $group['id'],
        ]);

        $menu = factory(AdminUiMenu::class)->create([
            'menu_name' => 'menu_root',
            'url' => '/',
            'menu_layer' => 0,
            'parent_id' => 0,
        ]);


        $menu = factory(AdminUiMenu::class)->create([
            'menu_name' => $menu_name,
            'url' => $url,
            'menu_layer' => 1,
            'parent_id' => 0,
        ]);

        //已建立 menu，但還沒權限只能進到 dashboard
        $this->actingAs($user)
            ->get($url)
            ->assertRedirect('/dashboard');


        factory(GroupPurview::class)->create([
            'menu_id' => 0,
            'group_id' => $group['id'],
        ]);

        factory(GroupPurview::class)->create([
            'menu_id' => $menu['id'],
            'group_id' => $group['id'],
        ]);

        $report = factory(AdminUiMain::class)->create([
            'table_class' => $table_class,
        ]);

        factory(AdminUiPageConfig::class)->create([
            'report_id' => $report['report_id'],
            'page_name' => $name,
        ]);


        //有權限登入
        //看得到 user list
        $response = $this->actingAs($user)->get($url);
        $response->assertStatus(200);

        //assert menu 要有自已那筆
        $this->assertStringContainsString('<li class="c-sidebar-nav-title" href=/dashboard/report-eu-logistic-sample>menu_logistic_sample</li>',
            $response->content());
        //header 表格
        $this->assertStringContainsString(' <table id="mytable" class="table table-striped">', $response->content());

        //傳入參數
        $ajax_response = $this->actingAs($user)->get($url.'/getTable/create_date/0/10');
        $ajax_response->assertStatus(200);
        $ajax_response->assertHeader('Content-Type', 'application/json');
        $ajax_response->assertJsonStructure([
            'data',
            'footerArray',
            'footerFunctinoName',
            'isExportExcel',
            'invisible_columns',
            'headers',
        ]);

    }

    /**
     * langController
     * 不卡登入
     * @test
     */
    public function langJs()
    {
        $result = $this->get('/dashboard/outlang');
        $result->assertHeader('Content-Type', 'text/javascript; charset=UTF-8')
            ->assertSuccessful();
    }

    /**
     * 需登入
     * @test
     */
    public function setLang()
    {

        //未登入
        $response = $this->get('/dashboard/config-locale/en');
        $response->assertRedirect('/dashboard/login');


        $user = factory(AdminUser::class)->create();

        factory(GroupUsers::class)->create([
            'user_id' => $user['id'],
        ]);

        //登入
        $this->actingAs($user);
        $this->get('/dashboard');
        $response = $this->get('/dashboard/config-locale/en');
        $response->assertRedirect('/dashboard');
    }


}
