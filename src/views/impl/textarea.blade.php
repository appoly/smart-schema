<div class="form-group">
    @if(isset($field['label']))
        <label for="{{ $field['name'] }}">{{ $field['label'] }}</label>
    @endif

    <?php
    $old_value = old($field['name'],
        isset($config['initial'][$field['name']]) ? $config['initial'][$field['name']] : '');
    ?>
    <textarea name="{{ $field['name'] }}"
              class="form-control {{ ($errors->has($field['name'])) ? ' is-invalid' : '' }}"
              id="{{ $field['name'] }}"
              aria-describedby="{{ $field['name'] }}Help"
              {{ isset($config['readonly']) ? 'readonly' : '' }}
              @if(isset($field['placeholder']))
              placeholder="{{ $field['placeholder'] }}"
              @endif
              >{{ $old_value }}</textarea>

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