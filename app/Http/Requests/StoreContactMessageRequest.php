<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
            // Honeypot: valódi látogató sosem tölti ki, mert a mező rejtett.
            'website' => ['nullable', 'prohibited'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Kérjük, add meg a neved.',
            'name.max' => 'A név legfeljebb 120 karakter lehet.',
            'email.required' => 'Kérjük, add meg az e-mail címed.',
            'email.email' => 'Ez nem tűnik érvényes e-mail címnek.',
            'email.max' => 'Az e-mail cím legfeljebb 255 karakter lehet.',
            'message.required' => 'Kérjük, írd le, miben segíthetünk.',
            'message.min' => 'Az üzenet legalább 10 karakter legyen.',
            'message.max' => 'Az üzenet legfeljebb 5000 karakter lehet.',
            'website.prohibited' => 'A beküldést nem tudtuk feldolgozni.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'név',
            'email' => 'e-mail cím',
            'message' => 'üzenet',
        ];
    }
}
