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
            <label for="tema_id" class="label">Tema_id</label>
            <input type="text" name="tema_id" class="input" value="{{ $pregunta->tema_id }}">
        </div>
        <div class="field">
            <label for="dificultat_id" class="label">dificultat_id</label>
            <input type="text" name="dificultat_id" class="input" value="{{ $pregunta->dificultat_id }}">
        </div>

        <button type="submit" class="button button--icon is-warning is-rounded is-responsive mt-4">
            <p>MODIFICAR LLIBRE</p>
        </button>
        <a href="{{ route('preguntes') }}" class="button button--icon is-danger is-rounded is-responsive mt-4">
            <p>CANCEL·LAR</p>
        </a>
    </form>
</div>
@endsection