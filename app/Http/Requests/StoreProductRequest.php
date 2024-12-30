<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:png,jpg,webp|max:4000',
            'category_id' => 'required|exists:categories,id',
            'menu_id' => 'required|exists:menus,id',
            'city_id' => 'required|exists:cities,id',
            'price' => 'required|numeric',
            'discount_price' => 'nullable|numeric',
            'size' => 'nullable|numeric',
            'quantity' => 'required|numeric',
            'best_seller' => 'nullable|boolean',
            'most_populer' => 'nullable|boolean',
        ];
    }
}
