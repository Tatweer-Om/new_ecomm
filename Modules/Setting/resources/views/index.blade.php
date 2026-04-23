@extends('tenant_layouts.app')

@section('title', trans('setting::messages.view_setting_lang', [], session('locale')))

@section('content')

<main class="flex-1 container mx-auto px-4 sm:px-6 py-10" x-data="{ open: false }">
  <div class="max-w-4xl mx-auto">
    <!-- Page Title and Add Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-10">
      <h2 class="text-2xl sm:text-4xl font-bold text-[var(--text-primary)]">{{ trans('setting::messages.view_setting_lang', [], session('locale')) }}</h2>
       

    </div>

    <!-- Color Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-[var(--border-color)]">
      
       <!-- Tailwind + Alpine Tabs -->
        <div x-data="tabs()" x-init="init()" class="w-full max-w-3xl mx-auto">
            <!-- Tabs list -->
            <div role="tablist" aria-label="Sample tabs" class="flex gap-1 border-b">
                <button
                x-bind:class="active===0 ? 'border-b-2 border-pink-500 text-pink-600' : 'text-gray-600'"
                @click="activate(0)"
                @keydown.arrow-right.prevent="next()"
                @keydown.arrow-left.prevent="prev()"
                role="tab"
                :aria-selected="active===0"
                :tabindex="active===0 ? 0 : -1"
                class="px-4 py-3 bg-transparent text-sm font-medium focus:outline-none">
                    {{ trans('setting::messages.general_setting_lang', [], session('locale')) }}
                </button>

                <button
                x-bind:class="active===1 ? 'border-b-2 border-pink-500 text-pink-600' : 'text-gray-600'"
                @click="activate(1)"
                @keydown.arrow-right.prevent="next()"
                @keydown.arrow-left.prevent="prev()"
                role="tab"
                :aria-selected="active===1"
                :tabindex="active===1 ? 0 : -1"
                class="px-4 py-3 bg-transparent text-sm font-medium focus:outline-none">
                    {{ trans('setting::messages.sms_panel_lang', [], session('locale')) }}
                </button>

                {{-- <button
                x-bind:class="active===2 ? 'border-b-2 border-pink-500 text-pink-600' : 'text-gray-600'"
                @click="activate(2)"
                @keydown.arrow-right.prevent="next()"
                @keydown.arrow-left.prevent="prev()"
                role="tab"
                :aria-selected="active===2"
                :tabindex="active===2 ? 0 : -1"
                class="px-4 py-3 bg-transparent text-sm font-medium focus:outline-none">
                Tab 3
                </button> --}}
            </div>

            <!-- Panels -->
            <div class="mt-4">
                <div x-show="active===0" x-cloak role="tabpanel" aria-labelledby="tab-1" class="p-4 bg-white rounded shadow-sm">
                    <h4 class="font-semibold mb-2">{{ trans('setting::messages.general_setting_lang', [], session('locale')) }}</h4>
                    <form class="add_setting">
                        <input type="hidden" class="g_setting_id" name="id" value="{{ $settings->id }}">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-2" for="company_name">{{ trans('setting::messages.setting_company_name_lang', [], session('locale')) }}</label>
                                <input class="form-input w-full rounded-lg border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark focus:ring-primary focus:border-primary company_name" name="company_name" id="company_name" value="{{ $settings->name }}" type="text"/>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2" for="company_contact">{{ trans('setting::messages.setting_company_contact_lang', [], session('locale')) }}</label>
                                <input class="form-input w-full rounded-lg border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark focus:ring-primary focus:border-primary company_contact isnumber" name="company_contact" id="company_contact" value="{{ $settings->contact }}" type="text"/>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2" for="company_email">{{ trans('setting::messages.setting_company_email_lang', [], session('locale')) }}</label>
                                <input class="form-input w-full rounded-lg border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark focus:ring-primary focus:border-primary company_email" name="company_email" id="company_email" value="{{ $settings->email }}" type="text"/>
                            </div>
                        </div><br>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-2" for="cr_number">{{ trans('setting::messages.setting_cr_number_lang', [], session('locale')) }}</label>
                                <input class="form-input w-full rounded-lg border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark focus:ring-primary focus:border-primary cr_number" name="cr_number" id="cr_number" value="{{ $settings->cr_number }}" type="text"/>
                            </div>
                            <div>
                                <h3 class="mb-3 text-sm font-medium text-[#1b0e15]">{{ trans('setting::messages.setting_logo_lang', [], session('locale')) }}</h3>
                                <div class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 bg-white px-6 py-10 text-center hover:border-[#e64ca1]">
                                <span class="material-symbols-outlined text-5xl text-gray-400"> add_photo_alternate </span>
                                {{-- <p class="mt-4 text-sm font-semibold text-[#1b0e15]">اسحب وأفلت الصور هنا</p>
                                <p class="text-xs text-gray-500">أو</p> --}}
                                <label class="mt-2 cursor-pointer font-semibold text-[#e64ca1] hover:underline" for="file-upload">
                                    <span>{{ trans('messages.upload_image_lang', [], session('locale')) }}</span>
                                    <input class="sr-only" id="file-upload" name="image" type="file">
                                </label>
                                <p class="mt-2 text-xs text-gray-400">{{ trans('messages.image_restriction_lang', [], session('locale')) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 pt-6 border-t">
                            <button 
                                type="submit" 
                                class="w-full flex items-center justify-center gap-2 bg-[var(--primary-color)] text-white font-bold py-3 rounded-lg shadow hover:bg-[var(--primary-darker)] submit_form"
                            >
                                <span class="material-symbols-outlined">save</span>
                                <span>{{ trans('accessory::messages.save_lang', [], session('locale')) }}</span>
                            </button>
                        </div>
                    </form>
                </div>

                <div x-show="active===1" x-cloak role="tabpanel" aria-labelledby="tab-2" class="p-4 bg-white rounded shadow-sm">
                    <h4 class="font-semibold mb-2">{{ trans('setting::messages.sms_panel_lang', [], session('locale')) }}</h4>
                    <form class="add_sms_panel">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-2">{{ trans('setting::messages.setting_sms_status_lang', [], session('locale')) }}</label>
                                <select class="form-select w-full rounded-lg border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark focus:ring-primary focus:border-primary sms_status" required name="sms_status">
                                    <option value="">{{ trans('messages.select_lang', [], session('locale')) }}</option>
                                    <option value="1">{{ trans('setting::messages.booking_confirmation_lang', [], session('locale')) }}</option>
                                    <option value="2">{{ trans('setting::messages.dress_delivery_lang', [], session('locale')) }}</option>
                                    <option value="3">{{ trans('setting::messages.dress_return_lang', [], session('locale')) }}</option>
                                    <option value="4">{{ trans('setting::messages.dress_delivery_reminder_lang', [], session('locale')) }}</option>
                                    <option value="5">{{ trans('setting::messages.dress_return_reminder_lang', [], session('locale')) }}</option>
                                     
                                </select>
                                <br>
                                <br>
                                <label class="block text-md font-medium mb-2 text-[var(--primary-color)]">{{ trans('setting::messages.sms_variable_lang', [], session('locale')) }}</label>
                                <label class="block text-sm font-medium mb-2 sms_variable customer_name">{{ trans('customer::messages.customer_name_lang', [], session('locale')) }}</label>
                                <label class="block text-sm font-medium mb-2 sms_variable customer_contact">{{ trans('customer::messages.customer_contact_lang', [], session('locale')) }}</label>
                                <label class="block text-sm font-medium mb-2 sms_variable dress_name">{{ trans('dress::messages.dress_lang', [], session('locale')) }}</label>
                                <label class="block text-sm font-medium mb-2 sms_variable booking_no">{{ trans('booking::messages.booking_no_lang', [], session('locale')) }}</label>
                                <label class="block text-sm font-medium mb-2 sms_variable booking_date">{{ trans('booking::messages.booking_date_lang', [], session('locale')) }}</label>
                                <label class="block text-sm font-medium mb-2 sms_variable event_date">{{ trans('booking::messages.event_date_lang', [], session('locale')) }}</label>
                                <label class="block text-sm font-medium mb-2 sms_variable pickup_date">{{ trans('booking::messages.receive_date_lang', [], session('locale')) }}</label>
                                <label class="block text-sm font-medium mb-2 sms_variable return_date">{{ trans('booking::messages.return_date_lang', [], session('locale')) }}</label>
                                <label class="block text-sm font-medium mb-2 sms_variable bill_url">{{ trans('setting::messages.bill_url_lang', [], session('locale')) }}</label>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2" for="sms">{{ trans('setting::messages.setting_sms_lang', [], session('locale')) }}</label>
                                <textarea class="form-textarea w-full rounded-lg border-border-light bg-background-light focus:ring-primary focus:border-primary text-right sms_area" name="sms"   rows="6"></textarea>
                            </div>
                            
                        </div> 
                        <div class="mt-8 pt-6 border-t">
                            <button 
                                type="submit" 
                                class="w-full flex items-center justify-center gap-2 bg-[var(--primary-color)] text-white font-bold py-3 rounded-lg shadow hover:bg-[var(--primary-darker)] submit_form"
                            >
                                <span class="material-symbols-outlined">save</span>
                                <span>{{ trans('accessory::messages.save_lang', [], session('locale')) }}</span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- <div x-show="active===2" x-cloak role="tabpanel" aria-labelledby="tab-3" class="p-4 bg-white rounded shadow-sm">
                <h4 class="font-semibold mb-2">Content for Tab 3</h4>
                <p class="text-sm text-gray-600">This is the content for tab 3.</p>
                </div> --}}
            </div>
        </div>
 

      
    </div>
     


  </div>

 
 
</main>
@php
  $apiVersion = env('API_VERSION', 'v1'); // default v1
@endphp
<script>
  window.translations = {
    validate_company_name_lang: "{{ trans('setting::messages.validate_company_name_lang', [], session('locale')) }}",
    validate_company_name_lang: "{{ trans('setting::messages.validate_company_name_lang', [], session('locale')) }}",
    validate_company_email_lang: "{{ trans('setting::messages.validate_company_email_lang', [], session('locale')) }}",
    validate_cr_number_lang: "{{ trans('setting::messages.validate_cr_number_lang', [], session('locale')) }}",
    validate_sms_status_lang: "{{ trans('setting::messages.validate_sms_status_lang', [], session('locale')) }}",
    validate_sms_lang: "{{ trans('setting::messages.validate_sms_lang', [], session('locale')) }}",
    setting_update_success_lang: "{{ trans('setting::messages.setting_update_success_lang', [], session('locale')) }}",
    setting_add_success_lang: "{{ trans('setting::messages.setting_add_success_lang', [], session('locale')) }}",
    setting_update_failed_lang: "{{ trans('setting::messages.setting_update_failed_lang', [], session('locale')) }}",
    setting_add_failed_lang: "{{ trans('setting::messages.setting_add_failed_lang', [], session('locale')) }}",
    setting_not_found_lang: "{{ trans('setting::messages.setting_not_found_lang', [], session('locale')) }}",
    sure_lang: "{{ trans('messages.sure_lang', [], session('locale')) }}",
    delete_text_lang: "{{ trans('messages.delete_text_lang', [], session('locale')) }}",
    delete_it_lang: "{{ trans('messages.delete_it_lang', [], session('locale')) }}",
    delete_failed_lang: "{{ trans('messages.delete_failed_lang', [], session('locale')) }}",
    delete_success_lang: "{{ trans('messages.delete_success_lang', [], session('locale')) }}",
    safe_lang: "{{ trans('messages.safe_lang', [], session('locale')) }}",
    store: "{{ route($apiVersion.'.settings.store') }}", 
    store_sms_panel: "{{ route($apiVersion.'.settings.storeSmsPanel') }}", 
    get_sms_status: "{{ route($apiVersion.'.settings.getSmsStatus') }}", 
  };
</script>
@endsection
