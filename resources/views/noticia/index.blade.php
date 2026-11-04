@extends('template.base')

@section('title', 'Portada - Banditore')

@section('hero')
<div class="p-4 p-md-5 mb-4 rounded text-bg-dark">
    <div class="col-lg-8 px-0">
        <h1 class="display-4 fst-italic">Banditore</h1>
        <p class="lead my-3">
            Tu espacio para publicar noticias y compartir opiniones.
        </p>
        <p class="lead mb-0">
            <a href="{{ route('noticia.create') }}" class="text-white fw-bold">
                Publicar una noticia
            </a>
        </p>
    </div>
</div>
@endsection

@section('content')
<div class="row g-5">

    <div class="col-md-8">
        <h3 class="pb-4 mb-4 border-bottom">
            Últimas noticias
        </h3>

        @forelse ($noticias as $noticia)
            <article class="blog-post mb-5">

                <h2 class="blog-post-title">
                    <a href="{{ route('noticia.show', $noticia) }}" class="text-dark">
                        {{ $noticia->titulo }}
                    </a>
                </h2>

                <p class="blog-post-meta">
                    Publicada el
                    {{ $noticia->created_at->format('d/m/Y H:i') }}
                </p>

                <p>
                    {{ \Illuminate\Support\Str::limit(strip_tags($noticia->texto), 300) }}
                </p>

                <p>
                    <span class="badge bg-secondary">
                        {{ $noticia->comentarios_count }} comentarios
                    </span>
                </p>

                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('noticia.show', $noticia) }}"
                       class="btn btn-primary btn-sm">
                        Leer noticia
                    </a>

                    @if ($noticia->sePuedeEditar())
                        <a href="{{ route('noticia.edit', $noticia) }}"
                           class="btn btn-warning btn-sm">
                            Editar
                        </a>
                    @else
                        <span class="badge bg-secondary align-self-center">
                            Plazo de edición terminado
                        </span>
                    @endif

                    @if ($noticia->sePuedeBorrar())
                        <form action="{{ route('noticia.destroy', $noticia) }}"
                              method="POST"
                              onsubmit="return confirm('¿Seguro que quieres borrar esta noticia?');">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger btn-sm">
                                Borrar
                            </button>
                        </form>
                    @else
                        <span class="badge bg-secondary align-self-center">
                            Tiene comentarios: no se puede borrar
                        </span>
                    @endif
                </div>

            </article>
        @empty
            <div class="alert alert-info">
                Todavía no hay noticias publicadas.
            </div>
        @endforelse

        <div class="mt-4">
            {{ $noticias->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <div class="col-md-4">

        <div class="blog-sidebar">
            <h4>Sobre Banditore</h4>
            <p>
                Banditore es una aplicación para publicar noticias y permitir
                que los usuarios compartan sus opiniones mediante comentarios.
            </p>
        </div>

        <div class="blog-sidebar">
            <h4>Estadísticas</h4>
            <p>
                Total de comentarios:
                <strong>{{ $totalComentarios }}</strong>
            </p>
            <p>
                Noticias mostradas:
                <strong>{{ $noticias->count() }}</strong>
            </p>
        </div>

        <div class="blog-sidebar">
            <h4>Normas</h4>
            <ul>
                <li>Las noticias se pueden editar durante 20 minutos.</li>
                <li>Las noticias con comentarios no se pueden borrar.</li>
                <li>Los comentarios se pueden modificar durante 5 minutos.</li>
            </ul>
        </div>

    </div>

</div>
@endsection

