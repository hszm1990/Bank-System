<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory;
    public const CREDIT = 'credit';
    public const DEBIT = 'debit';

    protected $fillable = [
        'source_card_id',
        'destination_card_id',
        'amount',
        'type'
    ];

    public function card()
    {
        return $this->belongsTo(Card::class, 'source_card_id');
    }
    public function sourceCard(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'source_card_id');
    }

    public function destinationCard(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'destination_card_id');
    }

    public function fee(): HasOne
    {
        return $this->hasOne(TransactionFee::class);
    }
}
