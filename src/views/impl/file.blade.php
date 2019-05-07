<div class="form-group">
    @if(isset($field['label']))
    <label for="{{ $field['name'] }}">{{ $field['label'] }}</label>
    @endif
    <input type="file" accept="{{ isset($field['accept']) ? $field['accept'] : '' }}" name="{{ $field['name'] }}"
        class='w-100 {{ $errors->has('pdf[map_under_18s]') ? "is-invalid" : "" }}'
        {{ isset($config['readonly']) ? 'disabled' : '' }}>

    @if($errors->has($field['name']))
    @foreach($errors->get($field['name']) as $message)
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @endforeach
    @endif
</div>
