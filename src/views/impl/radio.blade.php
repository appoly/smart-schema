<div class="form-group">
    <label for="{{ $field['name'] }}">{{ $field['label'] }}</label>
    <?php
    $old_value = old($field['name'],
        isset($config['initial'][$field['name']]) ? $config['initial'][$field['name']] : '');
    ?>
    @foreach($config['select_options'][$field['name']] ?? $config['select_options'] as $key => $label)
        <div class="custom-control custom-radio">
            <input type="radio" id="{{ $field['name'] }}-{{ $key }}" name="{{ $field['name'] }}" value="{{ $key }}" class="custom-control-input" {{ ($old_value == $key ? 'selected checked' : ' ') }}>
            <label class="custom-control-label" for="{{ $field['name'] }}-{{ $key }}">{{ $label }}</label>
        </div>
    @endforeach

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
