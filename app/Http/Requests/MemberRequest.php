<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class MemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();
        return $user->hasAnyRole(['admin', 'superadmin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => 'required|min:2|max:255',
            'phone' => [
                'required',
                'regex:/^(\+?[\d\s-]{7,15})$/', // Basic phone number validation
                Rule::unique('members')->ignore($this->route('member')) // Ignore current member for update
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('members')->ignore($this->route('member')) // Ignore current member for update
            ],
            'birth_date' => 'nullable|date',
            'marital_status' => 'nullable|string|max:50',
            'join_date' => 'nullable|date',
            'group_id' => 'required|exists:groups,id',
            'homecell_id' => 'nullable|exists:homecells,id',
            'active' => 'boolean',
            'created_by' => 'required|exists:users,id',
            'gender' => 'required|string|max:50',
            'residency' => 'nullable|string|max:255',
            'postal_address' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'spouse' => 'nullable|string|max:255',
            'spouse_number' => 'nullable|string|max:50',
            'next_of_kin' => 'nullable|string|max:255',
            'next_of_kin_number' => 'nullable|string|max:50',
            'official_join_date' => 'nullable|date',
            'born_again' => 'nullable|boolean',
            'spirit_filled_when' => 'nullable|date',
            'water_immersed_when' => 'nullable|date',
            'from_church' => 'nullable|string|max:255',
            'from_church_branch' => 'nullable|string|max:255',
            'from_church_pastor' => 'nullable|string|max:255',
            'from_church_pastor_number' => 'nullable|string|max:50'
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
