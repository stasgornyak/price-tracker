<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public const int PAGE_SIZE = 10;

    public function index(): View
    {
        $subscriptions = Subscription::query()
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(self::PAGE_SIZE);

        return view('subscriptions.index', compact('subscriptions'));
    }

    public function create(): View
    {
        $defaultEmail = auth()->user()->email;

        return view('subscriptions.create', compact('defaultEmail'));
    }

    public function store(StoreSubscriptionRequest $request): RedirectResponse
    {
        // TODO: Check if this is a real URL.

        $subscriptionExists = Subscription::query()
            ->where('user_id', auth()->id())
            ->where('url', $request->validated()['url'])
            ->exists();

        if ($subscriptionExists) {
            return back()
                ->withInput()
                ->with('error', 'Subscription already exists.');
        }

        Subscription::create($request->validated());

        return redirect()
            ->route('subscriptions.index')
            ->with('success', 'Subscription created.');
    }

    public function show(string $id): View|RedirectResponse
    {
        $subscription = Subscription::query()
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (! $subscription) {
            return redirect()
                ->route('subscriptions.index')
                ->with('error', 'Subscription not found.');
        }

        return view('subscriptions.show', compact('subscription'));
    }

    public function edit(string $id): RedirectResponse|View
    {
        $subscription = Subscription::query()
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (! $subscription) {
            return back()->with('error', 'Subscription not found.');
        }

        return view('subscriptions.edit', compact('subscription'));
    }

    public function update(UpdateSubscriptionRequest $request, string $id): RedirectResponse
    {
        $subscription = Subscription::query()
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (! $subscription) {
            return redirect()
                ->route('subscriptions.index')
                ->with('error', 'Subscription not found.');
        }

        $subscription->update($request->validated());

        return redirect()
            ->route('subscriptions.index')
            ->with('success', 'Subscription updated.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $subscription = Subscription::query()
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (! $subscription) {
            return redirect()
                ->route('subscriptions.index')
                ->with('error', 'Subscription not found.');
        }

        $subscription->delete();

        return redirect()
            ->route('subscriptions.index')
            ->with('success', 'Subscription deleted.');
    }
}
