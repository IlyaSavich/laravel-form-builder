{!! $input->generateBefore() !!}

{!! $input->label !!}
{!! $input->input() !!}
@if ($errors->has($input->name))
    <span>{{ $errors->first($input->name) }}</span>
@endif

{!! $input->generateAfter() !!}