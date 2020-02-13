<?php
/**
 * Created by PhpStorm.
 * User: Ayhan
 * Date: 30.06.2019
 * Time: 20:57
 */

namespace Mavitm\Staff\Models;
use Model;

class Settings extends Model
{
    public $implement       = ['System.Behaviors.SettingsModel'];
    public $settingsCode    = 'mavitm.staff.settings';
    public $settingsFields  = 'fields.yaml';
}