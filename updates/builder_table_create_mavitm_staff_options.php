<?php namespace Mavitm\Staff\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMavitmStaffOptions extends Migration
{
    public function up()
    {
        Schema::create('mavitm_staff_options', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->text('property')->nullable();
            $table->integer('sort_order')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('mavitm_staff_options');
    }
}
