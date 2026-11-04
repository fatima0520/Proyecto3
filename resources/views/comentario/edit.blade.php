@extends('template.base')

@section('title', 'Editar comentario - Banditore')

@section('content')
<div class="container my-5">

    <div class="row justify-content-center">
        <div class="col-md-8">

            <h1 class="mb-4">Editar comentario</h1>

            <p class="text-muted">
                Puedes modificar este comentario durante los primeros
                5 minutos desde su publicación.
            </p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Hay errores en el formulario:</strong>

                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('comentario.update', $comentario) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="mensaje" class="form-label">
                        Comentario
                    </label>

                    <textarea
                        name="mensaje"
                        id="mensaje"
                        class="form-control"
                        rows="5"
                        minlength="2"
                        maxlength="1000"
                        required
                    >{{ old('mensaje', $comentario->mensaje) }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="valoracion" class="form-label">
                        Valoración
                    </label>

                    <select name="valoracion"
                            id="valoracion"
                            class="form-select"
                            required>

                        <option value="pos"
                            {{ old('valoracion', $comentario->valoracion) === 'pos' ? 'selected' : '' }}>
                            Positiva
                        </option>

                        <option value="neg"
                            {{ old('valoracion', $comentario->valoracion) === 'neg' ? 'selected' : '' }}>
                            Negativa
                        </option>

                    </select>
                </div>

                <div class="d-flex gap-2">

                    <button type="submit" class="btn btn-primary">
                        Guardar cambios
                    </button>

                    <a href="{{ route('noticia.show', $comentario->noticia_id) }}"
                       class="btn btn-secondary">
                        Cancelar
                    </a>

                </div>

            </form>

        </div>
    </div>

</div>
@endsection
