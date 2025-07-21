<?php

namespace App\Http\Controllers;

use App\Models\Desafio;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DesafioController extends Controller
{
    public function index(Request $request)
    {
        $query = Desafio::query();

        if ($request->has('dificuldade')) {
            $query->where('dificuldade', $request->dificuldade);
        }

        if ($request->has('metodo_pontuacao')) {
            $query->where('metodo_pontuacao', $request->metodo_pontuacao);
        }

        if ($request->has('titulo')) {
            $query->where('titulo', 'like', '%' . $request->titulo . '%');
        }

        $desafios = $query->paginate(10);

        // Montar URL nas imagens de cada item da paginação
        $desafios->getCollection()->transform(function ($desafio) {
            return $this->montarUrlFoto($desafio);
        });

        return response()->json($desafios);
    }


    /**
     * Criar um novo desafio.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'dificuldade' => 'required|in:facil,medio,dificil',
            'pontos_recompensa' => 'required|integer',
            'duracao_dias' => 'required|integer',
            'url_foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'metodo_pontuacao' => 'required|in:duracao,distancia,calorias,dias_ativos',
            'meta_pontuacao' => 'nullable|numeric',
        ]);


        if ($request->hasFile('url_foto')) {
            $foto = $request->file('url_foto');
            $filename = uniqid('desafio_') . '.' . $foto->getClientOriginalExtension();
            $path = $foto->storeAs('desafios', $filename, 'public');
            $validated['url_foto'] = asset('storage/' . $path);
        }

        $desafio = Desafio::create($validated);

        return response()->json([
            'message' => 'Desafio criado com sucesso.',
            'data' => $desafio
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $desafio = Desafio::findOrFail($id);

        $validated = $request->validate([
            'titulo' => 'sometimes|required|string|max:255',
            'descricao' => 'nullable|string',
            'dificuldade' => 'sometimes|required|in:facil,medio,dificil',
            'pontos_recompensa' => 'sometimes|required|integer',
            'duracao_dias' => 'sometimes|required|integer',
            'url_foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'metodo_pontuacao' => 'sometimes|required|in:duracao,distancia,calorias,dias_ativos',
            'meta_pontuacao' => 'nullable|numeric',
        ]);

        // Upload da nova imagem se enviada
        if ($request->hasFile('url_foto')) {
            // 🔹 Deleta foto antiga se desejar
            if ($desafio->url_foto) {
                $oldPath = str_replace(asset('storage') . '/', '', $desafio->url_foto);
                if (file_exists(storage_path('app/public/' . $oldPath))) {
                    unlink(storage_path('app/public/' . $oldPath));
                }
            }

            $foto = $request->file('url_foto');
            $filename = uniqid('desafio_') . '.' . $foto->getClientOriginalExtension();
            $path = $foto->storeAs('desafios', $filename, 'public');
            $validated['url_foto'] = asset('storage/' . $path);
        }

        $desafio->update($validated);

        return response()->json([
            'message' => 'Desafio atualizado com sucesso.',
            'data' => $desafio
        ]);
    }

    private function montarUrlFoto($desafio)
{
    if ($desafio->url_foto) {
        // Se já começa com http, não faz nada
        if (!Str::startsWith($desafio->url_foto, ['http://', 'https://'])) {
            $desafio->url_foto = asset('storage/' . $desafio->url_foto);
        }
    } else {
        $desafio->url_foto = null;
    }

    return $desafio;
}

    public function indexId($id)
    {
        $desafio = Desafio::findOrFail($id);
        $desafio = $this->montarUrlFoto($desafio);

        return response()->json($desafio);
    }
}
