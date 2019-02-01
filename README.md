# Laravel: Smartfields & form helper

Tired of repeating yourself? This package centralises everything to do with fields.


[Introduction](#introduction)
[Field Types](#field-types)
[Virtual Fields](#virtual-fields)
[Validation Rules](#validation-rules)
[Model Attributes](#model-attributes)
[Forms](#forms)
[Code Generation](#code-generation)


## Quick Usage



## Introduction

Instead of having to create a migration, a request, form views and set up fillable fields, we can instead create a smart migration which handles it all.

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

## Options for fields


### Field Types
Standard field types area available.
```
$table->text("name")
$table->integer("user_id")
$table->float("latitude")
``` 
etc...

#### Virtual Fields
In some cases, we may want fields in a form that don't correspond directy to our database tables.

We can then use:
```
$table->virtual("slot")->forms(...
```

### Validation Rules
These can be chained to a field creation in your migration.

Example:
```
$table->text("email")->required()->email();
```

Available rules:
```
->unique()
->required()
->email()
->max( val )
->min( val )
```

Custom validation rules can be added with:
```
->addRule( rule )
```


When storing the object in your controller, the validation helper should be called with the object type:
```
public function store(Request $request) {
    SchemaHelper::validate($request, 'sites');
    
    // Process the request
    // ...
}
```


### Model attributes
`fillable()`

Casts:
```
->array()
->datetime()

```

Model __must__ have the `SmartField` trait to use `fillable()` or any of the attribute casts.
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

To render a basic form:
```
{!! \Appoly\SmartSchema\SchemaHelper::form('sites', route('sites.store')) !!}
```

Multiple choice form fields such as selects and radio buttons will need a set of options.

In the case of the following field:
```
$table->id("role")
        ->forms([
            'type' => 'select', // or 'type' => 'radio'
            'label' => 'Role'
        ]);
```

Options can be passed like so:
```
{!! \Appoly\SmartSchema\SchemaHelper::form('sites', route('sites.store'), [
    'select_options' => ['User', 'Admin']
]) !!}
```

Or with keys
```
{!! \Appoly\SmartSchema\SchemaHelper::form('sites', route('sites.store'), [
    'select_options' => [
        10001 => 'User', 
        20001 => 'Admin'
    ]
]) !!}
```

## Code Generation
This package includes a console command which will set up a boilerplate controller and view code.

`php artisan crud:generate {resource_name_singular}`

For example:

`php artisan crud:generate client`

