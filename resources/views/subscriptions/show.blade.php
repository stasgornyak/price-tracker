<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('subscriptions.index') }}"
           class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Subscriptions') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Show') }}</span>
    </div>

    <!-- Page Title -->
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Subscription details') }}</h1>
        <div class="flex gap-2">
            <x-buttons.link-button href="{{ route('subscriptions.edit', $subscription) }}">
                {{ __('Edit') }}
            </x-buttons.link-button>
            <form action="{{ route('subscriptions.destroy', $subscription) }}"
                  method="POST"
                  onsubmit="return confirm('{{ __('Are you sure?') }}')">
                @csrf
                @method('DELETE')
                <x-buttons.primary-button class="bg-red-600 hover:bg-red-700">
                    {{ __('Delete') }}
                </x-buttons.primary-button>
            </form>
        </div>
    </div>

    <div class="pt-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ __('General Information') }}</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">{{ __('Name') }}</span>
                                    <span class="text-gray-900">{{ $subscription->name }}</span>
                                </div>
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">{{ __('URL') }}</span>
                                    <a href="{{ $subscription->url }}" target="_blank" class="text-blue-600 hover:underline break-all">
                                        {{ $subscription->url }}
                                    </a>
                                </div>
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">{{ __('Email') }}</span>
                                    <span class="text-gray-900">{{ $subscription->email }}</span>
                                </div>
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">{{ __('Created At') }}</span>
                                    <span class="text-gray-900">{{ $subscription->created_at ? $subscription->created_at->format('d.m.Y H:i:s') : __('N/A') }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Status') }}</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">{{ __('Current Price') }}</span>
                                    <span class="text-lg font-bold text-gray-900">
                                        {{ $subscription->current_price ? number_format($subscription->current_price, 2, '.', ' ').' UAH' : __('N/A') }}
                                    </span>
                                </div>
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">{{ __('Last Checked At') }}</span>
                                    <span class="text-gray-900">
                                        {{ $subscription->price_checked_at ? $subscription->price_checked_at->format('d.m.Y H:i:s') : __('Never') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if($subscription->notes)
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-medium text-gray-900">{{ __('Notes') }}</h3>
                                <div class="mt-2 p-4 bg-gray-50 rounded-lg text-gray-700 whitespace-pre-wrap">{{ $subscription->notes }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
