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
            'author_name'  => 'required|string|min:2|max:100',
            'rating'       => 'required|integer|min:1|max:5',
            'text'         => 'required|string|min:20|max:3000',
            'pros'         => 'nullable|string|max:600',
            'cons'         => 'nullable|string|max:600',
            'author_email' => 'nullable|email|max:255',
            'author_phone' => ['nullable', 'string', 'max:30', 'regex:/^[\+\d\s\(\)\-]+$/'],
            'agree_rules'  => 'accepted',
            // Honeypot — should always be empty
            'website_url'  => 'max:0',
        ];
    }

    public function messages(): array
    {
        return [
            'author_name.required'  => 'Укажите ваше имя или псевдоним.',
            'author_name.min'       => 'Имя должно быть не короче 2 символов.',
            'rating.required'       => 'Выберите оценку от 1 до 5 звёзд.',
            'rating.min'            => 'Минимальная оценка — 1 звезда.',
            'rating.max'            => 'Максимальная оценка — 5 звёзд.',
            'text.required'         => 'Напишите текст отзыва.',
            'text.min'              => 'Отзыв должен быть не короче 20 символов — расскажите подробнее.',
            'text.max'              => 'Отзыв слишком длинный (максимум 3000 символов).',
            'pros.max'              => 'Поле "Плюсы" слишком длинное (максимум 600 символов).',
            'cons.max'              => 'Поле "Минусы" слишком длинное (максимум 600 символов).',
            'author_email.email'    => 'Введите корректный адрес электронной почты.',
            'author_phone.regex'    => 'Телефон может содержать только цифры, +, пробелы, скобки и дефис.',
            'agree_rules.accepted'  => 'Необходимо принять правила публикации отзывов.',
            'website_url.max'       => 'Ошибка отправки формы.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Strip ALL HTML tags and encode special chars to prevent XSS
        $this->merge([
            'author_name'  => $this->author_name  ? htmlspecialchars(strip_tags(trim($this->author_name)),  ENT_QUOTES, 'UTF-8') : null,
            'text'         => $this->text         ? htmlspecialchars(strip_tags(trim($this->text)),         ENT_QUOTES, 'UTF-8') : null,
            'pros'         => $this->pros         ? htmlspecialchars(strip_tags(trim($this->pros)),         ENT_QUOTES, 'UTF-8') : null,
            'cons'         => $this->cons         ? htmlspecialchars(strip_tags(trim($this->cons)),         ENT_QUOTES, 'UTF-8') : null,
            'author_email' => $this->author_email ? trim($this->author_email) : null,
            'author_phone' => $this->author_phone ? trim($this->author_phone) : null,
        ]);
    }
}
