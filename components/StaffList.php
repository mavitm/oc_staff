<?php namespace Mavitm\Staff\Components;

use Mavitm\Staff\Classes\CommonComponent;
use Mavitm\Staff\Models\Property;

class StaffList extends CommonComponent
{
    public $componentType = 'department';

    public function componentDetails()
    {
        return [
            'name'        => 'mavitm.staff::lang.component.staffList',
            'description' => 'No description provided yet...'
        ];
    }
/*
    public function defineProperties()
    {
        return [];
    }
*/

    public function onRun()
    {

        $department = Property::find($this->property("sourceID"));

        if(empty($department->id))
        {
            return $this->controller->run('404');
        }

        $this->page['category'] = $department;

        $department->staff->each(function ($person){
            $person->setUrl($this->property("detailPage"), $this->controller);
        });

        $this->page['persons']  = $department->staff;

        $this->prepareGlobalVariables();
        $this->includeAssets();
    }

}
