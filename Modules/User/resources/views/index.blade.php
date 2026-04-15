@extends('tenant_layouts.app')

@section('title', trans('user::messages.view_user_lang', [], session('locale')))

@section('content')

<main class="flex-1 container mx-auto px-4 sm:px-6 py-10" x-data="{ open: false }">
  <div class="max-w-4xl mx-auto">
    <!-- Page Title and Add Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-10">
      <h2 class="text-2xl sm:text-4xl font-bold text-[var(--text-primary)]">{{ trans('user::messages.view_user_lang', [], session('locale')) }}</h2>
      <button 
          @click="$store.modals.user = true"
          class="flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-[var(--primary-color)] rounded-full shadow-lg hover:bg-[var(--primary-darker)] transition-transform hover:scale-105"
      >
          <span class="material-symbols-outlined text-base">add_circle</span>
          <span>{{ trans('user::messages.add_user_lang', [], session('locale')) }}</span>
      </button>

    </div>

    <!-- user Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-[var(--border-color)]">
      
      <div class="overflow-x-auto" id="data-table">
        <table class="w-full text-right text-sm">
          <thead class="bg-gray-50 border-b border-[var(--border-color)]">
            <tr>
              <th class="px-4 sm:px-6 py-4 font-bold text-[var(--text-secondary)]">{{ trans('user::messages.user_name_lang', [], session('locale')) }}</th>
              <th class="px-4 sm:px-6 py-4 font-bold text-[var(--text-secondary)]">{{ trans('user::messages.user_contact_lang', [], session('locale')) }}</th>
              <th class="px-4 sm:px-6 py-4 font-bold text-[var(--text-secondary)]">{{ trans('user::messages.user_email_lang', [], session('locale')) }}</th>
              <th class="px-4 sm:px-6 py-4 font-bold text-[var(--text-secondary)] text-center">{{ trans('messages.action_lang', [], session('locale')) }}</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[var(--border-color)]">
            @forelse($users as $index => $user)
              <tr class="hover:bg-pink-50/50 transition-colors">
                <td class="px-4 sm:px-6 py-4 text-[var(--text-primary)]">{{ $user->name }}</td>
                <td class="px-4 sm:px-6 py-4 text-[var(--text-primary)]">{{ $user->contact }}</td>
                <td class="px-4 sm:px-6 py-4 text-[var(--text-primary)]">{{ $user->email }}</td>
                <td class="px-4 sm:px-6 py-4 text-center">
                  <div class="flex items-center justify-center gap-4 sm:gap-6">
                    <button class="icon-btn" onclick="edit('{{ $user->id }}')"><span class="material-symbols-outlined">edit</span></button>
                    <button class="icon-btn hover:text-red-500" onclick="del('{{ $user->id }}')"><span class="material-symbols-outlined">delete</span></button>
                  </div>
                </td>
              </tr> 
              @empty
                <tr class="hover:bg-pink-50/50 transition-colors">
                  <td colspan="2" class="text-center px-4 sm:px-6 py-4 text-[var(--text-primary)]">{{ trans('user::messages.user_not_found_lang', [], session('locale')) }}</td>
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
          @if ($users->onFirstPage())
              <li class="disabled"><span>&laquo;</span></li>
          @else
              <li><a href="{{ $users->previousPageUrl() }}">&laquo;</a></li>
          @endif

          {{-- Page Numbers --}}
          @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
              @if ($page == $users->currentPage())
                  <li class="active"><span>{{ $page }}</span></li>
              @else
                  <li><a href="{{ $url }}">{{ $page }}</a></li>
              @endif
          @endforeach

          {{-- Next --}}
          @if ($users->hasMorePages())
              <li><a href="{{ $users->nextPageUrl() }}">&raquo;</a></li>
          @else
              <li class="disabled"><span>&raquo;</span></li>
          @endif
      </ul>
  </div>


  </div>

 
