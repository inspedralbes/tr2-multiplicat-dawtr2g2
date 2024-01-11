@extends('app')


@section('content')

<div class="contenidor">
    @if (session('success'))
    <h6 class="notification is-success is-light">{{ session('success') }}</h6>
    @endif

    <form class="btn-container action="{{ route('view-afegir-pregunta') }}" method="POST">
        @method('GET')
        @csrf
        <button class="add-btn">
            <p>Afegir Pregunta</p>
        </button>
    </form>

    <table class="table is-striped is-hoverable is-fullwidth">
        <tr class="list-header">
            <th>ID</th>
            <th>Pregunta</th>
            <th>resposta_correcta_id</th>
            <th>resposta</th>
            <th>Dificultat</th>
            <th>Tema</th>
            <th></th>
        </tr>
        @foreach ($preguntes as $pregunta)
        <tr class="list">
            <td>{{ $pregunta->id }}</td>
            <td><a href="{{ route('view-modificar-pregunta', ['id' => $pregunta->id]) }}">{{ $pregunta->pregunta }}</a>
            </td>
            <td>{{ $pregunta->resposta_correcta_id}}</td>
            <td> {{ $pregunta->resposta }}</td>
            <td>{{$pregunta->dificultat}}</td>
            <td>T.{{ $pregunta->tema_id }}: {{$pregunta->tema}}</td>
            <td>
                <form action="{{ route('eliminar-pregunta', ['id' => $pregunta->id]) }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button class="delete-btn">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>

<style>
    * {
    padding: 0;
    margin: 0;
    box-sizing: border-box;
    text-decoration: none;
    list-style: none;
    background: none;
    border: none;
}

p {
    margin: 0;
}

.contenidor {
    width: 80%;
    margin: 0 auto;
}

.btn-container{
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.add-btn {
    background-color: #4CAF50;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-weight: bold;
    padding: 10px 20px;
    margin: 20px 0;
    transition: all .2s ease-in-out;
}

.add-btn:hover {
    background-color: #3e8a41;
}

.delete-btn{
    background-color: #f44336;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-weight: bold;
    padding: 10px 20px;
    transition: all .2s ease-in-out;
}

.delete-btn:hover {
    background-color: #c53929;
}

td {
    vertical-align: middle;
    text-align: center;
}

.list-header {
    background-color: #4CAF50;
    color: white;
    font-weight: bold;
    padding: 10px 20px; /* Añade un poco de espacio alrededor del texto */
    text-align: center; /* Centra el texto horizontalmente */
    border-bottom: 2px solid #388E3C; /* Añade una línea debajo del encabezado */
    font-size: 1.2em; /* Aumenta el tamaño del texto */
}


</style>

@endsection