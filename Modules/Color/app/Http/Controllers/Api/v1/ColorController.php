<?php

namespace Modules\Color\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Modules\Color\Http\Requests\StoreColorRequest;
use Modules\Color\Http\Requests\UpdateColorRequest;
use Modules\Color\Http\Resources\ColorResource;
use Modules\Color\Models\Color;
use Modules\Color\Services\ColorService;

class ColorController extends BaseApiController
{
    public function __construct(
        protected ColorService $colorService
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Color::class);

        $colors = $this->colorService->getPaginated(
            perPage: (int) $request->integer('per_page', 10),
            page: (int) $request->integer('page', 1)
        );

        return $this->successResponse(
            'Colors fetched successfully',
            ColorResource::collection($colors->items()),
            200,
            [
                'current_page' => $colors->currentPage(),
                'last_page'    => $colors->lastPage(),
                'per_page'     => $colors->perPage(),
                'total'        => $colors->total(),
            ]
        );
    }

    public function store(StoreColorRequest $request)
    {
        $user = $request->user();
        $validated = $request->validated();

        $color = $this->colorService->create([
            'color_name'    => $validated['color_name'],
            'color_name_ar' => $validated['color_name_ar'],
            'color_code'    => $validated['color_code'] ?? null,
            'created_by'    => $user?->id,
        ]);

        return $this->successResponse(
            trans('color::messages.color_add_success_lang', [], app()->getLocale()),
            new ColorResource($color),
            201
        );
    }

    public function show(Color $color)
    {
        $this->authorize('view', $color);

        $color = $this->colorService->findById($color->getKey());

        return $this->successResponse(
            'Color fetched successfully',
            new ColorResource($color)
        );
    }

    public function update(UpdateColorRequest $request, Color $color)
    {
        $validated = $request->validated();
        $user = $request->user();

        $color = $this->colorService->update($color, [
            'color_name'    => $validated['color_name'],
            'color_name_ar' => $validated['color_name_ar'],
            'color_code'    => $validated['color_code'] ?? null,
            'updated_by'    => $user?->id,
        ]);

        return $this->successResponse(
            trans('color::messages.color_update_success_lang', [], app()->getLocale()),
            new ColorResource($color)
        );
    }

    public function destroy(Request $request, Color $color)
    {
        $this->authorize('delete', $color);

        $user = $request->user();

        $this->colorService->softDelete($color, $user?->id);

        return $this->successResponse(
            trans('color::messages.delete_success_lang', [], app()->getLocale())
        );
    }

    public function restore(Request $request, int|string $id)
    {
        $this->authorize('restore', Color::class);

        $user = $request->user();

        $color = $this->colorService->restore($id, $user?->id);

        return $this->successResponse(
            trans('color::messages.color_restore_success_lang', [], app()->getLocale()),
            new ColorResource($color)
        );
    }
}