<!-- Your user Modal -->
<div 
    x-data 
    x-show="$store.modals.user" 
    x-cloak 
    x-effect="if (!$store.modals.user) resetuserForm()"
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
>
    <div 
        @click.away="$store.modals.user = false" 
        class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto p-6 sm:p-8"
    >
        <div class="flex justify-between items-start mb-6">
            <h1 class="text-xl sm:text-2xl font-bold">
                {{ trans('user::messages.add_user_lang', [], session('locale')) }}
            </h1>
            <button @click="$store.modals.user = false" class="text-gray-400 hover:text-gray-600">
                <span class="material-symbols-outlined text-3xl">close</span>
            </button>
        </div>

        <form class="add_user">
            <input type="hidden" class="user_id" name="user_id">
            <div class="space-y-6">
                <div>
                    <label class="block text-base font-medium mb-2">
                        {{ trans('user::messages.user_name_lang', [], session('locale')) }}
                    </label>
                    <input 
                        type="text" 
                        name="name"  
                        class="name w-full border rounded-lg p-3 focus:ring focus:ring-[var(--primary-color)]"
                    >
                </div>

                <div>
                    <label class="block text-base font-medium mb-2">
                        {{ trans('user::messages.user_contact_lang', [], session('locale')) }}
                    </label>
                    <input 
                        type="text" 
                        name="contact" 
                        class="contact isnumber_wod w-full border rounded-lg p-3 focus:ring focus:ring-[var(--primary-color)]"
                    >
                </div> 

                <div>
                    <label class="block text-base font-medium mb-2">
                        {{ trans('user::messages.user_email_lang', [], session('locale')) }}
                    </label>
                    <input 
                        type="text" 
                        name="email" 
                        class="email w-full border rounded-lg p-3 focus:ring focus:ring-[var(--primary-color)]"
                    >
                </div> 

                <div>
                    <label class="block text-base font-medium mb-2">
                        {{ trans('user::messages.user_passwrod_lang', [], session('locale')) }}
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        class="password w-full border rounded-lg p-3 focus:ring focus:ring-[var(--primary-color)]"
                    >
                </div> 
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($permissions as $key => $permission)
                        <div class="flex items-center gap-2">
                            <input 
                                type="checkbox" 
                                name="{{ $key }}" 
                                value="1"
                                class="h-4 w-4 rounded border-gray-300 text-[#e64ca1] focus:ring-[#e64ca1] {{ $permission['class'] }}"
                            >

                            <label class="text-sm font-medium text-gray-700 flex items-center gap-1">
                                <i class="{{ $permission['icon'] }} text-gray-500"></i>
                                {{ trans('user::messages.'.$permission['class'].'_lang', [], session('locale')) }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-8 pt-6 border-t">
                <button 
                    type="submit" 
                    class="w-full flex items-center justify-center gap-2 bg-[var(--primary-color)] text-white font-bold py-3 rounded-lg shadow hover:bg-[var(--primary-darker)] submit_form"
                >
                    <span class="material-symbols-outlined">save</span>
                    <span>{{ trans('user::messages.save_lang', [], session('locale')) }}</span>
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
    validate_user_name_lang: "{{ trans('user::messages.validate_user_name_lang', [], session('locale')) }}",
    validate_user_contact_lang: "{{ trans('user::messages.validate_user_contact_lang', [], session('locale')) }}",
    validate_user_password_lang: "{{ trans('user::messages.validate_user_password_lang', [], session('locale')) }}",
    user_update_success_lang: "{{ trans('user::messages.user_update_success_lang', [], session('locale')) }}",
    user_add_success_lang: "{{ trans('user::messages.user_add_success_lang', [], session('locale')) }}",
    user_update_failed_lang: "{{ trans('user::messages.user_update_failed_lang', [], session('locale')) }}",
    user_add_failed_lang: "{{ trans('user::messages.user_add_failed_lang', [], session('locale')) }}",
    user_not_found_lang: "{{ trans('user::messages.user_not_found_lang', [], session('locale')) }}",
    sure_lang: "{{ trans('messages.sure_lang', [], session('locale')) }}",
    delete_text_lang: "{{ trans('messages.delete_text_lang', [], session('locale')) }}",
    delete_it_lang: "{{ trans('messages.delete_it_lang', [], session('locale')) }}",
    delete_failed_lang: "{{ trans('messages.delete_failed_lang', [], session('locale')) }}",
    delete_success_lang: "{{ trans('messages.delete_success_lang', [], session('locale')) }}",
    safe_lang: "{{ trans('messages.safe_lang', [], session('locale')) }}",
    store: "{{ route($apiVersion.'.users.store') }}",
    update: "{{ route($apiVersion.'.users.update', ':id') }}",
    edit: "{{ route($apiVersion.'.users.show', ':id') }}",
    delete: "{{ route($apiVersion.'.users.destroy', ':id') }}",  
  };
</script>
@endsection
