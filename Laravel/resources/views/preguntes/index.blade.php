@extends('app')


@section('content')

<div class="contenidor">
    @if (session('success'))
    <h6 class="notification is-success is-light">{{ session('success') }}</h6>
    @endif

    <form class="p-4 mb-4" action="{{ route('view-afegir-pregunta') }}" method="POST">
        @method('GET')
        @csrf
        <button class="button button--icon is-success is-rounded is-responsive">
            <p>Afegir Pregunta</p>
        </button>
    </form>

    <table class="table is-striped is-hoverable is-fullwidth">
        <tr>
            <th>ID</th>
            <th>Pregunta</th>
            <th>resposta_correcta_id</th>
            <th>resposta</th>
            <th>Dificultat</th>
            <th>Tema</th>
            <th></th>
        </tr>
        @foreach ($preguntes as $pregunta)
        <tr>
            <td>{{ $pregunta->id }}</td>
            <td><a href="{{ route('view-modificar-pregunta', ['id' => $pregunta->id]) }}">{{ $pregunta->pregunta }}</a></td>
            <td>{{ $pregunta->resposta_correcta_id}}</td>
            <td> {{ $pregunta->resposta }}</td>
            <td>{{$pregunta->dificultat}}</td>
            <td>T.{{ $pregunta->tema_id }}: {{$pregunta->tema}}</td>
            <td>
                <form action="{{ route('eliminar-pregunta', ['id' => $pregunta->id]) }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button class="button is-danger is-small is-centered eliminar">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>

<style>
    .notification.is-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
        padding: .75rem 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: .25rem;
    }
</style>

@endsection