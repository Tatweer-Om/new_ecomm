@extends('tenant_layouts.app')

@section('title', trans('size::messages.view_size_lang', [], session('locale')))

@section('content')

<main class="flex-1 container mx-auto px-4 sm:px-6 py-10" x-data="{ open: false }">
  <div class="max-w-4xl mx-auto">
    <!-- Page Title and Add Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-10">
      <h2 class="text-2xl sm:text-4xl font-bold text-[var(--text-primary)]">{{ trans('size::messages.view_size_lang', [], session('locale')) }}</h2>
      <button 
          @click="$store.modals.size = true"
          class="flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-[var(--primary-color)] rounded-full shadow-lg hover:bg-[var(--primary-darker)] transition-transform hover:scale-105"
      >
          <span class="material-symbols-outlined text-base">add_circle</span>
          <span>{{ trans('size::messages.add_size_lang', [], session('locale')) }}</span>
      </button>

    </div>

    <!-- size Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-[var(--border-color)]">
      
      <div class="overflow-x-auto" id="data-table">
        <table class="w-full text-right text-sm">
          <thead class="bg-gray-50 border-b border-[var(--border-color)]">
            <tr>
              <th class="px-4 sm:px-6 py-4 font-bold text-[var(--text-secondary)]">{{ trans('size::messages.size_name_lang', [], session('locale')) }}</th>
              <th class="px-4 sm:px-6 py-4 font-bold text-[var(--text-secondary)]">{{ trans('size::messages.size_code_lang', [], session('locale')) }}</th>
              <th class="px-4 sm:px-6 py-4 font-bold text-[var(--text-secondary)]">{{ trans('messages.description_lang', [], session('locale')) }}</th>
              <th class="px-4 sm:px-6 py-4 font-bold text-[var(--text-secondary)] text-center">{{ trans('messages.action_lang', [], session('locale')) }}</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[var(--border-color)]">
            @forelse($sizes as $index => $size)
              <tr class="hover:bg-pink-50/50 transition-colors">
                <td class="px-4 sm:px-6 py-4 text-[var(--text-primary)]">{{ $size->size_name }}</td>
                <td class="px-4 sm:px-6 py-4 text-[var(--text-primary)]">{{ $size->size_code }}</td>
                <td class="px-4 sm:px-6 py-4 text-[var(--text-primary)]">{{ $size->description }}</td>
                <td class="px-4 sm:px-6 py-4 text-center">
                  <div class="flex items-center justify-center gap-4 sm:gap-6">
                    <button class="icon-btn" onclick="edit('{{ $size->id }}')"><span class="material-symbols-outlined">edit</span></button>
                    <button class="icon-btn hover:text-red-500" onclick="del('{{ $size->id }}')"><span class="material-symbols-outlined">delete</span></button>
                  </div>
                </td>
              </tr> 
              @empty
                <tr class="hover:bg-pink-50/50 transition-colors">
                  <td colspan="2" class="text-center px-4 sm:px-6 py-4 text-[var(--text-primary)]">{{ trans('size::messages.size_not_found_lang', [], session('locale')) }}</td>
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
          @if ($sizes->onFirstPage())
              <li class="disabled"><span>&laquo;</span></li>
          @else
              <li><a href="{{ $sizes->previousPageUrl() }}">&laquo;</a></li>
          @endif

          {{-- Page Numbers --}}
          @foreach ($sizes->getUrlRange(1, $sizes->lastPage()) as $page => $url)
              @if ($page == $sizes->currentPage())
                  <li class="active"><span>{{ $page }}</span></li>
              @else
                  <li><a href="{{ $url }}">{{ $page }}</a></li>
              @endif
          @endforeach

          {{-- Next --}}
          @if ($sizes->hasMorePages())
              <li><a href="{{ $sizes->nextPageUrl() }}">&raquo;</a></li>
          @else
              <li class="disabled"><span>&raquo;</span></li>
          @endif
      </ul>
  </div>


  </div>

 
