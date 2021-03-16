<?php


namespace WcStudio\AdminUi\Repositories;


use WcStudio\AdminUi\Repositories\Component\TableListSupport;
use Illuminate\Database\Eloquent\Model;

class ReportSample extends Model implements TableListSupport
{
    protected $table = 'adminui_page';
    protected $primaryKey = 'page_id';
    protected $guarded = [];

    /**
     * @param $filterObject
     *
     * @return array
     */
    public function getTableAllData($filterObject)
    {
        $dateColumn = $filterObject['dateColumn'];
        $start = $filterObject['start'];
        $end = $filterObject['end'];

        return self::all()->toArray();
    }
}
