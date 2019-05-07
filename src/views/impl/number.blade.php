<?php
$old_value = old($field['name'], isset($config['initial'][$field['name']]) ? $config['initial'][$field['name']] : '');
?>
<div class="form-group">
    @if(isset($field['label']))
      <label for="{{ $field['name'] }}">{{ $field['label'] }}</label>
    @endif
    <div class="input-group">
        @if(isset($field['prepend']))
            <div class="input-group-prepend">
                <span class="input-group-text">{{ $field['prepend'] }}</span>
            </div>
        @endif
        <input name="{{ $field['name'] }}" type="number"
               class="form-control {{ ($errors->has($field['name'])) ? ' is-invalid' : '' }}" id="{{ $field['name'] }}"
               step="{{ isset($field['step']) ? $field['step'] : 'any' }}"
               @if(isset($field['placeholder']))
               placeholder="{{ $field['placeholder'] }}"
               @endif
               aria-describedby="{{ $field['name'] }}Help"
               value="{{ $old_value }}"
               {{ isset($config['readonly']) ? 'readonly' : '' }}>
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