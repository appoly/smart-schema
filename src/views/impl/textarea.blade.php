<div class="form-group">
    <label for="{{ $field['name'] }}">{{ $field['label'] }}</label>

    <?php
    $old_value = old($field['name'],
        isset($config['initial'][$field['name']]) ? $config['initial'][$field['name']] : '');
    ?>
    <textarea name="{{ $field['name'] }}"
              class="form-control {{ ($errors->has($field['name'])) ? ' is-invalid' : '' }}"
              id="{{ $field['name'] }}"
              aria-describedby="{{ $field['name'] }}Help">{{ $old_value }}</textarea>

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