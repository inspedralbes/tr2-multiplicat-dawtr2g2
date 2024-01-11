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

    <table class="table is-striped is-hoverable is-fullwidth is-gray-bg">
        <tr>
            <th>ID</th>
            <th>Pregunta</th>
            <th>resposta_correcta_id</th>
            <th>resposta</th>
            <th>dificultat_id</th>
            <th>tema_id</th>
            <th></th>
        </tr>
        @foreach ($preguntes as $pregunta)
        <tr>
            <td>{{ $pregunta->id }}</td>
            <td><a href="{{ route('view-modificar-pregunta', ['id' => $pregunta->id]) }}">{{ $pregunta->pregunta }}</a></td>
            <td>{{ $pregunta->resposta_correcta_id}}</td>
            <td> {{ $pregunta->resposta }}</td>
            <td>{{ $pregunta->dificultat_id }}</td>
            <td>{{ $pregunta->tema_id }}</td>
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
/* Estilos para la tabla */
.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

/* Estilos para las celdas de encabezado */
th {
    background-color: #f8f9fa;
    padding: 12px;
    text-align: left;
    border: 1px solid #ddd;
}

/* Estilos para las celdas de datos */
td {
    padding: 12px;
    border: 1px solid #ddd;
}

/* Alternar colores de fondo para filas impares */
.is-striped tbody tr:nth-child(odd) {
    background-color: #f9f9f9;
}

/* Cambiar el color de fondo al pasar el ratón sobre las filas */
.is-hoverable tbody tr:hover {
    background-color: #f1f1f1;
}

/* Fondo gris para filas impares */
.is-gray-bg tbody tr:nth-child(odd) {
    background-color: #ddd;
}

/* Estilos para el botón de eliminar */
.eliminar {
    margin-top: 8px;
}

/* Puedes ajustar los estilos según tus preferencias y diseño general */

</style>


@endsection