@extends('tenant_layouts.app')

@section('title', trans('color::messages.view_color_lang', [], session('locale')))

@section('content')

<main class="flex-1 container mx-auto px-4 sm:px-6 py-10" x-data="{ open: false }">
  <div class="max-w-4xl mx-auto">
    <!-- Page Title and Add Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-10">
      <h2 class="text-2xl sm:text-4xl font-bold text-[var(--text-primary)]">{{ trans('color::messages.view_color_lang', [], session('locale')) }}</h2>
      <button 
          @click="$store.modals.color = true"
          class="flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-[var(--primary-color)] rounded-full shadow-lg hover:bg-[var(--primary-darker)] transition-transform hover:scale-105"
      >
          <span class="material-symbols-outlined text-base">add_circle</span>
          <span>{{ trans('color::messages.add_color_lang', [], session('locale')) }}</span>
      </button>

    </div>

    <!-- Color Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-[var(--border-color)]">
      
      <div class="overflow-x-auto" id="data-table">
        <table class="w-full text-right text-sm">
          <thead class="bg-gray-50 border-b border-[var(--border-color)]">
            <tr>
              <th class="px-4 sm:px-6 py-4 font-bold text-[var(--text-secondary)]">{{ trans('color::messages.color_name_lang', [], session('locale')) }}</th>
              <th class="px-4 sm:px-6 py-4 font-bold text-[var(--text-secondary)]">{{ trans('color::messages.color_lang', [], session('locale')) }}</th>
              <th class="px-4 sm:px-6 py-4 font-bold text-[var(--text-secondary)] text-center">{{ trans('messages.action_lang', [], session('locale')) }}</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[var(--border-color)]">
            @forelse($colors as $index => $color)
              <tr class="hover:bg-pink-50/50 transition-colors">
                <td class="px-4 sm:px-6 py-4 text-[var(--text-primary)]">{{ $color->color_name }}</td>
                <td class="px-4 sm:px-6 py-4"><div class="w-8 h-8 rounded-lg shadow-inner" style="background:{{ $color->color_code }};"></div></td>
                <td class="px-4 sm:px-6 py-4 text-center">
                  <div class="flex items-center justify-center gap-4 sm:gap-6">
                    <button class="icon-btn" onclick="edit('{{ $color->id }}')"><span class="material-symbols-outlined">edit</span></button>
                    <button class="icon-btn hover:text-red-500" onclick="del('{{ $color->id }}')"><span class="material-symbols-outlined">delete</span></button>
                  </div>
                </td>
              </tr> 
              @empty
                <tr class="hover:bg-pink-50/50 transition-colors">
                  <td colspan="2" class="text-center px-4 sm:px-6 py-4 text-[var(--text-primary)]">{{ trans('color::messages.color_not_found_lang', [], session('locale')) }}</td>
                </tr>
              @endforelse
          </tbody>
        </table>
        <!-- Pagination -->
        
      </div>
      
    </div>
    
    <div id="data-pagination" class="d-flex justify-content-end mt-4">
      <ul class="dress_pagination">
          {{-- Previous --}}
          @if ($colors->onFirstPage())
              <li class="disabled"><span>&laquo;</span></li>
          @else
              <li><a href="{{ $colors->previousPageUrl() }}">&laquo;</a></li>
          @endif

          {{-- Page Numbers --}}
          @foreach ($colors->getUrlRange(1, $colors->lastPage()) as $page => $url)
              @if ($page == $colors->currentPage())
                  <li class="active"><span>{{ $page }}</span></li>
              @else
                  <li><a href="{{ $url }}">{{ $page }}</a></li>
              @endif
          @endforeach

          {{-- Next --}}
          @if ($colors->hasMorePages())
              <li><a href="{{ $colors->nextPageUrl() }}">&raquo;</a></li>
          @else
              <li class="disabled"><span>&raquo;</span></li>
          @endif
      </ul>
  </div>


  </div>

 
