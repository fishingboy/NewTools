<?php


namespace WcStudio\AdminUi\Http\Controllers\Component;


use WcStudio\Tool\Http\Resources\ApiResponse;
use WcStudio\AdminUi\Http\Controllers\AdminUiInterface\AjaxComponent;

/**
 * Class DatePickComonent
 * date 的 Component
 * 設定時間相關設定
 *
 * @package App\Http\Controllers\Dashboard\Component
 */
class DatePick implements AjaxComponent
{
    /**
     * @var string
     */
    public $tablename ='';

    /**
     * @var object
     */
    public $daterange = \stdClass::class;

    /**
     * @var array
     */
    public $pickfield = [];

    /**
     * @var int 0
     */
    public $start = 0;
    /**
     * @var int
     */
    public $end = 0;


    /**
     * 設定該 component 的值
     * @param array $config['start'] date start
     * @param $config['end'] date end
     * @param $config['daterange'] max rage date
     * @param $config['pickfield'] which field to pick (date)
     */
    public function setComponentConfig($config)
    {
        if($config){
            $this->start = $config['start'];
            $this->end = $config['end'];
            $this->daterange = json_decode($config['daterange'], true);
            $this->pickfield = json_decode($config['pickfield']);
        }

    }

    /**
     * 處理 component 的資料
     *
     * @return array db 資料
     */
    public function componentHandle()
    {
        return [
            'start' => $this->start,
            'end' => $this->end,
            'daterange' => $this->daterange,
            'pickfield' => $this->pickfield
        ];
    }

    public function setAjaxModel($model = '')
    {
        $this->model = new $model;
    }

    /**
     * @param  array  $parameter 塞值
     */
    public function setParameter($parameter = [])
    {
        $this->setAjaxModel($parameter['model']);
        $this->ajax_parameter = $parameter;
    }

    public function outputResponse($code = '', $msg = '', $data = [])
    {
        $apiRespone = new ApiResponse();
        return $apiRespone->parseStatus($code, $msg, $data);

    }

    public function doAction()
    {
        $object = $this->model->searchAdv($this->ajax_parameter);

        if ( ! $object) {
            //不存在該筆
            Log::info(' 不存在該筆資料');
            return $this->outputResponse('200', '不存在該筆資料', []);

        }
        return $this->outputResponse(200, 'Success', $object->toArray());
    }
}
