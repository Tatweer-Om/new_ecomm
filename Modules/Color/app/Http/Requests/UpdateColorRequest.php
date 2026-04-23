<?php

namespace Modules\Color\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Color\Models\Color;

class UpdateColorRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = Auth::guard('tenant')->user() ?: $this->user();
        $color = $this->route('color');

        return $user && $color instanceof Color
            && Gate::forUser($user)->allows('update', $color);
    }

    public function rules(): array
    {
        return [
            'color_name'    => ['required', 'string', 'max:255'],
            'color_name_ar' => ['required', 'string', 'max:255'],
            'color_code'    => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'color_name.required'    => trans('color::messages.color_name_required_lang', [], session('locale')),
            'color_name.string'      => trans('color::messages.color_name_string_lang', [], session('locale')),
            'color_name.max'         => trans('color::messages.color_name_max_lang', [], session('locale')),

            'color_name_ar.required' => trans('color::messages.color_name_ar_required_lang', [], session('locale')),
            'color_name_ar.string'   => trans('color::messages.color_name_ar_string_lang', [], session('locale')),
            'color_name_ar.max'      => trans('color::messages.color_name_ar_max_lang', [], session('locale')),

            'color_code.string'      => trans('color::messages.color_code_string_lang', [], session('locale')),
            'color_code.max'         => trans('color::messages.color_code_max_lang', [], session('locale')),
        ];
    }
}
