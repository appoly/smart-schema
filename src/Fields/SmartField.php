<?php

namespace Appoly\SmartSchema\Fields;

use Appoly\SmartSchema\SchemaHelper;

trait SmartField
{
    public function __construct(array $attributes = [])
    {
        $t = $this->getTable() /*str_plural(strtolower((new \ReflectionClass($this))->getShortName()))*/;
        $this->fillable = SchemaHelper::getFillables($t);
        $this->casts = SchemaHelper::getCasts($t);

        parent::__construct($attributes);
    }
}