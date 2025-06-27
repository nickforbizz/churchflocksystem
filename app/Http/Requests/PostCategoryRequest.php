<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class PostCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();

        return auth()->check();

        // return $user->hasAnyRole(['writer', 'editor', 'admin', 'superadmin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        // rules for creating and updating a post category
        return [
            'name' => ['required', 'min:2', Rule::unique('post_categories', 'name')->ignore($this->route('postCategory'))],
            'description' => ['nullable', 'min:5'],
            'slug' => ['nullable', 'min:2', Rule::unique('post_categories', 'slug')->ignore($this->route('postCategory'))],
            'created_by' => ['required', 'exists:users,id'],
            'active' => ['nullable', 'boolean'],
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
        $this->merge([
            'slug' => Str::slug($this->input('name')),
            'created_by' => Auth::id()
        ]);
    }
}
