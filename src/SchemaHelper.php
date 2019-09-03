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
        return new self($name);
    }

    /**
     * Render entire basic form for a model.
     *
     * @param $name - Table name
     * @param $action - Action URL
     * @param null $config - initial, flavour, select_options, multiselect_selected_values, readonly, format
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function form($name, $action, $config = null)
    {
        $loaded_fields = self::get($name)->getFields();
        $fields = [];
        foreach ($loaded_fields as $loaded_field) {
            if ($loaded_field->getForms(isset($config['flavour']) ? $config['flavour'] : 'default')) {
                $fields[] = $loaded_field->getForms(isset($config['flavour']) ? $config['flavour'] : 'default');
            }
        }

        return view('smartschema::form', compact('fields', 'action', 'config'));
    }

    /**
     * Render a form element using pre-configured options from migration.
     *
     * @param $table_name - Table name (lowercase plural)
     * @param $field_name - Name of field
     * @param null $config - initial, flavour, select_options, readonly, format
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function renderConfiguredField($table_name, $field_name, $config = null)
    {
        $loaded_fields = self::get($table_name)->getFields();
        $data = $loaded_fields[$field_name]->getForms($flavour ?? 'default');

        if (isset($config['initial'])) {
            $config['initial'] = [
                $field_name => $config['initial'],
            ];
        }

        return self::renderField($data,
            $data['type'],
            [
                'initial' => isset($config['initial']) ? $config['initial'] : null,
                'select_options' => isset($config['select_options']) ? $config['select_options'] : null,
                'readonly' => isset($config['readonly']) ? $config['readonly'] : null,
                'format' => isset($config['format']) ? $config['format'] : null,
            ]);
    }

    /**
     * Render entire basic form for a model.
     *
     * @param $name - Table name
     * @param $group - field group required
     * @param null $config - initial, flavour, select_options, multiselect_selected_values, readonly, format
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function renderConfiguredFieldGroup($name, $group, $config = null)
    {
        $loaded_fields = self::get($name)->getFields();
        $fields = [];
        foreach ($loaded_fields as $loaded_field) {
            if ($loaded_field->getForms(isset($config['flavour']) ? $config['flavour'] : 'default') && $loaded_field->getGroup('2') == $group) {
                $fields[] = $loaded_field->getForms(isset($config['flavour']) ? $config['flavour'] : 'default');
            }
        }

        return view('smartschema::grouped-fields', compact('fields', 'config'));
    }

    /**
     * Render a field with full control.
     *
     * @param $data
     * @param $type
     * @param null $config - initial, select_values
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function renderField($data, $type, $config = null)
    {
        if (! is_array($data)) {
            $data = [
                'name' => $data,
                'label' => ucwords(str_replace('_', ' ', $data)),
            ];
        }
        $data['type'] = $type;
        if (isset($config['select_options'])) {
            return view('smartschema::impl.'.$type,
                [
                    'field' => $data,
                    'values' => $config['select_options'],
                    'data' => isset($config) && isset($config['initial']) ? $config['initial'] : null,
                    'config' => $config,
                ]);
        } else {
            return view('smartschema::impl.'.$type,
                [
                    'field' => $data,
                    'data' => isset($config) && isset($config['initial']) ? $config['initial'] : null,
                    'config' => $config,
                ]);
        }
    }

    public static function setUniqueExceptCurrent($name, $field, $value)
    {
        $loaded_fields = self::get($name)->getFields();
        foreach ($loaded_fields as $loaded_field) {
            if ($loaded_field->getForms() && $loaded_field->getName() == $field) {
                $loaded_field->unique($name.','.$field.','.$value);

                return true;
            }
        }

        return false;
    }

    public static function getValidationRules($name, $config = null)
    {
        $loaded_fields = self::get($name)->getFields();
        //dd($loaded_fields);

        $fields = [];
        foreach ($loaded_fields as $loaded_field) {
            if ($loaded_field->getForms()) {
                $fields[$loaded_field->getName()] = $loaded_field->getValidation();

                if ($config) {
                    //
                    // Stop unique fields blocking request on edit
                    //
                    if (isset($config['unique_except_current'])) {
                        foreach ($config['unique_except_current'] as $col => $value) {
                            if ($col == $loaded_field->getName()) {
                                $fields[$loaded_field->getName()] =
                                    str_replace(
                                        'unique:'.$name,
                                        'unique:'.$name.','.$col.','.$value,
                                        $loaded_field->getValidation()
                                    );
                            }
                        }
                    }
                }
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
        if (! isset($this->fields[$name])) {
            $this->fields[$name] = new Field();
            $this->fields[$name]->setName($name);
        }

        return $this->fields[$name];
    }

    public function renameColumn($from, $to)
    {
        if (isset($this->fields[$from])) {
            /*$this->fields[$to] = $this->fields[$from];
            unset($this->fields[$from]);*/
            $this->fields = $this->replace_key($this->fields, $from, $to);
        } else {
            $field = $this->field($to);
        }
        $this->fields[$to]->renameColumn($from, $to);

        return $this->fields[$to];
    }

    private function replace_key($array, $old_key, $new_key)
    {
        $keys = array_keys($array);
        if (false === $index = array_search($old_key, $keys)) {
            throw new Exception(sprintf('Key "%s" does not exit', $old_key));
        }
        $keys[$index] = $new_key;

        return array_combine($keys, array_values($array));
    }

    public static function validate(Request $request, $name, $config = null)
    {
        return $request->validate(
            self::getValidationRules($name, $config)
        );
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        if (count($this->fields) == 0) {
            $this->load();
        }

        return $this->fields;
    }

    public function dropField($name)
    {
        unset($this->fields[$name]);
    }

    public function timestamps()
    {
        $field = $this->field('timestamps');
        $field->setType('timestamps');
    }

    public function save()
    {
        if (! Schema::hasTable('schema')) {
            Schema::create('schema', function (Blueprint $table) {
                $table->string('name');
                $table->unique('name');
                $table->mediumText('fields');
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

    public function delete($name)
    {
        unset($this->fields[$name]);
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

    public function softDeletes()
    {
        return $this->timestamp('deleted_at');
    }
}
