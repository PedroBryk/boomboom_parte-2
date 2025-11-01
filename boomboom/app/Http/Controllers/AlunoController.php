<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aluno;

class AlunoController extends Controller
{
    // Lista todos os alunos
    public function index()
    {
        return view('alunos.index');
    }

    // Cadastra um novo aluno
    public function store(Request $request)
{
    // 1. Regras de validaçã
    $regras = [
        'nome' => 'required|string|min:3|max:100',
        'email' => 'required|string|email|max:50',
        'senha' => 'required|string|min:8|max:100',
        'telefone' => 'required|string|min:10|max:20',
        'data_nascimento' => 'nullable|date'
    ];

    // 2. Mensagens de erro personalizadas
    $mensagens = [
        'nome.required' => 'O nome é obrigatório.',
        'nome.min' => 'O nome deve ter pelo menos :min caracteres.',
        'nome.max' => 'O nome não pode exceder :max caracteres.',
        'senha.required' => 'A senha é obrigatória.',
        'senha.min' => 'A senha deve ter no mínimo :min caracteres.',
        'email.required' => 'O email é obrigatório.',
        'email.email' => 'Digite um email válido.',
        'email.unique' => 'Este email já está cadastrado.',
        'telefone.required' => 'O telefone é obrigatório.',
        'data_nascimento.required' => 'A data de nascimento é obrigatória.',
    ];

    // 3. Validação
    $request->validate($regras, $mensagens);

    // 4. Criação do aluno
    $aluno = Aluno::create([
        'nome' => $request->nome,
        'email' => $request->email,
        'senha' => bcrypt($request->senha),
        'telefone' => $request->telefone,
        'data_nascimento' => $request->data_nascimento,

    ]);

    return response()->json($aluno, 201);
}

    // Mostra um aluno específico
    public function show($id)
    {
        $aluno = Aluno::find($id);

        if (!$aluno) {
            return response()->json(['message' => 'Aluno não encontrado'], 404);
        }

        return response()->json($aluno, 200); //professor, estamos retornando no formato json para testar no postman
    }

    // Atualiza um aluno existente
    public function update(Request $request, $id)
    {
        $aluno = Aluno::findOrFail($id);

       $aluno->update([
        'nome' => $request->nome ?? $aluno->nome,
        'email' => $request->email ?? $aluno->email,
        'senha' => $request->filled('senha') ? bcrypt($request->senha) : $aluno->senha,
        'telefone' => $request->telefone ?? $aluno->telefone,
        'data_nascimento' => $request->data_nascimento ?? $aluno->data_nascimento,
    ]);

        return response()->json($aluno, 200);
    }

    // Deleta um aluno
    public function destroy($id)
    {
        $aluno = Aluno::findOrFail($id);
        $aluno->delete();

        return response()->json(['message' => 'Aluno deletado com sucesso'], 200);
    }
}
