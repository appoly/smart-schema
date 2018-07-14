<?php

namespace Appoly\SmartSchema;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Appoly\SmartSchema\Fields\Field;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class SchemaHelper
{

    private $fields = [];
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public static function get($name)
    {
        return new SchemaHelper($name);
    }

    public static function form($name, $action, $preloaded = null, $form_values = null)
    {
        $loaded_fields = self::get($name)->getFields();
        $fields = [];
        foreach ($loaded_fields as $loaded_field) {
            if ($loaded_field->getForms()) {
                $fields[] = $loaded_field->getForms();
            }
        }
        return view('smartschema::form', compact('fields', 'action', 'preloaded', 'form_values'));
    }

    public static function getValidationRules($name)
    {
        $loaded_fields = self::get($name)->getFields();
        $fields = [];
        foreach ($loaded_fields as $loaded_field) {
            if ($loaded_field->getForms()) {
                $fields[$loaded_field->getName()] = $loaded_field->getValidation();
            }
        }
        return $fields;
    }

    public static function getFillables($name)
    {
        $loaded_fields = self::get($name)->getFields();
        $fields = [];
        foreach ($loaded_fields as $loaded_field) {
            if ($loaded_field->isFillable()) {
                $fields[] = $loaded_field->getName();
            }
        }
        return array_values($fields);
    }



    public static function getCasts($name)
    {
        $loaded_fields = self::get($name)->getFields();
        $fields = [];
        foreach ($loaded_fields as $loaded_field) {
            if ($loaded_field->getCast()) {
                $fields[$loaded_field->getName()] = $loaded_field->getCast();
            }
        }
        return $fields;
    }

    public function field($name)
    {
        if (!isset($this->fields[$name])) {
            $this->fields[$name] = new Field();
            $this->fields[$name]->setName($name);
        }
        return $this->fields[$name];
    }

    public static function validate(Request $request, $name)
    {
        return $request->validate(
            self::getValidationRules($name)
        );
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        if (sizeof($this->fields) == 0) {
            $this->load();
        }
        return $this->fields;
    }

    public function timestamps()
    {
        $field = $this->field('timestamps');
        $field->setType('timestamps');
    }

    public function save()
    {
        if (!Schema::hasTable('schema')) {
            Schema::create('schema', function (Blueprint $table) {
                $table->string('name');
                $table->unique('name');
                $table->text('fields');
                $table->timestamps();
            });
        }
        //dd(serialize($this->fields));
        DB::table('schema')->where(
            ['name' => $this->name]
        )->delete();
        DB::table('schema')->insert(
            ['name' => $this->name, 'fields' => serialize($this->fields)]
        );
    }

    public function load()
    {
        if (Schema::hasTable('schema')) {
            $row = DB::table('schema')->where('name', $this->name)->first();
            $this->fields = unserialize($row->fields);
        }
    }


    //
    // Quick field generators
    //
    public function boolean($name)
    {
        $field = $this->field($name);
        $field->setType('boolean');
        return $field;
    }

    public function increments($name)
    {
        $field = $this->field($name);
        $field->setType('increments');
        return $field;
    }

    public function char($name)
    {
        $field = $this->field($name);
        $field->setType('char');
        return $field;
    }

    public function date($name)
    {
        $field = $this->field($name);
        $field->setType('date');
        return $field;
    }

    public function dateTime($name)
    {
        $field = $this->field($name);
        $field->setType('dateTime');
        return $field;
    }

    public function double($name)
    {
        $field = $this->field($name);
        $field->setType('double');
        return $field;
    }

    public function float($name)
    {
        $field = $this->field($name);
        $field->setType('float');
        return $field;
    }

    public function integer($name)
    {
        $field = $this->field($name);
        $field->setType('integer');
        return $field;
    }

    public function morphs($name)
    {
        $field = $this->field($name);
        $field->setType('morphs');
        return $field;
    }

    public function rememberToken()
    {
        $field = $this->field('rememberToken');
        $field->setType('rememberToken');
        return $field;
    }

    public function string($name)
    {
        $field = $this->field($name);
        $field->setType('string');
        return $field;
    }

    public function text($name)
    {
        $field = $this->field($name);
        $field->setType('text');
        return $field;
    }

    public function time($name)
    {
        $field = $this->field($name);
        $field->setType('time');
        return $field;
    }

    public function timestamp($name)
    {
        $field = $this->field($name);
        $field->setType('timestamp');
        return $field;
    }

    public function binary($name)
    {
        $field = $this->field($name);
        $field->setType('binary');
        return $field;
    }

    public function virtual($name)
    {
        $field = $this->field($name);
        $field->setType('virtual');
        return $field;
    }

}