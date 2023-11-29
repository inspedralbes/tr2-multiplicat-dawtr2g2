@extends('app')

@section('content')
<div class="contenidor">
    @if (session('success'))
    <h6 class="notification is-success is-light">{{ session('success') }}</h6>
    @endif


    <table class="table is-striped is-hoverable is-fullwidth">
        <tr>
            <th>ID</th>
            <th>Pregunta</th>
            <th>dificultat_id</th>
            <th>tema_id</th>
            <th></th>
        </tr>
        @foreach ($preguntes as $pregunta)
        <tr>
            <td>{{ $pregunta->id }}</td>
            <td>{{ $pregunta->pregunta }}</a></td>
            <td>{{ $pregunta->dificultat_id }}</td>
            <td>{{ $pregunta->tema_id }}</td>
        </tr>
        @endforeach
    </table>
</div>
@endsection