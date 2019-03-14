<div class="form-group">
    <label for="{{ $field['name'] }}">{{ $field['label'] }}</label>
    <input type="file" accept="{{ isset($field['accept']) ? $field['accept'] : '' }}" name="{{ $field['name'] }}">
</div>
