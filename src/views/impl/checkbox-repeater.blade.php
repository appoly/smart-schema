<div class="mb-3">
    <label>{{ $field['label'] }}</label>

    @foreach($config['select_options'][ $field['name'] ] as $key => $label)
        <div class="form-check">
            <input name="{{ $field['name'] }}[{{ $key }}]" type="hidden" value="0">
            <div class="custom-control custom-checkbox">
                <input name="{{ $field['name'] }}[{{ $key }}]" type="checkbox"
                       class="form-check-input custom-control-input {{ ($errors->has($field['name']. '_' . $key)) ? ' is-invalid' : '' }}"
                       id="{{ $field['name'] }}_{{ $key }}" value="1"
                        {{ isset($config['initial'][ $field['name'] ]) && $config['initial'][ $field['name']][$key] == 1 ? 'checked' : '' }}>
                <label for="{{ $field['name'] }}_{{ $key }}"
                       class="custom-control-label form-check-label">{{ $label }}</label>
            </div>
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
