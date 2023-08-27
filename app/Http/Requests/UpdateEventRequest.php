<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer'],
            'status' => ['required', 'boolean'],
            'type' => ['required', 'in:donation,subscriber,merch_sale']
        ];
    }
}
