<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Strip the leading "@" so the login matches the Telegram username.
     */
    protected function prepareForValidation(): void
    {
        if (is_string($this->input('telegram_login'))) {
            $this->merge([
                'telegram_login' => ltrim($this->input('telegram_login'), '@'),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', Rule::unique('users', 'email')->ignore($this->user())],
            'password' => ['sometimes', 'required', 'string', 'min:8'],
            'telegram_login' => ['sometimes', 'nullable', 'string', 'max:255', Rule::unique('users', 'telegram_login')->ignore($this->user())],
        ];
    }
}
