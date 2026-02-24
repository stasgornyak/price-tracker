<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('subscriptions.index') }}"
           class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Subscriptions') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Edit') }}</span>
    </div>

    <!-- Page Title -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit subscription') }}</h1>
    </div>

    <div class="pt-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-hidden overflow-x-auto p-6 bg-white border-b border-gray-200">
                    <div class="min-w-full align-middle">

                        @if(session()->has('error'))
                            <div class="mb-4">
                                <x-alert type="error">{{ session()->get('error') }}</x-alert>
                            </div>
                        @endif

                        <form class="max-w-md" method="POST"
                              action="{{ route('subscriptions.update', ['subscription' => $subscription->id]) }}">
                            @csrf
                            @method('PUT')

                            <!-- Url -->
                            <div class="mb-4">
                                <x-forms.input :label="__('Name')" name="name" type="text" autofocus required
                                               value="{{ $subscription->name }}" />
                            </div>

                            <div class="mb-4">
                                <x-forms.input :label="__('Url')" name="url" type="text" disabled
                                               value="{{ $subscription->url }}" />
                            </div>

                            <div class="mb-4">
                                <x-forms.input :label="__('Email')" name="email" type="text" required
                                               value="{{ $subscription->email }}" />
                            </div>

                            <div class="mb-4">
                                <x-forms.area-input :label="__('Notes')" name="notes"
                                               value="{{ $subscription->notes }}" />
                            </div>

                            <div class="mt-4">
                                <x-buttons.primary-button>
                                    {{ __('Save') }}
                                </x-buttons.primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>