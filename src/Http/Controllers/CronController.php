<?php

namespace DavidOghi\NotificationsSystem\Http\Controllers;

use DavidOghi\NotificationsSystem\Services\NotificationLogDispatcher;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CronController extends Controller
{
    public function __invoke(Request $request, NotificationLogDispatcher $dispatcher, ?string $token = null)
    {
        $configuredToken = config('david-oghi-notifications-system.cron.token');

        if (! $configuredToken || ! hash_equals((string) $configuredToken, (string) ($token ?: $request->query('token')))) {
            abort(403);
        }

        @set_time_limit((int) config('david-oghi-notifications-system.cron.time_limit', 300));

        $result = $dispatcher->process($request->integer('pick') ?: null);

        return response()->json($result);
    }
}
