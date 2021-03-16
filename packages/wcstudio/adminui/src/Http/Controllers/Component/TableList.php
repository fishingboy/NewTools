<?php


namespace WcStudio\AdminUi\Http\Controllers\Component;

use WcStudio\AdminUi\Services\Report\TableService;
use AsusMw\WebsiteConfig\Services\LocalizationI18n;
use NewLog;

/**
 * Class TableListComonent
 * table 的 Component
 * 抓取db data顯示table
 *
 * @package App\Http\Controllers\Dashboard\Component
 */
class TableList
{
    /**
     * @var string
     */
    public $model = '';
    /**
     * @var string
     */
    public $customTableFunction = 'GetTableData';
    /**
     * @var string
     */
    public $dateColumn = '';
    /**
     * @var string
     */
    public $start = '';
    /**
     * @var string
     */
    public $end = '';
    /**
     * @var bool
     */
    public $loadHeader = true;
    /**
     * @var array
     */
    public $filter_columns = [];

    /**
     * @var object
     */
    public $select_columns = \stdClass::class;

    /**
     * @var object
     */
    public $footerArray = \stdClass::class;
    /**
     * @var string
     */
    public $footerFunctinoName = "";

    /**
     * @var
     */
    public $invisible_columns;

    /**
     * @var
     */
    public $params;
    /**
     * @var array
     */
    public $filter_condition = [];


    public $headers = [];

    /**
     * 設定該 component 的值
     *
     * @param $config array $config ['columns'] 要抓取的table欄位 (todo)
     * table 的name
     */
    public function setComponentConfig($config)
    {
        if (empty($config)) {
            return [];
        }
        $this->headers = (empty($config['headers'])) ? [] : $config['headers'];
        $this->select_columns = $config['select_columns'];
        $this->filter_columns = (empty($config['filter_columns'])) ? [] : $config['filter_columns'];

        $this->model = $config['table_name'];

        $this->footerArray = $config['footer_arr'];
        $this->footerFunctinoName = $config['footer_func'];

        $invisible_arr = $this->getInvisibleArray($config['invisible_columns']);
        $this->invisible_columns = $invisible_arr;
    }

    /**
     * @param $config
     *
     * @return array
     */
    public function getInvisibleArray($config)
    {
        $invisible_arr = [];
        foreach ($config as $key => $value) {
            array_push($invisible_arr, $value);
        }
        return $invisible_arr;
    }



    /**
     * @param $key string
     * @param $value string 值
     */
    public function setParamValues($key, $value)
    {
        $this->params[$key] = $value;
    }

    /**
     * 處理 component 的資料
     *
     * @return array db 資料
     */
    public function componentHandle()
    {
        if (empty($this->model)){
            return [
                'data' => [],
                'footerArray' => [],
                'footerFunctinoName' => '',
                'isExportExcel' => false,
                'invisible_columns' => [],
                'headers' => [],
            ];
        }

        $this->combineFilterCondition();

        if ($this->customTableFunction != '' and $this->customTableFunction != 'GetTableData') {
            $cutomName = $this->customTableFunction;

            $collection = $this->$cutomName($this->model, $this->filter_condition);

        } else {

            if (!empty($this->select_columns)){
                $collection = $this->model->get($this->select_columns)->toArray();
            }else{
                $collection = $this->model->get()->toArray();
            }

        }

        return [
            'data' => $collection,
            'footerArray' => $this->footerArray,
            'footerFunctinoName' => $this->footerFunctinoName,
            'invisible_columns' => $this->invisible_columns,
            'headers' => $this->headers,
        ];


    }

    /**
     * 合併查詢條件
     */
    public function combineFilterCondition()
    {

        $condition = [
            'tablename' => $this->model,
            'show_columns' => $this->select_columns,
            'dateColumn' => $this->dateColumn,
            'start' => $this->start,
            'end' => $this->end,
        ];
        if (empty($this->filter_columns)){

            $this->filter_condition = $condition;

        }else{
            $this->filter_condition = array_merge(
                $condition,
                $this->filter_columns
            );

        }

    }

    /**
     * 客製的db query function 需在reportpageconfig 的table中設置
     *
     * @param string $tablename
     * @param array $columns
     *
     * @return array
     */
    public function customGetTable($tablename = '', $columns = [])
    {

        $path =  $tablename;
        $db = new  $path;
        $data = $db::query();

        if($columns == []) {
            $collection = $db::all()->toArray();
        }else {
            $collection = $data->select($columns)->get()->toArray();
        }

        $collection = $db::all()->toArray();

        return $collection;
    }


}
