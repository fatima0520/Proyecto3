@extends('template.base')

@section('title', 'Editar noticia - InfoNova')

@section('content')
<div class="container my-5">

    <div class="row justify-content-center">
        <div class="col-md-8">

            <h1 class="mb-4">Editar noticia</h1>

            <p class="text-muted">
                Puedes modificar esta noticia durante los primeros 20 minutos
                desde su publicación.
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

            <form action="{{ route('noticia.update', $noticia) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="titulo" class="form-label">
                        Título de la noticia
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="titulo"
                        name="titulo"
                        value="{{ old('titulo', $noticia->titulo) }}"
                        minlength="3"
                        maxlength="200"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="texto" class="form-label">
                        Contenido de la noticia
                    </label>

                    <textarea
                        class="form-control"
                        id="texto"
                        name="texto"
                        rows="10"
                    >{{ old('texto', $noticia->texto) }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="archivo_md" class="form-label">
                        Sustituir contenido mediante un archivo (opcional)
                    </label>

                    <input
                        type="file"
                        class="form-control"
                        id="archivo_md"
                        name="archivo_md"
                        accept=".md,.txt"
                    >

                    <div class="form-text">
                        Puedes subir un archivo .md o .txt de hasta 2 MB.
                        Si subes uno, su contenido sustituirá al texto anterior.
                    </div>
                </div>

                <div class="d-flex gap-2">

                    <button type="submit" class="btn btn-primary">
                        Guardar cambios
                    </button>

                    <a href="{{ route('noticia.show', $noticia) }}"
                       class="btn btn-secondary">
                        Cancelar
                    </a>

                </div>

            </form>

        </div>
    </div>

</div>
@endsection

