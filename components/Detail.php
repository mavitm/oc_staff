<?php namespace Mavitm\Staff\Components;

use Mavitm\Staff\Classes\CommonComponent;
use Mavitm\Staff\Models\Staff;

class Detail extends CommonComponent
{
    public function componentDetails()
    {
        return [
            'name'        => 'mavitm.staff::lang.component.detailComponent',
            'description' => 'mavitm.staff::lang.component.detailComponentDescription'
        ];
    }

    public function defineProperties()
    {
        $defOpt = parent::defineProperties();

        unset($defOpt['sourceID']);

        $defOpt['targetPerson'] = [
            'title'       => 'mavitm.staff::lang.component.slugOrId',
            'description' => 'mavitm.staff::lang.component.slugOrIdDescription',
            'type'        => 'string',
            'default'     => '{{ :slug }}',
            'required'    => 1,
        ];

        return $defOpt;
    }

    public function onRun()
    {

        $parameterName = $this->paramName("targetPerson");

        if($parameterName == "id")
        {
            $person = Staff::find($this->property('targetPerson'));
        }
        else
        {
            $person = Staff::where('slug', '=', $this->property('targetPerson'))->take(1)->first();
        }


        $this->prepareGlobalVariables();
        $this->page['person']           = $person;

        $this->includeAssets();
    }

}
