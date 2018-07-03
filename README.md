# Laravel: Smartfields & form helper

Aims to create a centralised place for configuring fields.


## Introduction

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

When storing the object in your controller, the validation helper should be called with the object type:
```
public function store(Request $request) {
    SchemaHelper::validate($request, 'sites');
    Site::create($request->all());
}
```

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

## Forms
The form helper will generate (currently Bootstrap 4) forms based on the field data.

In migration, use `->forms([...` to show a field in the auto-generated forms:
```
->forms([
    'type' => 'text',
    'label' => 'Site name'
])
```

To render a form:
```
{!! \Appoly\SmartSchema\SchemaHelper::form('sites', route('sites.store')) !!}
```

