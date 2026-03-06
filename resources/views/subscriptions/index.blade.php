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
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-hidden overflow-x-auto p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
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

                        <x-buttons.common-button href="{{ route('subscriptions.create') }}"
                                       variant="primary" :is-link="true"
                                       class="mb-4">{{ __('Add Subscription') }}</x-buttons.common-button>

                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Name') }}</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Url') }}</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Email') }}</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Current price') }}</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Checked at') }}</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700">
                                </th>
                            </tr>
                            </thead>

                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 divide-solid">
                            @forelse($subscriptions as $subscription)
                                <tr class="bg-white dark:bg-gray-800">
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 dark:text-gray-100 align-top">
                                        <a href="{{ route('subscriptions.show', $subscription) }}" class="hover:underline">
                                            {{ $subscription->name }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 dark:text-gray-100 align-top">
                                        <a href="{{ $subscription->url }}" target="_blank" class="hover:underline">{{ $subscription->url }}</a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 dark:text-gray-100 align-top">
                                        {{ $subscription->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 dark:text-gray-100 align-top">
                                        {{ $subscription->current_price ? number_format($subscription->current_price, 2, '.', ' ').' UAH' : '–' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 dark:text-gray-100 align-top">
                                        {{ $subscription->price_checked_at ? $subscription->price_checked_at->format('d.m.Y H:i') : '–' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 dark:text-gray-100 flex flex-nowrap gap-2 align-top">
                                        <x-tooltip text="{{ __('Edit') }}" position="top">
                                            <x-buttons.common-button icon="fas-file-pen" :is-link="true" variant="primary"
                                                href="{{ route('subscriptions.edit', $subscription) }}"/>
                                        </x-tooltip>

                                        <x-tooltip text="{{ __('Delete') }}" position="top">
                                            <x-buttons.common-button icon="fas-trash" type="button" variant="danger"
                                                x-on:click="$dispatch('open-modal', 'delete-subscription-{{ $subscription->id }}')" />
                                        </x-tooltip>

                                        <x-modal name="delete-subscription-{{ $subscription->id }}" max-width="md">
                                            <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                <div class="sm:flex sm:items-start">
                                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                                        @svg('fas-triangle-exclamation', 'h-6 w-6 text-red-600 dark:text-red-400')
                                                    </div>
                                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Delete Subscription') }}</h3>
                                                        <div class="mt-2">
                                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                                {{ __('Are you sure you want to delete this subscription? This action cannot be undone.') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                                                <form action="{{ route('subscriptions.destroy', $subscription) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-buttons.common-button icon="fas-trash" type="submit" variant="danger">
                                                        {{ __('Delete') }}
                                                    </x-buttons.common-button>
                                                </form>
                                                <x-buttons.common-button type="button" variant="secondary"
                                                    x-on:click="$dispatch('close-modal', 'delete-subscription-{{ $subscription->id }}')">
                                                    {{ __('Cancel') }}
                                                </x-buttons.common-button>
                                            </div>
                                        </x-modal>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
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
