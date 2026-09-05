<?php

namespace App\Services;

use App\Models\Sale;

class MidtransService
{
    public function createTransaction(Sale $sale, array $options = []): array
    {
        // Stub: return a mock token and redirect URL for Midtrans Snap
        $token = 'mock_token_' . $sale->id;
        $redirect_url = url('/pos/receipt/' . $sale->id);
        return compact('token', 'redirect_url');
    }
}
