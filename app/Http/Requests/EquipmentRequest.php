<?php

namespace App\Http\Requests;

use App\Models\Equipment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Server-side validation shared by "store" and "update".
 * On failure Laravel redirects back with the errors and the old input,
 * which is what lets the form keep the values the user typed.
 */
class EquipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // The routes already sit behind the "auth" middleware.
        return true;
    }

    public function rules(): array
    {
        return [
            'equipment_name' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(array_keys(Equipment::CATEGORIES))],
            // numeric rejects text, min:0 rejects negatives, decimal keeps it to cents.
            'unit_price' => ['required', 'numeric', 'min:0', 'max:99999999.99', 'decimal:0,2'],
            'description' => ['required', 'string', 'max:2000'],
            'availability_status' => ['required', Rule::in(array_keys(Equipment::STATUSES))],
        ];
    }

    public function messages(): array
    {
        return [
            'equipment_name.required' => 'Enter the equipment name.',
            'equipment_name.max' => 'The equipment name must be 255 characters or fewer.',

            'category.required' => 'Choose a category.',
            'category.in' => 'Category must be one of: paddles, balls, nets or accessories.',

            'unit_price.required' => 'Enter the unit price.',
            'unit_price.numeric' => 'Unit price must be a number, for example 450 or 450.50.',
            'unit_price.min' => 'Unit price cannot be negative.',
            'unit_price.max' => 'Unit price is too large.',
            'unit_price.decimal' => 'Unit price can have at most two decimal places.',

            'description.required' => 'Describe the equipment, including its key specifications.',
            'description.max' => 'The description must be 2000 characters or fewer.',

            'availability_status.required' => 'Choose an availability status.',
            'availability_status.in' => 'Availability must be either available or unavailable.',
        ];
    }
}
