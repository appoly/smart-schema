<div class="form-check">
    <?php
    $old_value = old($field['name'], isset($config['initial'][$field['name']]) ? $config['initial'][$field['name']] : '');
    ?>
    <input name="{{ $field['name'] }}" type="hidden" value="0">
    <div class="custom-control custom-checkbox">
        <input name="{{ $field['name'] }}" type="{{ $field['type'] }}" class="form-check-input custom-control-input {{ ($errors->has($field['name'])) ? ' is-invalid' : '' }}"
               id="{{ $field['name'] }}" aria-describedby="{{ $field['name'] }}Help" value="1"
                {{ $old_value ? 'checked' : '' }}
                {{ isset($config['readonly']) ? 'disabled' : '' }}>
        <label for="{{ $field['name'] }}" class="custom-control-label form-check-label">{{ $field['label'] }}</label>
    </div>
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