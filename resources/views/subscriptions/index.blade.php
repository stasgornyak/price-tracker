<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <span class="text-gray-500 dark:text-gray-400">{{ __('Subscriptions') }}</span>
    </div>

    <!-- Page Title -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Subscriptions') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Create, update and delete your subscriptions') }}</p>
    </div>

    <div class="pt-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-hidden overflow-x-auto p-6 bg-white border-b border-gray-200">
                    <div class="min-w-full align-middle">

                        @if(session()->has('success'))
                            <div class="mb-4">
                                <x-alert type="success">{{ session()->get('success') }}</x-alert>
                            </div>
                        @endif

                            @if(session()->has('error'))
                                <div class="mb-4">
                                    <x-alert type="error">{{ session()->get('error') }}</x-alert>
                                </div>
                            @endif

                        <x-buttons.link-button href="{{ route('subscriptions.create') }}"
                                       class="mb-4">{{ __('Add Subscription') }}</x-buttons.link-button>

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Name') }}</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Url') }}</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Email') }}</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Current price') }}</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Checked at') }}</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50">
                                </th>
                            </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                            @forelse($subscriptions as $subscription)
                                <tr class="bg-white">
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 align-top">
                                        <a href="{{ route('subscriptions.show', $subscription) }}" class="hover:underline">
                                            {{ $subscription->name }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 align-top">
                                        <a href="{{ $subscription->url }}" target="_blank" class="hover:underline">{{ $subscription->url }}</a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 align-top">
                                        {{ $subscription->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 align-top">
                                        {{ $subscription->current_price ? number_format($subscription->current_price, 2, '.', ' ').' UAH' : '–' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 align-top">
                                        {{ $subscription->price_checked_at ? $subscription->price_checked_at->format('d.m.Y H:i') : '–' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 flex flex-nowrap gap-2 align-top">
                                        <x-buttons.icon-link-button icon="fas-file-pen"
                                                            variant="primary"
                                                            href="{{ route('subscriptions.edit', $subscription) }}"/>

                                        <form action="{{ route('subscriptions.destroy', $subscription) }}"
                                              class="inline-block" method="POST"
                                              onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <x-buttons.icon-button icon="fas-trash" type="submit" variant="danger" />
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center">
                                        {{ __('No subscriptions found.') }}
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        <div class="mt-2">
                            {{ $subscriptions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>