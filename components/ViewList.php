<?php namespace Mavitm\Staff\Components;

use Mavitm\Staff\Models\Staff;
use Response;
use Mavitm\Staff\Classes\CommonComponent;
use Mavitm\Staff\Models\Property;

class ViewList extends CommonComponent
{
    public $componentType = 'view';

    public function componentDetails()
    {
        return [
            'name'        => 'mavitm.staff::lang.component.viewList',
            'description' => 'mavitm.staff::lang.component.viewListDescription'
        ];
    }

//    public function defineProperties()
//    {
//        $defOpt = parent::defineProperties();
//        return $defOpt;
//    }


    public function onRun()
    {

        $viewDataRow = Property::find($this->property("sourceID"));

        if(empty($viewDataRow->id) || empty($viewDataRow->property))
        {
            return $this->controller->run('404');
        }

        $allPerson = [];
        foreach ($viewDataRow->property as $i=>$opt)
        {
            $allPerson = array_merge($allPerson, $opt['persons']);
        }

        $allPerson = array_unique($allPerson);

        $personsData = Staff::whereIn("id", $allPerson)->get();

        $persons = [];

        foreach ($personsData as $person)
        {
            $person->setUrl($this->property("detailPage"), $this->controller);
            $persons[$person->id] = $person;
        }

        $rows = [];

        foreach ($viewDataRow->property as $i=>$opt)
        {
            $cols = [];
            foreach ($opt['persons'] as $ID)
            {
                $cols[] = [
                    'class' => 12 / $opt['col'],
                    'person' => $persons[$ID]
                ];
            }
            $opt['cols'] = $cols;
            $rows[] = $opt;
        }


        $this->prepareGlobalVariables();
        $this->page['properyRow']       = $viewDataRow;
        $this->page['persons']          = $persons;
        $this->page['rows']             = $rows;

        $this->includeAssets();
    }
}
