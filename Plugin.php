<?php namespace Mavitm\Staff;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            "Mavitm\Staff\Components\ViewList"  => "viewlist",
            "Mavitm\Staff\Components\Detail"    => "staffDetail",
            "Mavitm\Staff\Components\StaffList"    => "staffList",
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'mavitm.staff::lang.plugin.name',
                'description' => '',
                'category'    => 'Mavitm',
                'icon'        => 'icon-cog',
                'class'       => 'Mavitm\Staff\Models\Settings',
                'order'       => 500,
                'permissions' => ['mavitm.staff.manage_staff']
            ]
        ];
    }
}
