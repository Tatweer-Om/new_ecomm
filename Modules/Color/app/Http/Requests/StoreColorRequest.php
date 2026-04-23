<?php

namespace Modules\Color\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Color\Models\Color;

class StoreColorRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = Auth::guard('tenant')->user() ?: $this->user();

        return $user && Gate::forUser($user)->allows('create', Color::class);
    }

    public function rules(): array
    {
        return [
            'color_name'    => ['required', 'string', 'max:255'],
            'color_name_ar' => ['required', 'string', 'max:255'],
            'color_code'    => ['nullable', 'string', 'max:50'],
        ];
    }
}
