<?php namespace Mavitm\Staff\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMavitmStaffRecords extends Migration
{
    public function up()
    {
        Schema::create('mavitm_staff_records', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('department_id')->nullable();
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->dateTime('dob')->nullable();
            $table->string('email')->nullable();
            $table->string('title')->nullable();
            $table->text('biography')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->text('capabilities')->nullable();
            $table->string('slug')->nullable();
            $table->integer('sort_order')->nullable();
            $table->text('other_values')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('mavitm_staff_records');
    }
}
