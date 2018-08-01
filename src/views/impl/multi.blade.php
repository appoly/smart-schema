<div class="form-group">
    <label for="{{ $field['name'] }}">{{ $field['label'] }}</label>

    <select name="{{ $field['name'] }}[]" multiple="multiple" class="form-control select2able {{ ($errors->has($field['name'])) ? ' is-invalid' : '' }}" id="{{ $field['name'] }}" aria-describedby="{{ $field['name'] }}Help">
        @if(!isset($data) && !old($field['name']))
            <option selected disabled>Select...</option>
        @endif
        @foreach($values as $key => $label)
            <option {{ (old($field['name'], optional($data)->{$field['name']}) == $key ? 'selected' : '') }} value="{{ $key }}">{{ $label }}</option>
        @endforeach
    </select>

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