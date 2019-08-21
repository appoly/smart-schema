<form method="POST" action="{{ $action }}" enctype="multipart/form-data">
    @csrf


    @if(isset($config) && isset($config['initial']) && !(isset($config['method']) && $config['method'] != 'PATCH'))
        <input name="_method" type="hidden" value="PATCH">
    @endif

    @foreach($fields as $field)

        {{--@if(isset($config['select_options'][ $field['name'] ]))
            @include('smartschema::impl.' . $field['type'],
                [
                'field' => $field,
                'values' => $config['select_options'][ $field['name'] ],
                'data' => isset($config) && isset($config['initial']) ? $config['initial']: null
                ])
        @else

            @include('smartschema::impl.' . $field['type'],
                [
                'field' => $field,
                'data' => isset($config) && isset($config['initial']) ? $config['initial']: null
                ])

        @endif--}}
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

    <div class="form-group row mb-0">
        <div class="col-md-12 text-right">
            <button type="submit" class="btn btn-primary">
                {{ __('Save Details') }}
            </button>
        </div>
    </div>
</form>