<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotebookFilterRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'sort' => 'nullable|string|in:default,price_asc,price_desc',
      'manufacturer' => 'nullable|string',
      'page' => 'nullable|integer|min:1',
      'min_price' => 'nullable|numeric|min:0',
      'max_price' => 'nullable|numeric|min:0',
      'screen_diagonal_from' => 'nullable|numeric|min:0|max:30',
      'screen_diagonal_to' => 'nullable|numeric|min:0|max:30',
    ];
  }
}
