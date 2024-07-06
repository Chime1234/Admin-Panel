<?php
/*
 * File name: WalletTransactionCreatingEvent.php
 * Last modified: 2024.05.03 at 18:03:35
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace App\Events;

use App\Models\WalletTransaction;
use Exception;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WalletTransactionCreatingEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Exception
     */
    public function __construct(WalletTransaction $walletTransaction)
    {
        if ($walletTransaction->action == 'debit' && $walletTransaction->wallet->balance < $walletTransaction->amount) {
            throw new Exception(__('lang.wallet_balance_insufficient'));
        }
    }
}
