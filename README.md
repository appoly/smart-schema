# Smartfields & forms

Example migration:
```
SmartSchema::create('sites', function ($table) {
    $table->increments("id");

    $table->integer("name");

    $table->text("name")
        ->nullable()
        ->required()
        ->fillable()
        ->max(5)
        ->forms([
            'type' => 'text',
            'label' => 'Site name'
        ]);

    $table->timestamps();
});
```

## Field Types
`$table->text("name")`
`$table->integer("user_id")`
`$table->float("latitude")` etc...

## Validation Rules
`->unique()`
`->required()` etc...

includes `->nullable()` for db schema

## Model attributes
`fillable()`

Casts:
`array()`
`datetime()` etc...


Model must have the `SmartField` trait to use `fillable()` or any of the attribute casts.
```
class User extends Model
{
    use SmartField;
}

```