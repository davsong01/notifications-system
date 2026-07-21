<?php

namespace DavidOghi\NotificationsSystem\Http\Controllers;

use DavidOghi\NotificationsSystem\Models\EmailProvider;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EmailProviderController extends Controller
{
    public function index(Request $request)
    {
        $model = config('david-oghi-notifications-system.models.email_provider', EmailProvider::class);

        $providers = $model::query()
            ->when($request->filled('name'), fn ($query) => $query->where('name', 'like', '%' . $request->name . '%'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest()
            ->paginate((int) config('david-oghi-notifications-system.dashboard.pagination', 25))
            ->withQueryString();

        return view('david-oghi-notifications-system::email-providers.index', [
            'providers' => $providers,
            'layout' => config('david-oghi-notifications-system.dashboard.layout'),
        ]);
    }

    public function create()
    {
        return view('david-oghi-notifications-system::email-providers.edit', [
            'provider' => null,
            'layout' => config('david-oghi-notifications-system.dashboard.layout'),
        ]);
    }

    public function store(Request $request)
    {
        $model = config('david-oghi-notifications-system.models.email_provider', EmailProvider::class);
        $model::query()->create($this->validated($request));

        return redirect()->route($this->routeName('email-providers.index'))->with('success', 'Email provider created.');
    }

    public function edit(int $id)
    {
        $model = config('david-oghi-notifications-system.models.email_provider', EmailProvider::class);

        return view('david-oghi-notifications-system::email-providers.edit', [
            'provider' => $model::query()->findOrFail($id),
            'layout' => config('david-oghi-notifications-system.dashboard.layout'),
        ]);
    }

    public function update(Request $request, int $id)
    {
        $model = config('david-oghi-notifications-system.models.email_provider', EmailProvider::class);
        $model::query()->findOrFail($id)->update($this->validated($request));

        return redirect()->route($this->routeName('email-providers.index'))->with('success', 'Email provider updated.');
    }

    public function destroy(int $id)
    {
        $model = config('david-oghi-notifications-system.models.email_provider', EmailProvider::class);
        $model::query()->findOrFail($id)->delete();

        return back()->with('success', 'Email provider deleted.');
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'MAIL_MAILER' => ['nullable', 'string', 'max:200'],
            'MAIL_HOST' => ['required', 'string', 'max:200'],
            'MAIL_PORT' => ['required', 'string', 'max:200'],
            'MAIL_USERNAME' => ['nullable', 'string', 'max:200'],
            'MAIL_PASSWORD' => ['nullable', 'string', 'max:200'],
            'MAIL_ENCRYPTION' => ['nullable', 'string', 'max:200'],
            'MAIL_FROM_ADDRESS' => ['required', 'email', 'max:200'],
            'MAIL_FROM_NAME' => ['required', 'string', 'max:200'],
            'MAIL_REPLY_TO_ADDRESS' => ['nullable', 'email', 'max:200'],
            'MAIL_REPLY_TO_NAME' => ['nullable', 'string', 'max:200'],
            'status' => ['required', 'in:active,inactive'],
        ]);
    }

    protected function routeName(string $name): string
    {
        return config('david-oghi-notifications-system.dashboard.route_name', 'david-oghi-notifications-system.') . $name;
    }
}
