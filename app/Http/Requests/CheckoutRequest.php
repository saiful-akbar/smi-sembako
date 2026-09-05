<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cart' => ['required','array','min:1'],
            'cart.*.product_id' => ['required','integer','exists:products,id'],
            'cart.*.qty' => ['required','integer','min:1'],
            'cart.*.price' => ['required','numeric','min:0'],
            'cart.*.discount' => ['nullable','numeric','min:0'],
            'subtotal' => ['required','numeric','min:0'],
            'discount_total' => ['required','numeric','min:0'],
            'tax_total' => ['required','numeric','min:0'],
            'grand_total' => ['required','numeric','min:0'],
            'payment_method' => ['required','in:cash,midtrans,split'],
            'split_cash_amount' => ['nullable','numeric','min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'cart.required' => 'Keranjang tidak boleh kosong.',
            'cart.*.product_id.exists' => 'Produk tidak ditemukan.',
            'cart.*.qty.min' => 'Jumlah minimal 1.',
            'grand_total.min' => 'Total tidak boleh negatif.',
        ];
    }
}
