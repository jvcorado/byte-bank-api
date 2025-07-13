<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $request->user()->accounts()->with('transactions')->get()->map(function ($account) {
            return [
                'id' => $account->id,
                'name' => $account->name,
                'balance' => $account->getBalance(),
                'transactions_count' => $account->transactions->count(),
                'created_at' => $account->created_at,
                'updated_at' => $account->updated_at
            ];
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string'
        ]);

        $account = $request->user()->accounts()->create([
            'name' => strtolower($validated['name'])
        ]);

        return [
            'id' => $account->id,
            'name' => $account->name,
            'balance' => $account->getBalance(),
            'created_at' => $account->created_at,
            'updated_at' => $account->updated_at
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $account = $request->user()->accounts()->findOrFail($id);

        return [
            'id' => $account->id,
            'name' => $account->name,
            'balance' => $account->getBalance(),
            'transactions' => $account->getTransactions(),
            'created_at' => $account->created_at,
            'updated_at' => $account->updated_at
        ];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string'
        ]);

        $account = $request->user()->accounts()->findOrFail($id);
        $account->update(['name' => strtolower($validated['name'])]);

        return [
            'id' => $account->id,
            'name' => $account->name,
            'balance' => $account->getBalance(),
            'created_at' => $account->created_at,
            'updated_at' => $account->updated_at
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $account = $request->user()->accounts()->findOrFail($id);
        $account->delete();
        return response()->noContent();
    }
}
