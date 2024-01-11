@extends('app')

@section('content')
<div class="container p-4 mt-4">
    <form class="box container__form" action="{{ route('modificar-pregunta', ['id' => $pregunta->id]) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="field">
            <label for="pregunta" class="label">Pregunta</label>
            <input type="text" name="pregunta" class="input" value="{{ $pregunta->pregunta }}">
        </div>
        <div class="field">
            <label for="resposta" class="label">Resposta text</label>
            <input type="text" name="resposta" class="input" value="{{ $pregunta->resposta }}">
        </div>
        <div class="field">
            <label for="tema_id" class="label">Tema</label>
            <p value="">Geometria</option>
        </div>
        <div class="field">
            <label for="dificultat" class="label">Dificultat</label>

            <select name="dificultat_id" id="">
                @foreach($dificultats as $dificultat)
                <option value="{{ $dificultat->id }}">{{ $dificultat->nom_dificultat }}</option>
                @endforeach
            </select>

        </div>

        <button type="submit" class="button button--icon is-warning is-rounded is-responsive mt-4">
            <p>Modificar pregunta</p>
        </button>
        <div class="control">
            <a href="{{ route('preguntes') }}" class="button is-danger">
                <span>Cancel·lar</span>
            </a>
        </div>

    </form>
</div>
<style>
    .container__form {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
    }

    .field {
        margin-bottom: 1.5rem;
    }

    .label {
        font-weight: bold;
        display: block;
        margin-bottom: 0.5rem;
    }

    .input,
    .select {
        width: 100%;
        padding: 0.5rem;
        font-size: 1rem;
        border-radius: 3px;
        border: 1px solid #ccc;
    }

    .button {
        margin-top: 1rem;
    }

    .is-danger {
        background-color: #ff3860;
        color: white;
    }

    .is-warning {
        background-color: #ffdd57;
        color: #333;
    }

    p {
        color: black;
    }

    .control {
        margin-top: 1rem;
    }

    .control a {
        display: inline-block;
        padding: 10px 20px;
        text-decoration: none;
        background-color: #ff3860;
        color: white;
        border-radius: 5px;
    }

    .control a:hover {
        background-color: #ff506b;
    }

    .button {
        display: inline-block;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .is-warning {
        background-color: #ffdd57;
        color: #333;
    }

    .is-warning:hover {
        background-color: #ffeb89;
    }
</style>
@endsection