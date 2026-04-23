<?php

namespace Modules\Color\Http\Controllers\Web;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $this->authorizeForUser(Auth::guard('tenant')->user(), 'viewAny', Color::class);

        $colors = Color::query()->latest()->paginate(10);

        if ($request->ajax()) {
            return view('color::index', compact('colors'))->render();
        }

        return view('color::index', compact('colors'));
    }

    public function store(StoreColorRequest $request)
    {
        $user = Auth::guard('tenant')->user();
        $validated = $request->validated();

        $color = $this->colorService->create([
            'color_name'    => $validated['color_name'],
            'color_name_ar' => $validated['color_name_ar'],
            'color_code'    => $validated['color_code'] ?? null,
            'created_by'    => $user?->id,
        ]);

        return $this->successResponse(
            trans('color::messages.color_add_success_lang', [], session('locale')),
            new ColorResource($color),
            201
        );
    }

    public function show(Color $color)
    {
        $this->authorizeForUser(Auth::guard('tenant')->user(), 'view', $color);

        return $this->successResponse(
            'Color fetched successfully',
            new ColorResource($color)
        );
    }

    public function update(UpdateColorRequest $request, Color $color)
    {
        $validated = $request->validated();
        $user = Auth::guard('tenant')->user();

        $color = $this->colorService->update($color, [
            'color_name'    => $validated['color_name'],
            'color_name_ar' => $validated['color_name_ar'],
            'color_code'    => $validated['color_code'] ?? null,
            'updated_by'    => $user?->id,
        ]);

        return $this->successResponse(
            trans('color::messages.color_update_success_lang', [], session('locale')),
            new ColorResource($color)
        );
    }

    public function destroy(Request $request, Color $color)
    {
        $this->authorizeForUser(Auth::guard('tenant')->user(), 'delete', $color);

        $user = Auth::guard('tenant')->user();

        $this->colorService->softDelete($color, $user?->id);

        return $this->successResponse(
            trans('color::messages.delete_success_lang', [], session('locale'))
        );
    }

    public function restore(Request $request, int|string $id)
    {
        $this->authorizeForUser(Auth::guard('tenant')->user(), 'restore', Color::class);

        $user = Auth::guard('tenant')->user();

        $color = $this->colorService->restore($id, $user?->id);

        return $this->successResponse(
            trans('color::messages.color_restore_success_lang', [], session('locale')),
            new ColorResource($color)
        );
    }
}
