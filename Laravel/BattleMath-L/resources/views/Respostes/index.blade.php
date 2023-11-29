@extends('app')

@section('content')

<div class="contenidor">
    @if (session('success'))
    <h6 class="notification is-success is-light">{{ session('success') }}</h6>
    @endif

    <form class="p-4 mb-4" action="{{ route('view-afegir-resposta') }}" method="POST">
        @method('GET')
        @csrf
        <button class="button button--icon is-success is-rounded is-responsive"><p>Afegir Resposta</p></button>
    </form>

    <table class="table is-striped is-hoverable is-fullwidth">
        <tr>
            <th>ID</th>
            <th>Resposta</th>
            <th>dificultat_id</th>
            <th>tema_id</th>
            <th></th>
        </tr>
        @foreach ($respostes as $resposta)
        <tr>
            <td>{{ $resposta->id }}</td>
            <td>{{ $resposta->resposta }}</a></td>
            <td>{{ $resposta->dificultat_id }}</td>
            <td>{{ $resposta->tema_id }}</td>
        </tr>
        @endforeach
    </table>
</div>
@endsection