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
            
            <button type="submit" class="button button--icon is-warning is-rounded is-responsive mt-4"><p>MODIFICAR Resposta</p></button>
            <a href="{{ route('respostes') }}" class="button button--icon is-danger is-rounded is-responsive mt-4"><p>CANCEL·LAR</p></a>
        </form>
    </div>
@endsection