<?php

namespace Modules\Color\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ColorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'color_name'    => $this->color_name,
            'color_name_ar' => $this->color_name_ar,
            'color_code'    => $this->color_code,

            'created_by'    => $this->created_by,
            'updated_by'    => $this->updated_by,

            'created_at'    => $this->created_at?->toDateTimeString(),
            'updated_at'    => $this->updated_at?->toDateTimeString(),
        ];
    }
}