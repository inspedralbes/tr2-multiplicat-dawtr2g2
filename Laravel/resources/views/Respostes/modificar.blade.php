@extends('app')

@section('content')
<div class="container p-4 mt-4">
    <form class="box container__form" action="{{ route('modificar-resposta', ['id' => $resposta->id]) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="field">
            <label for="resposta" class="label">Resposta</label>
            <input type="text" name="resposta" class="input" value="{{ $resposta->resposta }}">
        </div>
        <div class="field">
            <label for="tema_id" class="label">Tema_id</label>
            <input type="text" name="tema_id" class="input" value="{{ $resposta->tema_id }}">
        </div>
        <div class="field">
            <label for="dificultat_id" class="label">dificultat_id</label>
            <input type="text" name="dificultat_id" class="input" value="{{ $resposta->dificultat_id }}">
        </div>

        <button type="submit" class="button button--icon is-warning is-rounded is-responsive mt-4">
            <p>Modificar Resposta</p>
        </button>
        <div class="control">
            <a href="{{ route('respostes') }}" class="button button--icon is-danger is-rounded is-responsive mt-4">
                <p>Cancel·lar</p>
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