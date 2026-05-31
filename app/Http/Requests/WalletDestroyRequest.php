<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WalletDestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $wallet = $this->route('wallet');

        return $wallet && $wallet->family_id == auth()->user()->family_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $wallet = $this->route('wallet');

            if ($wallet && $wallet->transactions()->exists()) {
                $validator->errors()->add('wallet', "Wallet can't be deleted because it has associated transactions.");
            }
        });
    }
}
