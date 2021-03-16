<?php


namespace WcStudio\AdminUi\Http\Controllers;


use WcStudio\AdminUi\Http\Controllers\AdminUiIndex;
use WcStudio\AdminUi\Http\Controllers\AdminUiInterface\AdminUiCrud;
use WcStudio\AdminUi\Http\Controllers\Component\AdminMenu;
use WcStudio\AdminUi\Http\Controllers\Component\TableList;

class AdminCrud extends AdminUiIndex implements AdminUiCrud
{

    /**
     * @var string
     */
    public $view_base_path = "adminui";

    /**
     * @var array
     */
    protected $model = [];

    /**
     * @var false|string
     */
    protected $class_name = "";

    /**
     * @var array
     */
    public $assignment = [];

    /**
     * @var array
     */
    public $select_columns = [];

    /**
     * @var array
     */
    public $show_columns = [];

    public function __construct()
    {
        parent::__construct();
        $this->class_name = substr(strrchr(get_class($this), "\\"), 1);

    }

    /**
     * 頁面預設，會包含
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Support\Renderable|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $this->init();

        $this->components = [
            'menulist' => AdminMenu::class,
            'tablelist' => TableList::class,
        ];

        $this->components_parameter['tablelist'] = [
            'headers' => $this->assignment,
            'table_name' => $this->model,
            'select_columns' => $this->select_columns,
            'func' => [],
            'invisible_columns' => [],
            'footer_arr' => [],
            'footer_func' => [],
        ];

        $this->view_path = $this->view_base_path.".".$this->class_name;

        $this->setView($this->view_path);

        $view_data = array_merge(
            $this->handle(),
            [
                'assign' => $this->assignment,
                'show_column' => $this->show_columns
            ]
        );


        return view($this->view_path, $view_data);

    }


    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function showEditForm($id)
    {
        $this->init();

        $data = $this->model->where('id', $id)->get();

        $this->setView($this->view_base_path.".".$this->class_name."_edit");

        $view_data = array_merge(
            $this->handle(),
            [
                'data' => $data
            ]
        );

        return view($this->view_path, $view_data);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function showCreateForm()
    {
        $this->init();

        $this->setView($this->view_base_path.".".$this->class_name."_edit");

        return view($this->view_path, $this->handle());

    }

    /**
     * @param $id
     */
    public function edit($id)
    {
        if( ! $this->validator()){
            return [];
        }

        $this->afterUpdate();
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $this->afterUpdate();
    }

    /**
     *
     */
    public function create()
    {
        if( ! $this->validator()){
            return [];
        }

        $this->afterUpdate();
    }

    /**
     *
     */
    public function afterUpdate()
    {
        return redirect()->back();
    }

    /**
     *
     */
    public function validator()
    {
        return false;

    }


}
