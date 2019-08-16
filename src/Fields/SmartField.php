<?php

namespace Appoly\SmartSchema\Fields;

use Appoly\SmartSchema\SchemaHelper;
use Appoly\SmartSchema\SmartSchema;

trait SmartField
{
    public function __construct(array $attributes = [])
    {

        $t = $this->getTable();

        if (!array_key_exists($t, SmartSchema::$table_data)) {

            $this->fillable = SchemaHelper::getFillables($t);
            $this->casts = SchemaHelper::getCasts($t);

            SmartSchema::$table_data[$t] = [
                'fillable' => $this->fillable,
                'casts' => $this->casts
            ];
        } else {
            $this->fillable = SmartSchema::$table_data[$t]['fillable'];
            $this->casts = SmartSchema::$table_data[$t]['casts'];
        }


        parent::__construct($attributes);
    }
}
