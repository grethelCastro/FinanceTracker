<?php

namespace App\Observers;

use App\Models\Transaction;

class TransactionObserver
{
    public function created(Transaction $transaction)
    {
        $account = $transaction->account;
        
        if ($transaction->type === 'income') {
            $account->balance += $transaction->amount;
        } else {
            $account->balance -= $transaction->amount;
        }
        
        $account->save();
    }
    
    public function updated(Transaction $transaction)
    {
        // Lógica similar para actualizar saldos si la transacción cambia
    }
    
    public function deleted(Transaction $transaction)
    {
        // Revertir el efecto en la cuenta
        $account = $transaction->account;
        
        if ($transaction->type === 'income') {
            $account->balance -= $transaction->amount;
        } else {
            $account->balance += $transaction->amount;
        }
        
        $account->save();
    }
}