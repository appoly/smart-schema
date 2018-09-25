<input name="{{ $field['name'] }}" type="{{ $field['type'] }}" id="{{ $field['name'] }}"
       aria-describedby="{{ $field['name'] }}Help"
       value="{{ old($field['name'], optional($data)->{$field['name']}) }}">