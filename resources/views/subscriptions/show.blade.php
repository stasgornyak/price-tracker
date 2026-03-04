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
            <x-buttons.common-button href="{{ route('subscriptions.edit', $subscription) }}" :is-link="true" variant="primary">
                {{ __('Edit') }}
            </x-buttons.common-button>
            <form action="{{ route('subscriptions.destroy', $subscription) }}"
                  method="POST"
                  onsubmit="return confirm('{{ __('Are you sure?') }}')">
                @csrf
                @method('DELETE')
                <x-buttons.common-button variant="danger">
                    {{ __('Delete') }}
                </x-buttons.common-button>
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

                        <div class="md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Price History') }}</h3>
                            @if($priceHistory->isEmpty())
                                <p class="mt-2 text-gray-500">{{ __('No price history yet.') }}</p>
                            @else
                                <div class="mt-2 overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead>
                                            <tr>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">{{ __('Date') }}</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">{{ __('Price') }}</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">{{ __('Change') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach($priceHistory as $record)
                                                @php
                                                    $next = $priceHistory[$loop->index + 1] ?? null;
                                                    $diff = $next ? $record->price - $next->price : null;
                                                @endphp
                                                <tr>
                                                    <td class="px-4 py-2 text-sm text-gray-700">{{ $record->recorded_at->format('d.m.Y H:i') }}</td>
                                                    <td class="px-4 py-2 text-sm text-gray-900">{{ number_format($record->price, 2, '.', ' ') }} UAH</td>
                                                    <td class="px-4 py-2 text-sm">
                                                        @if($diff === null)
                                                            <span class="text-gray-400">—</span>
                                                        @elseif($diff > 0)
                                                            <span class="text-red-600">+{{ number_format($diff, 2, '.', ' ') }} UAH</span>
                                                        @else
                                                            <span class="text-green-600">{{ number_format($diff, 2, '.', ' ') }} UAH</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
