<div class="form-group">
    @if(isset($field['label']))
        <label for="{{ $field['name'] }}">{{ $field['label'] }}</label>
    @endif

    <select name="{{ $field['name'] }}"
            class="form-control select2able {{ ($errors->has($field['name'])) ? ' is-invalid' : '' }}"
            id="{{ $field['name'] }}" aria-describedby="{{ $field['name'] }}Help">
        @if(!isset($config['initial']) && !old($field['name']))
            <option selected disabled>
                @if(!isset($field['placeholder']))
                    Select...
                @else
                    {{ $field['placeholder'] }}
                @endif
            </option>
        @endif
        <?php
            $old_value = old($field['name'],
                        isset($config['initial'][$field['name']]) ? $config['initial'][$field['name']] : '');
        ?>
         @foreach($config['select_options'][$field['name']] ?? $config['select_options'] as $key => $label)
            <option {{ ($old_value == $key ? 'selected' : ' ') }} value="{{ $key }}">{{ $label }}</option>
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
