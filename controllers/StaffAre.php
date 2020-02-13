<?php namespace Mavitm\Staff\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Mavitm\Staff\Models\Settings;

class StaffAre extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\ReorderController'
    ];
    
    public $listConfig      = 'config_list.yaml';
    public $formConfig      = 'config_form.yaml';
    public $reorderConfig   = 'config_reorder.yaml';

    public $bodyClass = 'compact-container';

    public $requiredPermissions = [
        'manage_staff' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Mavitm.Staff', 'main-menu-item', 'staff-menu-staff');
    }

    public function formExtendFields($form)
    {
        $socialLinks = Settings::get("social_links", null);
        if(!empty($socialLinks))
        {
            $socialForm = [];

            foreach ($socialLinks as $platform)
            {
                $socialForm['other_values[links]['.$platform['name'].']'] = [
                    "label" => $platform['name'],
                    "type"  => "text",
                    "tab"   => "mavitm.staff::lang.department.social_links",
                    "span"  => "auto"
                ];
            }

            $form->addTabFields($socialForm);
        }
    }

}
