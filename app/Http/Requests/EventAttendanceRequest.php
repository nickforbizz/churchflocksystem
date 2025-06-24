<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class EventAttendanceRequest extends FormRequest
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
            'member_id' => ['required', 'exists:members,id'],
            'event_id' => 'required|exists:events,id',
            'attendance_date' => 'required|date',
            'attendance_time' => 'nullable',
            'notes' => 'nullable|string|max:500',
            'status' => ['required', Rule::in(['present', 'absent', 'excused'])],
            'attendance_type' => ['required', Rule::in(['in-person', 'online'])],
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
        // pick current date-time for attendance_time
        $attendance_time = date('Y-m-d H:i:s');

        // This sets variables before validation occurs.
        $this->merge([
            'created_by' => Auth::id(),
            'attendance_time' => $attendance_time
        ]);
    }

}
