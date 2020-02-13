<?php namespace Mavitm\Staff\Models;

use Model;

/**
 * Model
 */
class Property extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;

    public $timestamps = false;

    public $table = 'mavitm_staff_options';

    public $rules = [];

    protected $jsonable = ['property'];

    public static $typeValues = [
        "department" => "Department",
        "view"       => "Wiev"
    ];

    public $hasMany = [
        "staff" => ["Mavitm\Staff\Models\Staff", "key" => "department_id", "otherKey" => "id"]
    ];

    public $attachOne = [
        'cover' => \System\Models\File::class
    ];

    public function scopeDepartments($query)
    {
        return $query->where("type", "=", 'department');
    }

    public function scopeView($query)
    {
        return $query->where("type", "=", "view");
    }


}