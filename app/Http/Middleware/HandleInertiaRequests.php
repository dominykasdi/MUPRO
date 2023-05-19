<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => function () use ($request) {
                    if ($request->user()) {
                        return array_merge(
                            $request->user()->only('id', 'name', 'email'),
                            [
                                'roles' => $request->user()->roles->pluck('name')->toArray(),
                                'api_token' => $request->user()->api_token,
                            ]
                        );
                    }

                    return null;
                },
            ],
        ]);
    }
}
