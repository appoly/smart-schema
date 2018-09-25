<?php
$old_value = old($field['name'], isset($config['initial'][$field['name']]) ? $config['initial'][$field['name']] : '');
?>

<input name="{{ $field['name'] }}" type="{{ $field['type'] }}" id="{{ $field['name'] }}"
       aria-describedby="{{ $field['name'] }}Help"
       value="{{ $old_value }}">