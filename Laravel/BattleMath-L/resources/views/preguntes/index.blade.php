@extends('app')


@section('content')

<div class="contenidor">
    @if (session('success'))
    <h6 class="notification is-success is-light">{{ session('success') }}</h6>
    @endif

    <form class="p-4 mb-4" action="{{ route('view-afegir-pregunta') }}" method="POST">
        @method('GET')
        @csrf
        <button class="button button--icon is-success is-rounded is-responsive"><p>Afegir Pregunta</p></button>
    </form>

    <table class="table is-striped is-hoverable is-fullwidth">
        <tr>
            <th>ID</th>
            <th>Pregunta</th>
            <th>resposta_correcta_id</th>
            <th>dificultat_id</th>
            <th>tema_id</th>
            <th></th>
        </tr>
        @foreach ($preguntes as $pregunta)
        <tr>
            <td>{{ $pregunta->id }}</td>
            <td><a href="{{ route('view-modificar-pregunta', ['id' => $pregunta->id]) }}">{{ $pregunta->pregunta }}</a></td>
            <td>{{ $pregunta->esposta_correcta_id}}</td>
            <td>{{ $pregunta->dificultat_id }}</td>
            <td>{{ $pregunta->tema_id }}</td>
            
        </tr>
        @endforeach
    </table>
</div>

@endsection