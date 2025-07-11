<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory; // ¡Este trait es esencial para usar factories!

    protected $fillable = [
        'type', 'amount', 'category_id', 'account_id', 
        'date', 'description', 'user_id'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->withDefault([
            'name' => 'Sin categoría',
            'type' => 'expense'
        ]);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class)->withDefault([
            'name' => 'Sin cuenta',
            'type' => 'cash',
            'balance' => 0
        ]);
    }
}