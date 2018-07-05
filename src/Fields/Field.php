<?php

namespace Appoly\SmartSchema\Fields;


class Field
{
    private $name;
    private $type;
    private $forms;
    private $cast;
    private $unique = false;

    /**
     * @return mixed
     */
    public function getCast()
    {
        return $this->cast;
    }

    /**
     * @param mixed $cast
     */
    public function setCast($cast): void
    {
        $this->cast = $cast;
    }

    private $nullable = false;
    private $fillable = false;

    private $default;
    private $hasDefault = false;
    private $validation = [];

    /**
     * @return bool
     */
    public function isFillable(): bool
    {
        return $this->fillable;
    }

    /**
     * @param bool $fillable
     */
    public function setFillable(bool $fillable): void
    {
        $this->fillable = $fillable;
    }

    public function fillable()
    {
        $this->fillable = true;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getForms()
    {
        return $this->forms;
    }


    public function forms($forms)
    {
        $forms['name'] = $this->name;
        $this->forms = $forms;
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function increments()
    {
        $this->type = 'increments';
        return $this;
    }

    public function text()
    {
        $this->type = 'text';
        return $this;
    }

    public function timestamps()
    {
        $this->type = 'timestamps';
        return $this;
    }

    public function nullable()
    {
        $this->nullable = true;
        return $this;
    }

    public function notNullable()
    {
        $this->nullable = false;
        return $this;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * @param bool $nullable
     */
    public function setNullable(bool $nullable): void
    {
        $this->nullable = $nullable;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }


    public function getDefault()
    {
        return $this->default;
    }

    public function default($default)
    {
        $this->hasDefault = true;
        $this->default = $default;
        return $this;
    }

    public function getValidation()
    {
        return implode('|', $this->validation);
    }

    public function required()
    {
        $this->validation[] = 'required';
        return $this;
    }

    public function unique($table)
    {
        $this->unique = true;
        $this->validation[] = 'unique:' . $table;
        return $this;
    }

    public function email()
    {
        $this->validation[] = 'email';
        return $this;
    }

    public function max($max)
    {
        $this->validation[] = 'max:' . $max;
        return $this;
    }

    public function min($min)
    {
        $this->validation[] = 'min:' . $min;
        return $this;
    }


    //
    // Mutators
    //
    public function integer()
    {
        $this->cast = 'integer';
        return $this;
    }

    public function real()
    {
        $this->cast = 'real';
        return $this;
    }

    public function float()
    {
        $this->cast = 'float';
        return $this;
    }

    public function double()
    {
        $this->cast = 'double';
        return $this;
    }

    public function string()
    {
        $this->cast = 'string';
        return $this;
    }

    public function boolean()
    {
        $this->cast = 'boolean';
        return $this;
    }

    public function object()
    {
        $this->cast = 'object';
        return $this;
    }

    public function array()
    {
        $this->cast = 'array';
        return $this;
    }

    public function collection()
    {
        $this->cast = 'collection';
        return $this;
    }

    public function date()
    {
        $this->cast = 'date';
        return $this;
    }

    public function datetime()
    {
        $this->cast = 'datetime';
        return $this;
    }

    public function timestamp()
    {
        $this->cast = 'timestamp';
        return $this;
    }

    /**
     * @return bool
     */
    public function isUnique(): bool
    {
        return $this->unique;
    }

    /**
     * @return bool
     */
    public function hasDefault(): bool
    {
        return $this->hasDefault;
    }

}