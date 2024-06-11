{{ html()->label($label, $name)->class('form-label')->for($name) }}

@php
    $required = isset($required) && $required!='' ? 'required' : false;
@endphp

@if($required)
{!! field_required($required) !!}
@endisset


{{ html()->text($name)->placeholder($placeholder)
       ->class(['form-control', 'is-invalid' => $errors->has($name)])
        ->attributes(["$required"])
        ->addChildren(invalidsFeedbackDIV($name))
}}