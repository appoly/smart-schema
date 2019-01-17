<?php
/**
 * User: johnwedgbury
 * Date: 03/07/2018
 * Time: 13:33
 */

namespace Appoly\SmartSchema;

use App\User;
use Illuminate\Support\Facades\Schema;

class SmartSchema
{
    public static function create($table, $callback)
    {
        $smartModel = new SchemaHelper($table);
        $callback($smartModel);
        $smartModel->save();

        Schema::create($table, function ($table) use ($smartModel) {
            SmartSchema::initiate($table, $smartModel);
        });

        $smartModel->save();
    }

    public static function table($table, $callback)
    {
        $smartModel = new SchemaHelper($table);
        $smartModel->load();
        $callback($smartModel);
        $smartModel->save();

        Schema::table($table, function ($table) use ($smartModel) {
            SmartSchema::initiate($table, $smartModel);
        });

        $smartModel->save();
    }

    public static function initiate($table, SchemaHelper $smartModel)
    {
        foreach ($smartModel->getFields() as $field) {

            $new_field = SmartSchema::databaseField($field, $table);

            if ($field->shouldBeDeleted()) {
                $table->dropColumn($field->getName());
                $new_field->delete();
                $smartModel->save();
                continue;
            }

            if ($field->isNullable() && !$new_field) {
                $new_field = SmartSchema::databaseField($field, $table, true);
                if($new_field)
                    $new_field->nullable()->change();
            } elseif ($field->isNullable()) {
                $new_field->nullable();
            }

            if ($field->isUnique() && !$new_field) {
                $new_field = SmartSchema::databaseField($field, $table, true);
                if($new_field)
                    $new_field->unique();
            } elseif ($field->isUnique() && !$field->exists) {
                $new_field->unique();
            }

            if ($field->isRememberToken() && !$field->exists) {
                $new_field->rememberToken();
            }

            if ($field->hasDefault() && !$new_field) {
                $new_field = SmartSchema::databaseField($field, $table, true);
                if($new_field)
                    $new_field->default($field->getDefault())->change();
            } elseif ($field->hasDefault()) {
                $new_field->default($field->getDefault());
            }


            if ($field->exists && $new_field) {
                $new_field->change();
                $field->exists = true;
                $smartModel->save();
            }


            if ($field->getRenameFrom()) {
                $smartModel->renameColumn($field->getRenameFrom(), $field->getRenameTo());
                $table->renameColumn($field->getRenameFrom(), $field->getRenameTo());
                $field->setName($field->getRenameTo());
                $field->clearRenames();
                $smartModel->save();
            }

            $field->exists = true;
        }
    }

    private static function databaseField($field, $table, $force = false) {
        $new_field = null;
        switch ($field->getType()) {
            case 'text':
                if (!$field->exists || $force)
                    $new_field = $table->text($field->getName());
                break;
            case 'increments':
                if (!$field->exists || $force)
                    $new_field = $table->increments($field->getName());
                break;
            case 'integer':
                if (!$field->exists || $force)
                    $new_field = $table->integer($field->getName());
                break;
            case 'timestamps':
                if (!$field->exists)
                    $new_field = $table->timestamps();
                break;
            case 'boolean':
                if (!$field->exists || $force)
                    $new_field = $table->boolean($field->getName());
                break;
            case 'char':
                if (!$field->exists || $force)
                    $new_field = $table->char($field->getName());
                break;
            case 'date':
                if (!$field->exists || $force)
                    $new_field = $table->date($field->getName());
                break;
            case 'dateTime':
                if (!$field->exists || $force)
                    $new_field = $table->dateTime($field->getName());
                break;
            case 'double':
                if (!$field->exists || $force)
                    $new_field = $table->double($field->getName());
                break;
            case 'float':
                if (!$field->exists || $force)
                    $new_field = $table->float($field->getName());
                break;
            case 'morphs':
                if (!$field->exists || $force)
                    $new_field = $table->morphs($field->getName());
                break;
            case 'rememberToken':
                if (!$field->exists)
                    $new_field = $table->rememberToken();
                break;
            case 'string':
                if (!$field->exists || $force)
                    $new_field = $table->string($field->getName());
                break;
            case 'time':
                if (!$field->exists || $force)
                    $new_field = $table->time($field->getName());
                break;
            case 'timestamp':
                if (!$field->exists)
                    $new_field = $table->timeStamp($field->getName());
                break;
            case 'binary':
                if (!$field->exists || $force)
                    $new_field = $table->binary($field->getName());
                break;
        }

        return $new_field;
    }
}
