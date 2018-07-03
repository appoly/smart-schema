<form method="POST" action="{{ $action }}">
    @csrf

    @foreach($fields as $field)

        @if($field['type'] == 'select' || $field['type'] == 'multiselect' || $field['type'] == 'taggable')
            @include('smartschema::impl.' . $field['type'],
                [
                'field' => $field,
                'values' => $form_values[ $field['name'] ],
                'data' => isset($preloaded) ? $preloaded: null
                ])
        @else

            @include('smartschema::impl.' . $field['type'],
                [
                'field' => $field,
                'data' => isset($preloaded) ? $preloaded: null
                ])

        @endif
    @endforeach

    <div class="form-group row mb-0">
        <div class="col-md-12 text-right">
            <button type="submit" class="btn btn-primary">
                {{ __('Save Details') }}
            </button>
        </div>
    </div>
</form>