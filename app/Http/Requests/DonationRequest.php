<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class DonationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();
        // return $user->hasAnyRole(['admin', 'superadmin']);
        
        // Only allow authenticated users to create donations
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric',
            'purpose' => 'required',
            'method' => 'required',
            'date' => 'required|date',
            'active' => 'boolean',
            'created_by' => 'required|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'unique' => ':attribute is already used',
            'required' => 'The :attribute field is required.',
        ];
    }


    public function prepareForValidation()
    {
        // generate UUID receipt number
        if ($this->isMethod('post') && !$this->has('receipt_number')) {
            $this->merge([
                'receipt_number' => (string) Str::uuid()
            ]);
        }
        // This sets variables before validation occurs.
        $this->merge([
            'created_by' => Auth::id()
        ]);
    }

}
