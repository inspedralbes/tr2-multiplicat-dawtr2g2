@extends('app')

@section('content')
<div class="container p-4 mt-4">
        <form class="box container__form" action="{{ route('afegir-resposta') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="field">
                <label for="resposta" class="label">Resposta</label>
                <input type="text" name="resposta" class="input">
            </div>
            <div class="field">
                <label for="tema_id" class="label">Tema_id</label>
                <input type="text" name="tema_id" class="input">
            </div>
            <div class="field">
                <label for="dificultat_id" class="label">Dificultat_id</label>
                <input type="text" name="dificultat_id" class="input">
            </div>
            
            <button type="submit" class="button button--icon is-success is-rounded is-responsive mt-4"><p>Afegir Resposta</p></button>
            <a href="{{ route('respostes') }}" class="button button--icon is-danger is-rounded is-responsive mt-4"><p>Cancel·lar</p></a>
        </form>
    </div>
@endsection