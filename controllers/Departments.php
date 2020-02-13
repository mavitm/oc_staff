<?php namespace Mavitm\Staff\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Departments extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\ReorderController',
        ];
    
    public $listConfig      = 'config_list.yaml';
    public $formConfig      = 'config_form.yaml';
    public $reorderConfig   = 'config_reorder.yaml';

    public $requiredPermissions = [
        'manage_staff' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Mavitm.Staff', 'main-menu-item', 'staff-menu-department');
    }

    public function listExtendQuery($query)
    {
        $query->departments();
    }

    public function reorderExtendQuery($query)
    {
        $query->departments();
    }

}
