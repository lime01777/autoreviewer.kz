<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'author_name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'text' => 'required|string|min:10',
            'pros' => 'nullable|string|max:500',
            'cons' => 'nullable|string|max:500',
            'author_email' => 'nullable|email|max:255',
            'author_phone' => 'nullable|string|max:20',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'author_name' => $this->author_name ? strip_tags($this->author_name) : null,
            'text' => $this->text ? strip_tags($this->text) : null,
            'pros' => $this->pros ? strip_tags($this->pros) : null,
            'cons' => $this->cons ? strip_tags($this->cons) : null,
        ]);
    }
}
