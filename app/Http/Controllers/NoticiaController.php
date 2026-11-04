<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use App\Models\Comentario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Exception;

class NoticiaController extends Controller
{
    private const RULES = [
    'titulo' => 'required|string|min:3|max:200',
    'texto' => 'nullable|string',
    'archivo_md' => 'nullable|file|mimes:txt,md|max:2048',
    ];

    private const MESSAGES = [
    'titulo.required' => 'El título es obligatorio.',
    'titulo.min' => 'El título debe tener al menos 3 caracteres.',
    'titulo.max' => 'El título no puede superar los 200 caracteres.',
    'archivo_md.mimes' => 'El archivo debe ser de tipo .txt o .md.',
    'archivo_md.max' => 'El archivo no puede superar los 2 MB.',
    ];
    /**
     * Display a listing of the resource.
     */
    
    public function index(): View
    {
        // Obtener las noticias y contar sus comentarios
        $noticias = Noticia::withCount('comentarios')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        // Obtener el número total de comentarios
        $totalComentarios = Comentario::count();

        // Enviar los datos a la vista
        return view('noticia.index', compact('noticias', 'totalComentarios'));
    }


    /**
     * Show the form for creating a new resource.
     */
    
    public function create(): View
    {
        // Mostrar el formulario para crear una noticia
        return view('noticia.create');
    }


    /**
     * Store a newly created resource in storage.
     */
    
    public function store(Request $request): RedirectResponse
    {
        // Validar los datos recibidos del formulario
        $validator = Validator::make(
            $request->all(),
            self::RULES,
            self::MESSAGES
        );

        // Comprobar si hay errores de validación
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Comprobar si se ha subido un archivo
        if ($request->hasFile('archivo_md')) {
            // Leer el contenido del archivo
            $texto = file_get_contents(
                $request->file('archivo_md')->getRealPath()
            );
        } else {
            // Si no hay archivo, utilizar el texto del formulario
            $texto = $request->input('texto');
        }

        // Crear y guardar la noticia
        Noticia::create([
            'titulo' => $request->input('titulo'),
            'texto' => $texto,
        ]);

        // Volver al listado con un mensaje de éxito
        return redirect()->route('noticia.index')
            ->with('success', 'La noticia se ha creado correctamente.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
    // Buscar la noticia por su identificador y obtener sus comentarios
    $noticia = Noticia::with('comentarios')->findOrFail($id);

    // Enviar la noticia a la vista
    return view('noticia.show', compact('noticia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View|RedirectResponse
    {
    // Buscar la noticia
    $noticia = Noticia::findOrFail($id);

    // Comprobar si todavía se puede editar
    if (!$noticia->sePuedeEditar()) {
        return redirect()->route('noticia.index')
            ->with('error', 'Han pasado los 20 minutos. Ya no puedes editar esta noticia.');
    }

    // Mostrar el formulario de edición
    return view('noticia.edit', compact('noticia'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, string $id): RedirectResponse
    {
    // Buscar la noticia
    $noticia = Noticia::findOrFail($id);

    // Comprobar si todavía se puede editar
    if (!$noticia->sePuedeEditar()) {
        return redirect()->route('noticia.index')
            ->with('error', 'Han pasado los 20 minutos. Ya no puedes editar esta noticia.');
    }

    // Validar los datos recibidos
    $validator = Validator::make(
        $request->all(),
        self::RULES,
        self::MESSAGES
    );

    // Comprobar si hay errores de validación
    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    // Comprobar si se ha subido un archivo
    if ($request->hasFile('archivo_md')) {
        // Leer el contenido del archivo
        $texto = file_get_contents(
            $request->file('archivo_md')->getRealPath()
        );
    } else {
        // Utilizar el texto escrito en el formulario
        $texto = $request->input('texto');
    }

    // Actualizar los datos de la noticia
    $noticia->update([
        'titulo' => $request->input('titulo'),
        'texto' => $texto,
    ]);

    // Volver al listado con un mensaje
    return redirect()->route('noticia.index')
        ->with('success', 'La noticia se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
    // Buscar la noticia
    $noticia = Noticia::findOrFail($id);

    // Comprobar si se puede borrar
    if (!$noticia->sePuedeBorrar()) {
        return redirect()->route('noticia.index')
            ->with('error', 'No puedes eliminar esta noticia porque tiene comentarios.');
    }

    // Eliminar la noticia
    $noticia->delete();

    // Volver al listado con un mensaje
    return redirect()->route('noticia.index')
        ->with('success', 'La noticia se ha eliminado correctamente.');
    }
}
