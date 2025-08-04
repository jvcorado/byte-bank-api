<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Validation\ValidationException;

class Account extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id'];

    // Relacionamentos
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Busca todas as transações relacionadas a esta conta
    public function getTransactions()
    {
        return $this->transactions()->orderBy('created_at', 'desc')->get();
    }

    // Busca uma transação específica pelo ID
    public function getTransactionById($id)
    {
        return $this->transactions()->find($id);
    }

    // Calcula saldo somando receitas e despesas da conta
    public function getBalance()
    {
        $income = $this->transactions()->where('type', 'INCOME')->sum('amount');
        $expense = $this->transactions()->where('type', 'EXPENSE')->sum('amount');
        return $income - $expense;
    }

    // Accessor para o saldo (para usar como $account->balance)
    public function getBalanceAttribute()
    {
        return $this->getBalance();
    }

    // Valida transação antes de adicionar ou atualizar
    private function validateTransaction($amount, $type)
    {
        if ($type === 'INCOME' && $amount <= 0) {
            throw ValidationException::withMessages([
                'amount' => 'O valor de adição deve ser maior que zero.'
            ]);
        }

        if ($type === 'EXPENSE' && $amount <= 0) {
            throw ValidationException::withMessages([
                'amount' => 'O valor de despesa deve ser maior que zero.'
            ]);
        }

        // Validação opcional: verificar se há saldo suficiente para despesas
        // if ($type === 'EXPENSE' && $amount > $this->getBalance()) {
        //     throw ValidationException::withMessages([
        //         'amount' => 'O valor da remoção não pode ser maior que o saldo atual.'
        //     ]);
        // }

        return true;
    }

    // Adiciona nova transação
    public function addTransaction($transactionData)
    {
        $this->validateTransaction($transactionData['amount'], $transactionData['type']);

        return $this->transactions()->create([
            'type' => $transactionData['type'],
            'subtype' => $transactionData['subtype'] ?? null,
            'amount' => $transactionData['amount']
        ]);
    }

    // Atualiza transação existente
    public function updateTransaction($transactionId, $transactionData)
    {
        $transaction = $this->getTransactionById($transactionId);

        if (!$transaction) {
            throw ValidationException::withMessages([
                'transaction' => 'Transação não encontrada.'
            ]);
        }

        $this->validateTransaction($transactionData['amount'], $transactionData['type']);

        $transaction->update([
            'type' => $transactionData['type'],
            'subtype' => $transactionData['subtype'] ?? null,
            'amount' => $transactionData['amount']
        ]);

        return $transaction;
    }

    // Deleta transação pelo ID
    public function deleteTransaction($transactionId)
    {
        $transaction = $this->getTransactionById($transactionId);

        if (!$transaction) {
            throw ValidationException::withMessages([
                'transaction' => 'Transação não encontrada.'
            ]);
        }

        return $transaction->delete();
    }

    // Método para converter para JSON (similar ao toJSON do TypeScript)
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'user_id' => $this->user_id,
            'balance' => $this->getBalance(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }

    // Método estático para criar a partir de dados (similar ao fromJSON)
    public static function fromData($data)
    {
        return new self([
            'name' => strtolower($data['name']),
            'user_id' => $data['user_id'] ?? null
        ]);
    }
}
