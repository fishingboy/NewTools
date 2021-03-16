<?php

namespace WcStudio\AdminUi\Tests\Feature;

use WcStudio\AdminUi\Repositories\GroupUsers;
use WcStudio\AdminUi\Repositories\GroupPurview;
use WcStudio\AdminUi\Repositories\Group;
use WcStudio\AdminUi\Repositories\AdminUser;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use WcStudio\AdminUi\Tests\TestCase;

class MenuTest extends TestCase
{
    use DatabaseTransactions;

    //rollback
//    use WithoutMiddleware;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testMenuGroup()
    {
        $group = factory(Group::class)->create();
        $this->assertEquals('unit_test', $group['group_name']);
    }

    public function testMenuGroupPurview()
    {
        $group = factory(GroupPurview::class)->create();
        $this->assertEquals(2, $group['group_id']);
    }

    public function testMenuGroupUsers()
    {
        $group = factory(GroupUsers::class)->create();
        $this->assertEquals(2, $group['group_id']);
    }

}
