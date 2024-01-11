@extends('app')

@section('content')

<div class="contenidor">
    @if (session('success'))
    <h6 class="notification is-success is-light">{{ session('success') }}</h6>
    @endif

    <form class="p-4 mb-4" action="{{ route('view-afegir-resposta') }}" method="POST">
        @method('GET')
        @csrf
        <button class="button button--icon is-success is-rounded is-responsive">
            <p>Afegir Resposta</p>
        </button>
    </form>

    <table class="table is-striped is-hoverable is-fullwidth">
        <tr>
            <th>ID</th>
            <th>Resposta</th>
            <th>Dificultat</th>
            <th>Tematica</th>
            <th></th>
        </tr>
        @foreach ($respostes as $resposta)
        <tr>
            <td>{{ $resposta->id }}</td>
            <td><a href="{{ route('view-modificar-resposta', ['id' => $resposta->id]) }}">{{ $resposta->resposta }}</a></td>
            <td>{{ $resposta->dificultat}}</td>
            <td>T. {{ $resposta->tema_id }}. : {{$resposta->tema}}</td>
            <td>
                <form action="{{ route('eliminar-resposta', ['id' => $resposta->id]) }}" method="POST">
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