@php
    $required = isset($required) && $required != '' ? 'required' : false;

    $select2 = isset($select2);
    $select2_class_name = $select2ClassName ?? 'select2-' . $name;
    $select2_ajax_url = isset($select2AjaxUrl) ? $select2AjaxUrl : false;

    $options = !empty($data) ? optional($data->user())->pluck('name', 'id')->toArray() : [];
    $selected = !empty($data) ? optional($data->user())->pluck('id')->toArray() : [];

    $oldValue = old($name);

    if ($oldValue && !array_key_exists($oldValue, $options) && isset($select2Model)) {
        $query_data = $select2Model::where('id', $oldValue)->get();
        foreach ($query_data as $row) {
            $options[$row->id] = $row->name.' (Email: '.$row->email.')';

        }
    }
@endphp

{{ html()->label($label, $name)->class('form-label')->for($name) }}

@if($required)
    {!! field_required($required) !!}
@endif

{{ html()->select($name, $options, $selected)
       ->placeholder($placeholder)
       ->class([
            'form-select',
            $select2_class_name => $select2
       ])
       ->attributes([$required])
}}

{!! invalidFeedbackDIV($name) !!}

@if($select2)
@push('after-scripts')
<!-- Select2 Library -->
<x-library.select2 />

<!-- Select2 module {{$name}} Library -->
<script type="module">
    $(document).ready(function() {
        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
            document.querySelector('.select2-container--open .select2-search__field').focus();
        });

        let options = {
            theme: 'bootstrap-5',
            placeholder: '@lang('Select an option')',
            minimumInputLength: 1,
            allowClear: true,
        }

        @if($select2_ajax_url)
        options.ajax = {
            url: '{{ $select2_ajax_url }}',
                dataType: 'json',
                data: function(params) {
                return {
                    q: $.trim(params.term)
                };
            },
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
        @endif


        $('.{{$select2_class_name}}').select2(options);
    });
</script>
@endpush
@endif