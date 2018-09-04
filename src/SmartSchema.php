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
    }

    public static function initiate($table, $smartModel)
    {
        foreach ($smartModel->getFields() as $field) {

            $new_field = null;
            /*if(!$field->exists) {
                $field->exists = true;*/
            switch ($field->getType()) {
                case 'text':
                    $new_field = $table->text($field->getName());
                    break;
                case 'increments':
                    if (!$field->exists)
                        $new_field = $table->increments($field->getName());
                    break;
                case 'integer':
                    $new_field = $table->integer($field->getName());
                    break;
                case 'timestamps':
                    if (!$field->exists)
                        $new_field = $table->timestamps();
                    break;
                case 'boolean':
                    $new_field = $table->boolean($field->getName());
                    break;
                case 'char':
                    $new_field = $table->char($field->getName());
                    break;
                case 'date':
                    $new_field = $table->date($field->getName());
                    break;
                case 'dateTime':
                    $new_field = $table->dateTime($field->getName());
                    break;
                case 'double':
                    $new_field = $table->double($field->getName());
                    break;
                case 'float':
                    $new_field = $table->float($field->getName());
                    break;
                case 'morphs':
                    $new_field = $table->morphs($field->getName());
                    break;
                case 'rememberToken':
                    if (!$field->exists)
                        $new_field = $table->rememberToken();
                    break;
                case 'string':
                    $new_field = $table->string($field->getName());
                    break;
                case 'time':
                    $new_field = $table->time($field->getName());
                    break;
                case 'timestamp':
                    $new_field = $table->timeStamp($field->getName());
                    break;
                case 'binary':
                    $new_field = $table->binary($field->getName());
                    break;
            }
            /*$smartModel->save();
       }*/


            if ($field->isNullable()) {
                $new_field->nullable();
            }

            if ($field->isUnique() && !$field->exists) {
                $new_field->unique();
            }

            if ($field->isRememberToken() && !$field->exists) {
                $new_field->rememberToken();
            }

            if ($field->hasDefault()) {
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
            }

            $field->exists = true;
        }
    }
}
