    @foreach($fields as $field)

          @php

        $view = 'smartschema::impl.' . $field['type'];

        if(view()->exists('smartschema::impl.' . $field['type'])) {
            $view = 'smartschema::impl.' . $field['type'];             
        }
        else {

            $view = $field['type']; 
        }

        @endphp

        @include($view,
                [
                'field' => $field,
                'config' => isset($config) ? $config : null
                ])

    @endforeach