<!-- Your Color Modal -->
<div 
    x-data 
    x-show="$store.modals.color" 
    x-cloak 
    x-effect="if (!$store.modals.color) resetColorForm()"
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
>
    <div 
        @click.away="$store.modals.color = false" 
        class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 sm:p-8"
    >
        <div class="flex justify-between items-start mb-6">
            <h1 class="text-xl sm:text-2xl font-bold">
                {{ trans('color::messages.add_color_lang', [], session('locale')) }}
            </h1>
            <button @click="$store.modals.color = false" class="text-gray-400 hover:text-gray-600">
                <span class="material-symbols-outlined text-3xl">close</span>
            </button>
        </div>

        <form class="add_color">
            <input type="hidden" class="color_id" name="color_id">
            <div class="space-y-6">
                <div>
                    <label class="block text-base font-medium mb-2">
                        {{ trans('color::messages.color_name_lang', [], session('locale')) }}
                    </label>
                    <input 
                        type="text" 
                        name="color_name"  
                        class="color_name w-full border rounded-lg p-3 focus:ring focus:ring-[var(--primary-color)]"
                    >
                </div>

                <div>
                    <label class="block text-base font-medium mb-2">
                        {{ trans('color::messages.color_name_ar_lang', [], session('locale')) }}
                    </label>
                    <input 
                        type="text" 
                        name="color_name_ar" 
                        class="color_name_ar w-full border rounded-lg p-3 focus:ring focus:ring-[var(--primary-color)]"
                    >
                </div>

                <div>
                    <label class="block text-base font-medium mb-2">
                        {{ trans('color::messages.color_select_lang', [], session('locale')) }}
                    </label>
                    <input 
                        type="color" 
                        name="color_code" 
                        class="color_code w-16 h-10 rounded cursor-pointer border"
                    >
                    <span class="text-sm text-gray-500">
                        {{ trans('color::messages.color_click_square_choose_lang', [], session('locale')) }}
                    </span>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t">
                <button 
                    type="submit" 
                    class="w-full flex items-center justify-center gap-2 bg-[var(--primary-color)] text-white font-bold py-3 rounded-lg shadow hover:bg-[var(--primary-darker)] submit_form"
                >
                    <span class="material-symbols-outlined">save</span>
                    <span>{{ trans('color::messages.save_lang', [], session('locale')) }}</span>
                </button>
            </div>
        </form>
    </div>
</div>

</main>
@php
  $apiVersion = env('API_VERSION', 'v1'); // default v1
@endphp
<script>
  window.translations = {
    validate_color_name: "{{ trans('color::messages.validate_color_name', [], session('locale')) }}",
    validate_color_name_ar: "{{ trans('color::messages.validate_color_name_ar', [], session('locale')) }}",
    color_update_success_lang: "{{ trans('color::messages.color_update_success_lang', [], session('locale')) }}",
    color_add_success_lang: "{{ trans('color::messages.color_add_success_lang', [], session('locale')) }}",
    color_update_failed_lang: "{{ trans('color::messages.color_update_failed_lang', [], session('locale')) }}",
    color_add_failed_lang: "{{ trans('color::messages.color_add_failed_lang', [], session('locale')) }}",
    color_not_found_lang: "{{ trans('color::messages.color_not_found_lang', [], session('locale')) }}",
    sure_lang: "{{ trans('messages.sure_lang', [], session('locale')) }}",
    delete_text_lang: "{{ trans('messages.delete_text_lang', [], session('locale')) }}",
    delete_it_lang: "{{ trans('messages.delete_it_lang', [], session('locale')) }}",
    delete_failed_lang: "{{ trans('messages.delete_failed_lang', [], session('locale')) }}",
    delete_success_lang: "{{ trans('messages.delete_success_lang', [], session('locale')) }}",
    safe_lang: "{{ trans('messages.safe_lang', [], session('locale')) }}",
    store: "{{ route($apiVersion.'.colors.store') }}",
    update: "{{ route($apiVersion.'.colors.update', ':id') }}",
    edit: "{{ route($apiVersion.'.colors.show', ':id') }}",
    delete: "{{ route($apiVersion.'.colors.destroy', ':id') }}",  
  };
</script>
@endsection
