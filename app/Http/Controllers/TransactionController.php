<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $accountId)
    {
        $account = $request->user()->accounts()->findOrFail($accountId);

        $validated = $request->validate([
            'type' => 'required|in:INCOME,EXPENSE',
            'amount' => 'required|numeric|min:0.01',
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
            'amount' => 'required|numeric|min:0.01',
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
