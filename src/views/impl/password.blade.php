<div class="form-group">
    <label for="{{ $field['name'] }}">{{ $field['label'] }}</label>
    <div class="input-group">
        @if(isset($field['prepend']))
            <div class="input-group-prepend">
                <span class="input-group-text">{{ $field['prepend'] }}</span>
            </div>
        @endif
        <input name="{{ $field['name'] }}" type="{{ $field['type'] }}"
               class="form-control {{ ($errors->has($field['name'])) ? ' is-invalid' : '' }}" id="{{ $field['name'] }}"
               @if(isset($field['placeholder']))
               placeholder="{{ $field['placeholder'] }}"
               @endif
               aria-describedby="{{ $field['name'] }}Help"
               value="{{ old($field['name'], optional($data)->{$field['name']}) }}">
        @if(isset($field['append']))
            <div class="input-group-append">
                <span class="input-group-text">{{ $field['append'] }}</span>
            </div>
        @endif
    </div>
    @if(isset($field['help']))
        <small id="{{ $field['name'] }}Help" class="form-text text-muted">{{ $field['help'] }}</small>
    @endif

    @if($errors->has($field['name']))
        @foreach($errors->get($field['name']) as $message)
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @endforeach
    @endif
</div>