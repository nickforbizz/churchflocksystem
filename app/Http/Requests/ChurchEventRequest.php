<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ChurchEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();
        // return $user->hasAnyRole(['admin', 'superadmin']);
        
        // Only allow authenticated users to create groups
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
            'title' => 'required|min:2|max:255',
            'location' => 'required|min:2|max:255',
            'description' => 'nullable',
            'event_date' => 'required',
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
        // This sets variables before validation occurs.
        $this->merge([
            'created_by' => Auth::id()
        ]);
    }


    public function passedValidation()
    {
        $this->merge([
            'created_by' => Auth::id()
        ]);
    }
}
