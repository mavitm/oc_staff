<?php namespace Mavitm\Staff\Controllers;

use Form;
use Flash;
use Backend\Classes\Controller;
use BackendMenu;
use Mavitm\Staff\Models\Property;
use Mavitm\Staff\Models\Staff;
use October\Rain\Exception\ApplicationException;

class Views extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'manage_staff' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Mavitm.Staff', 'main-menu-item', 'staf-menu-views');

        $this->addJs("/plugins/mavitm/staff/assets/js/view.js");
        $this->addCss("/plugins/mavitm/staff/assets/css/view.css");

        $this->addJs("/plugins/mavitm/staff/assets/vendor/jquery-ui/jquery-ui.min.js");
        $this->addCss("/plugins/mavitm/staff/assets/vendor/jquery-ui/jquery-ui.min.css");
    }

    public function listExtendQuery($query)
    {
        $query->view();
    }

    public function reorderExtendQuery($query)
    {
        $query->view();
    }

    public function create()
    {
        $this->prepreDepartmentVars();
        $this->asExtension('FormController')->create();
    }

    public function update($recorID = 0)
    {
        $this->prepreDepartmentVars();
        $this->updateVaribale(Property::find($recorID));
        $this->asExtension('FormController')->update($recorID);
    }

    public function prepreDepartmentVars($currencyDepartment = null)
    {
        $deparments = Property::departments()->get();
        $deparmentsLists = $deparments->lists("name", "id");

        $selected = is_numeric($currencyDepartment) ? $currencyDepartment : (is_object($currencyDepartment) ? $currencyDepartment->id : 0);

        $departmentSelect = Form::select('departments', array_merge([0=>'--'],$deparmentsLists), $selected,
            [
                'class' => 'form-control',
                'id' => 'departments',
                'data-request' => 'onStaffList'
            ]
        );

        $this->vars['departmentSelect'] = $departmentSelect;
        $this->vars['departments']      = $deparments;
        $this->vars['deparmentsLists']  = $deparmentsLists;
    }

    public function updateVaribale($record)
    {
        if(empty($record->property))
        {
            $this->vars['staffForm'] = "";
            return "";
        }

        $formData = "";

        foreach ($record->property as $row)
        {
            $formData .= $this->onAddRow(null, $row['col'], $row['name'], $row['persons'])['data'];
        }
        $this->vars['staffForm'] = $formData;
        return $formData;
    }

    function onAddRow($id = null, $col = null, $name = null, $persons = null)
    {
        if(!$col)
        {
            $col = input("col", 3);
        }

        if(!$name)
        {
            $name = input('name', false);
        }

        return [
            'data' => $this->makePartial('partials/row', ['col' => $col, 'name' => $name, 'persons' => $persons])
        ];
    }

    function onAddCol($id = null, $col = null, $colSize = null, $persons = null)
    {
        if(!$col)
        {
            $col        = input("col", 3);
        }
        if(!$colSize)
        {
            $colSize    = abs(12 / $col);
        }
        if(!$persons)
        {
            $persons    = input('persons', array_fill(0, $col,0));
        }

        if(!is_array($persons))
        {
            $persons = array_fill(0, $col,0);
        }

        if(count($persons) < $col)
        {
            $persons = array_pad($persons, $col, 0);
        }

        $data = '';
        foreach ($persons as $ID)
        {
            if($ID < 1)
            {
                $person = "";
                $data .= $this->makePartial("partials/col", ['person' => $person, 'colSize' => $colSize]);
            }
            else
            {
                $person = $this->makePartial('partials/staff_item', ['person' => Staff::find($ID)]);
                $data .= $this->makePartial("partials/col", ['person' => $person, 'colSize' => $colSize]);
            }
        }

        return [
            'data' => $data.'<div class="clearfix"></div>'
        ];
    }

    public function onStaffList()
    {
        $department             = input('departments', 0);
        $this->vars['persons']  = Staff::where("department_id", "=", $department)->get();

        $code = $this->makePartial("partials/stafflist");

        return [
            "#staff-list" => $code
        ];

    }

    public function onViewSave()
    {
        if(input('id', 0)){
            $model = Property::find(input('id'));
            $context = "update";
        }
        else
        {
            $model = new Property();
            $context = "create";
        }

        $input = input();

        $rows = $input['stafrow'];
        $data = [];
        foreach ($rows as $i => $val)
        {
            $data[$i] = [
                "name" => $input['row-name'][$i],
                "col" => $input['row-col-count'][$i],
                "persons" => array_map('trim', explode(',', $input['row-col-persons'][$i])),
            ];
        }

        $model->name = $input['Property']['name'];
        $model->type = "view";
        $model->property = $data;

        $model->save();
        Flash::success(e(trans('mavitm.staff::lang.form.saved_success')));

        if ($redirect = $this->makeRedirect($context, $model)) {
            return $redirect;
        }
    }

}
