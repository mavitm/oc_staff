<?php namespace Mavitm\Staff\Classes;

use Lang;
use Cms\Classes\ComponentBase;
use Mavitm\Staff\Models\Property;
use Cms\Classes\Page;
use Mavitm\Staff\Models\Settings;
use Mavitm\Staff\Models\Staff;

class CommonComponent extends ComponentBase
{
    public $componentType = 'department';

    public $propertyRow;

    public function componentDetails()
    {
        return [
            'name'        => 'mavitm.staff::lang.component.viewList',
        ];
    }

    public function defineProperties()
    {
        $opt = [
            'sourceID' => [
                'title' => 'mavitm.staff::lang.component.content',
                'type' => 'dropdown',
                'options' => $this->getPropOptions()
            ],
            'detailViewType' => [
                'title'       => 'mavitm.staff::lang.component.detailViewType',
                'type'        => 'dropdown',
                'options'     => ['modal' => 'Modal', 'page' => 'Page']
            ],
            'detailPage' => [
                'title' => 'mavitm.staff::lang.component.detailPage',
                'type' => 'dropdown',
                'options' => $this->getPageOptions()
            ],
            'imgWidth' => [
                'title'       => 'mavitm.staff::lang.component.listImgWidth',
                'description' => 'mavitm.staff::lang.component.pixel_value',
                'type'        => 'string',
                'required'    => 1,
                'default'     => 300
            ],
            'imgHeight' => [
                'title'       => 'mavitm.staff::lang.component.listImgHeight',
                'description' => 'mavitm.staff::lang.component.pixel_value',
                'type'        => 'string',
                'required'    => 1,
                'default'     => 300
            ],
        ];

        return $opt;
    }

    public function getPropOptions()
    {
        if($this->componentType == "department")
        {
            return Property::departments()->get()->lists("name", "id");
        }

        return Property::view()->get()->lists("name", "id");
    }

    public function getPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function includeAssets()
    {
        $this->addJs('assets/js/front.js');
        $this->addCss('assets/css/front.css');
    }

    public function prepareGlobalVariables()
    {
        $this->page['photoListW']           = $this->property("imgWidth");
        $this->page['photeListH']           = $this->property("imgHeight");
        $this->page['detailViewType']       = $this->property("detailViewType");
        $this->page['includeStaffModal']    = 0;
        if($this->page['detailViewType'] == "modal")
        {
            $this->page['includeStaffModal'] = 1;
        }


        $socials         = Settings::get("social_links");

        $icons = [];
        if(is_array($socials) && !empty($socials))
        {
            foreach ($socials as $arr)
            {
                $icons[$arr['name']] = $arr['css_class'];
            }
        }

        $settings['social_icons']         = $icons;
        $settings['show_dob']             = Settings::get("show_dob");
        $settings['show_title']           = Settings::get("show_title");
        $settings['show_email']           = Settings::get("show_email");
        $settings['show_country']         = Settings::get("show_country");
        $settings['show_biography']       = Settings::get("show_biography");
        $settings['show_capabilities']    = Settings::get("show_capabilities");

        $this->page['staffSettings']      = $settings;
    }

    public function onViewModalPerson()
    {
        $this->prepareGlobalVariables();

        $person = Staff::find(input('id'));

        $content    = '';
        $title      = e(trans('mavitm.staff::lang.component.personel_information')); //'Personal information';;

        if(!empty($person->id))
        {
            $this->page['person']   = $person;
            $content                = $this->renderPartial('@staff_detail_in_modal');
        }

        return [
            'staffModal' => 1,
            '#staff_modal_content'  => $content,
            '#staff_modal_title'    => $title
        ];
    }

}