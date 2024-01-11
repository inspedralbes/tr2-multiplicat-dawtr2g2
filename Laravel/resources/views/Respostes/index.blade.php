@extends('app')

@section('content')

<div class="contenidor">
    @if (session('success'))
    <h6 class="notification is-success is-light">{{ session('success') }}</h6>
    @endif

    <form class="btn-container" action="{{ route('view-afegir-resposta') }}" method="POST">
        @method('GET')
        @csrf
        <button class="add-btn">
            <p>Afegir Resposta</p>
        </button>
    </form>

    <table class="table is-striped is-hoverable is-fullwidth">
        <tr class="list-header">
            <th>ID</th>
            <th>Resposta</th>
            <th>dificultat_id</th>
            <th>tema_id</th>
            <th></th>
        </tr>
        @foreach ($respostes as $resposta)
        <tr>
            <td>{{ $resposta->id }}</td>
            <td><a href="{{ route('view-modificar-resposta', ['id' => $resposta->id]) }}">{{ $resposta->resposta }}</a></td>
            <td>{{ $resposta->dificultat_id }}</td>
            <td>{{ $resposta->tema_id }}</td>
            <td>
                <form action="{{ route('eliminar-resposta', ['id' => $resposta->id]) }}" method="POST">
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