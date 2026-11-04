<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ComentarioController extends Controller
{
    private const RULES = [
        'noticia_id' => 'required|exists:noticia,id',
        'mensaje' => 'required|string|min:2|max:1000',
        'valoracion' => 'required|in:pos,neg',
    ];

    private const MESSAGES = [
        'noticia_id.required' => 'Debes indicar la noticia.',
        'noticia_id.exists' => 'La noticia seleccionada no existe.',
        'mensaje.required' => 'El comentario es obligatorio.',
        'mensaje.min' => 'El comentario debe tener al menos 2 caracteres.',
        'mensaje.max' => 'El comentario no puede superar los 1000 caracteres.',
        'valoracion.required' => 'Debes seleccionar una valoración.',
        'valoracion.in' => 'La valoración debe ser positiva o negativa.',
    ];

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
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

        // Crear y guardar el comentario
        Comentario::create([
            'noticia_id' => $request->input('noticia_id'),
            'mensaje' => $request->input('mensaje'),
            'valoracion' => $request->input('valoracion'),
        ]);

        // Volver a la noticia con un mensaje de éxito
        return redirect()->route(
            'noticia.show',
            $request->input('noticia_id')
        )->with('success', 'El comentario se ha publicado correctamente.');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View|RedirectResponse
    {
        // Buscar el comentario
        $comentario = Comentario::findOrFail($id);

        // Comprobar si todavía se puede modificar
        if (!$comentario->sePuedeModificarOBorrar()) {
            return redirect()->route('noticia.show', $comentario->noticia_id)
                ->with('error', 'Han pasado los 5 minutos. Ya no puedes editar este comentario.');
        }

        // Mostrar el formulario de edición
        return view('comentario.edit', compact('comentario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $comentario = Comentario::findOrFail($id);

        // Comprobar que todavía se puede modificar
        if (!$comentario->sePuedeModificarOBorrar()) {
            return redirect()
                ->route('noticia.show', $comentario->noticia_id)
                ->with('error', 'Han pasado los 5 minutos. Ya no puedes modificar este comentario.');
        }

        // Validar los datos que realmente se modifican
        $validator = Validator::make(
            $request->all(),
            [
                'mensaje' => 'required|string|min:2|max:1000',
                'valoracion' => 'required|in:pos,neg',
            ],
            self::MESSAGES
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Guardar los cambios
        $comentario->update($validator->validated());

        return redirect()
            ->route('noticia.show', $comentario->noticia_id)
            ->with('success', 'Comentario actualizado correctamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        // Buscar el comentario
        $comentario = Comentario::findOrFail($id);

        // Comprobar si todavía se puede eliminar
        if (!$comentario->sePuedeModificarOBorrar()) {
            return redirect()->route('noticia.show', $comentario->noticia_id)
                ->with('error', 'Han pasado los 5 minutos. Ya no puedes eliminar este comentario.');
        }

        // Guardar el identificador de la noticia
        $noticiaId = $comentario->noticia_id;

        // Eliminar el comentario
        $comentario->delete();

        // Volver a la noticia con un mensaje
        return redirect()->route('noticia.show', $noticiaId)
            ->with('success', 'El comentario se ha eliminado correctamente.');
    }
}
