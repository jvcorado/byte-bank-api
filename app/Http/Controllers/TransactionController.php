<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Enums\TransactionSubtype;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $accountId)
    {
        $account = $request->user()->accounts()->findOrFail($accountId);

        // Parâmetros de paginação
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);

        // Query base
        $query = $account->transactions()->orderBy('created_at', 'desc');

        // Filtros opcionais
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('subtype')) {
            $query->where('subtype', $request->subtype);
        }

        if ($request->has('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Executar paginação
        $transactions = $query->paginate($perPage, ['*'], 'page', $page);

        // Formatar resposta no padrão esperado pelo frontend
        return response()->json([
            'transactions' => $transactions->items(),
            'pagination' => [
                'currentPage' => $transactions->currentPage(),
                'nextPage' => $transactions->hasMorePages() ? $transactions->currentPage() + 1 : null,
                'prevPage' => $transactions->currentPage() > 1 ? $transactions->currentPage() - 1 : null,
                'totalPages' => $transactions->lastPage(),
                'totalItems' => $transactions->total(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $accountId)
    {
        $account = $request->user()->accounts()->findOrFail($accountId);

        $validated = $request->validate([
            'type' => 'required|in:INCOME,EXPENSE',
            'subtype' => 'nullable|in:' . implode(',', TransactionSubtype::values()),
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
            'document' => 'nullable|string|max:255',
        ]);

        try {
            $transaction = $account->addTransaction($validated);
            return $transaction;
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Buscar a transação e verificar se pertence ao usuário autenticado
        $transaction = Transaction::whereHas('account', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })->findOrFail($id);

        $validated = $request->validate([
            'type' => 'required|in:INCOME,EXPENSE',
            'subtype' => 'nullable|in:' . implode(',', TransactionSubtype::values()),
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
            'document' => 'nullable|string|max:255',
        ]);

        try {
            $account = $transaction->account;
            $updatedTransaction = $account->updateTransaction($id, $validated);
            return $updatedTransaction;
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function destroy(Request $request, $id)
    {
        // Buscar a transação e verificar se pertence ao usuário autenticado
        $transaction = Transaction::whereHas('account', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })->findOrFail($id);

        try {
            $account = $transaction->account;
            $account->deleteTransaction($id);
            return response()->noContent();
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }
}
