<?php namespace Mavitm\Staff\Models;

use Str;
use Model;
/**
 * Model
 */
class Staff extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;

    public $timestamps = false;

    public $table = 'mavitm_staff_records';

    public $rules = [
        'email'    => 'required|between:6,255|email|unique:mavitm_staff_records',
    ];

    protected $jsonable = ['capabilities', 'other_values'];

    public $belongsTo = [
        'department' => ['Mavitm\Staff\Models\Property', 'scope' => 'departments']
    ];

    public $attachOne = [
        'photo' => \System\Models\File::class
    ];

    public function afterCreate()
    {
        if(empty($this->slug))
        {
            $this->slug = Str::slug($this->name." ".$this->surname." ".$this->id);
        }
    }

    public function getDepartmentIdOptions()
    {
        return Property::departments()->get()->lists("name", "id");
    }

    public function setUrl($pageName, $controller, array $urlParams = array())
    {
        $params = [
            "id"   => $this->id,
            "slug" => $this->slug,
        ];

        return $this->url = $controller->pageUrl($pageName, $params);
    }

}
