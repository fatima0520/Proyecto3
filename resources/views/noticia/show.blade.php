@extends('template.base')

@section('title', $noticia->titulo . ' - InfoNova')

@section('content')
<div class="container my-5">

    <div class="row g-5">

        <div class="col-md-8">

            <article class="blog-post">

                <h1 class="blog-post-title">
                    {{ $noticia->titulo }}
                </h1>

                <p class="blog-post-meta">
                    Publicada el
                    {{ $noticia->created_at->format('d/m/Y H:i') }}
                </p>

                <div class="blog-post-content">
                    {!! nl2br(e($noticia->texto)) !!}
                </div>

            </article>

            <hr class="my-5">

            <h3 class="mb-4">
                Comentarios ({{ $noticia->comentarios->count() }})
            </h3>

            @forelse ($noticia->comentarios as $comentario)

                <div class="comentario
                    {{ $comentario->valoracion === 'pos' ? 'comentario-positivo' : 'comentario-negativo' }}">

                    <div class="d-flex justify-content-between flex-wrap">
                        <strong>
                            @if ($comentario->valoracion === 'pos')
                                <span class="text-success">Valoración positiva</span>
                            @else
                                <span class="text-danger">Valoración negativa</span>
                            @endif
                        </strong>

                        <small class="text-muted">
                            {{ $comentario->created_at->format('d/m/Y H:i') }}
                        </small>
                    </div>

                    <p class="mt-3 mb-2">
                        {{ $comentario->mensaje }}
                    </p>

                    @if ($comentario->sePuedeModificarOBorrar())

                        <div class="d-flex gap-2">

                            <a href="{{ route('comentario.edit', $comentario) }}"
                               class="btn btn-warning btn-sm">
                                Editar
                            </a>

                            <form action="{{ route('comentario.destroy', $comentario) }}"
                                  method="POST"
                                  onsubmit="return confirm('¿Seguro que quieres borrar este comentario?');">

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm">
                                    Borrar
                                </button>

                            </form>

                        </div>

                        <small class="text-muted">
                            Tiempo restante para modificarlo:
                            {{ $comentario->minutosRestantes() }} minutos.
                        </small>

                    @else

                        <small class="text-muted">
                            El plazo de modificación ha terminado.
                        </small>

                    @endif

                </div>

            @empty

                <div class="alert alert-info">
                    Esta noticia todavía no tiene comentarios.
                    ¡Sé el primero en comentar!
                </div>

            @endforelse

            <hr class="my-5">

            <h3 class="mb-4">Añadir un comentario</h3>

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

            <form action="{{ route('comentario.store') }}" method="POST">

                @csrf

                <input type="hidden"
                       name="noticia_id"
                       value="{{ $noticia->id }}">

                <div class="mb-3">
                    <label for="mensaje" class="form-label">
                        Tu comentario
                    </label>

                    <textarea
                        name="mensaje"
                        id="mensaje"
                        class="form-control"
                        rows="4"
                        minlength="2"
                        maxlength="1000"
                        required
                    >{{ old('mensaje') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="valoracion" class="form-label">
                        Valoración
                    </label>

                    <select name="valoracion"
                            id="valoracion"
                            class="form-select"
                            required>

                        <option value="">Selecciona una valoración</option>

                        <option value="pos"
                            {{ old('valoracion') === 'pos' ? 'selected' : '' }}>
                            Positiva
                        </option>

                        <option value="neg"
                            {{ old('valoracion') === 'neg' ? 'selected' : '' }}>
                            Negativa
                        </option>

                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    Enviar comentario
                </button>

            </form>

            <div class="mt-5">
                <a href="{{ route('noticia.index') }}"
                   class="btn btn-secondary">
                    Volver a las noticias
                </a>
            </div>

        </div>

        <div class="col-md-4">

            <div class="blog-sidebar">
                <h4>Información</h4>

                <p>
                    <strong>Publicado:</strong>
                    {{ $noticia->created_at->format('d/m/Y H:i') }}
                </p>

                <p>
                    <strong>Comentarios:</strong>
                    {{ $noticia->comentarios->count() }}
                </p>

                @if ($noticia->sePuedeEditar())
                    <p class="text-success">
                        Puedes editar esta noticia durante
                        {{ $noticia->minutosRestantesEdicion() }} minutos más.
                    </p>
                @else
                    <p class="text-danger">
                        El plazo de edición ha terminado.
                    </p>
                @endif

                @if ($noticia->sePuedeBorrar())
                    <p class="text-success">
                        Esta noticia se puede borrar porque no tiene comentarios.
                    </p>
                @else
                    <p class="text-danger">
                        Esta noticia no se puede borrar porque tiene comentarios.
                    </p>
                @endif

            </div>

        </div>

    </div>

</div>
@endsection

