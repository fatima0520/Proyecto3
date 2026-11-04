@extends('template.base')

@section('title', 'Publicar noticia - Banditore')

@section('content')
<div class="container my-5">

    <div class="row justify-content-center">
        <div class="col-md-8">

            <h1 class="mb-4">Publicar una noticia</h1>

            <p class="text-muted">
                Completa el formulario para publicar una nueva noticia.
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

            <form action="{{ route('noticia.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <div class="mb-3">
                    <label for="titulo" class="form-label">
                        Título de la noticia
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="titulo"
                        name="titulo"
                        value="{{ old('titulo') }}"
                        minlength="3"
                        maxlength="200"
                        required
                    >

                    <div class="form-text">
                        Entre 3 y 200 caracteres.
                    </div>
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
                    >{{ old('texto') }}</textarea>

                    <div class="form-text">
                        Puedes escribir el contenido aquí o subir un archivo.
                    </div>
                </div>

                <div class="mb-4">
                    <label for="archivo_md" class="form-label">
                        Subir archivo Markdown o texto (opcional)
                    </label>

                    <input
                        type="file"
                        class="form-control"
                        id="archivo_md"
                        name="archivo_md"
                        accept=".md,.txt"
                    >

                    <div class="form-text">
                        Formatos permitidos: .md y .txt. Tamaño máximo: 2 MB.
                        Si subes un archivo, su contenido sustituirá al texto escrito.
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Publicar noticia
                    </button>

                    <a href="{{ route('noticia.index') }}"
                       class="btn btn-secondary">
                        Cancelar
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection

