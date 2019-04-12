<div class="form-group">
    @if(isset($field['label']))
        <label for="{{ $field['name'] }}">{{ $field['label'] }}</label>
    @endif
    <input type="file" accept="{{ isset($field['accept']) ? $field['accept'] : '' }}" name="{{ $field['name'] }}">
</div>
