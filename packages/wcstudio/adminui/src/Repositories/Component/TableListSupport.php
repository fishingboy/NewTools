<?php


namespace WcStudio\AdminUi\Repositories\Component;


interface TableListSupport
{

    /**
     * @param $filterObject
     *
     * @return array
     */
    public function getTableAllData($filterObject);
}
