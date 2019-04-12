<div class="form-group">
    @if(isset($field['label']))
        <label for="{{ $field['name'] }}">{{ $field['label'] }}</label>
    @endif

    <select name="{{ $field['name'] }}"
            class="form-control taggable {{ ($errors->has($field['name'])) ? ' is-invalid' : '' }}"
            id="{{ $field['name'] }}" aria-describedby="{{ $field['name'] }}Help">
        @if(isset($config['multiselect_values']))
            @foreach($config['multiselect_values'] as $key => $label)
                <option {{ (old($field['name'], optional($config['initial'])[$field['name']]) == $label ? 'selected' : '') }} value="{{ $label }}">{{ $label }}</option>
            @endforeach
        @endif
    </select>
    <small id="{{ $field['name'] }}HelpTaggable" class="form-text text-muted">Choose from previous values, or type your
        own and then press enter.
    </small>

    @if(isset($field['help']))
        <small id="{{ $field['name'] }}Help" class="form-text text-muted">{{ $field['help'] }}</small>
    @endif

    @if($errors->has($field['name']))
        @foreach($errors->get($field['name']) as $message)
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @endforeach
    @endif
</div>