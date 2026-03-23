<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'endpoint'    => 'required',
            'keys.auth'   => 'required',
            'keys.p256dh' => 'required'
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user) {
            $user->updatePushSubscription(
                $request->endpoint,
                $request->keys['p256dh'],
                $request->keys['auth']
            );
        }

        return response()->json(['success' => true]);
    }
}