<!-- Your size Modal -->
<div 
    x-data 
    x-show="$store.modals.size" 
    x-cloak 
    x-effect="if (!$store.modals.size) resetSizeForm()"
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
>
    <div 
        @click.away="$store.modals.size = false" 
        class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto p-6 sm:p-8"
    >
        <div class="flex justify-between items-start mb-6">
            <h1 class="text-xl sm:text-2xl font-bold">
                {{ trans('size::messages.add_size_lang', [], session('locale')) }}
            </h1>
            <button @click="$store.modals.size = false" class="text-gray-400 hover:text-gray-600">
                <span class="material-symbols-outlined text-3xl">close</span>
            </button>
        </div>

        <form class="add_size">
            <input type="hidden" class="size_id" name="size_id">
            <div class="space-y-6">
                <div>
                    <label class="block text-base font-medium mb-2">
                        {{ trans('size::messages.size_name_lang', [], session('locale')) }}
                    </label>
                    <input 
                        type="text" 
                        name="size_name"  
                        class="size_name w-full border rounded-lg p-3 focus:ring focus:ring-[var(--primary-color)]"
                    >
                </div>

                <div>
                    <label class="block text-base font-medium mb-2">
                        {{ trans('size::messages.size_name_ar_lang', [], session('locale')) }}
                    </label>
                    <input 
                        type="text" 
                        name="size_name_ar" 
                        class="size_name_ar w-full border rounded-lg p-3 focus:ring focus:ring-[var(--primary-color)]"
                    >
                </div> 

                <div>
                    <label class="block text-base font-medium mb-2">
                        {{ trans('size::messages.size_code_lang', [], session('locale')) }}
                    </label>
                    <input 
                        type="text" 
                        name="size_code" 
                        class="size_code w-full border rounded-lg p-3 focus:ring focus:ring-[var(--primary-color)]"
                    >
                </div> 
                <div>
                    <label class="block text-base font-medium mb-2">
                        {{ trans('messages.description_lang', [], session('locale')) }}
                    </label>
                    <textarea name="description" 
                        class="description w-full border rounded-lg p-3 focus:ring focus:ring-[var(--primary-color)]"></textarea>
                     
                </div> 
            </div>

            <div class="mt-8 pt-6 border-t">
                <button 
                    type="submit" 
                    class="w-full flex items-center justify-center gap-2 bg-[var(--primary-color)] text-white font-bold py-3 rounded-lg shadow hover:bg-[var(--primary-darker)] submit_form"
                >
                    <span class="material-symbols-outlined">save</span>
                    <span>{{ trans('size::messages.save_lang', [], session('locale')) }}</span>
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
    validate_size_name: "{{ trans('size::messages.validate_size_name', [], session('locale')) }}",
    validate_size_name_ar: "{{ trans('size::messages.validate_size_name_ar', [], session('locale')) }}",
    size_update_success_lang: "{{ trans('size::messages.size_update_success_lang', [], session('locale')) }}",
    size_add_success_lang: "{{ trans('size::messages.size_add_success_lang', [], session('locale')) }}",
    size_update_failed_lang: "{{ trans('size::messages.size_update_failed_lang', [], session('locale')) }}",
    size_add_failed_lang: "{{ trans('size::messages.size_add_failed_lang', [], session('locale')) }}",
    size_not_found_lang: "{{ trans('size::messages.size_not_found_lang', [], session('locale')) }}",
    sure_lang: "{{ trans('messages.sure_lang', [], session('locale')) }}",
    delete_text_lang: "{{ trans('messages.delete_text_lang', [], session('locale')) }}",
    delete_it_lang: "{{ trans('messages.delete_it_lang', [], session('locale')) }}",
    delete_failed_lang: "{{ trans('messages.delete_failed_lang', [], session('locale')) }}",
    delete_success_lang: "{{ trans('messages.delete_success_lang', [], session('locale')) }}",
    safe_lang: "{{ trans('messages.safe_lang', [], session('locale')) }}",
    store: "{{ route($apiVersion.'.sizes.store') }}",
    update: "{{ route($apiVersion.'.sizes.update', ':id') }}",
    edit: "{{ route($apiVersion.'.sizes.show', ':id') }}",
    delete: "{{ route($apiVersion.'.sizes.destroy', ':id') }}",  
  };
</script>
@endsection
