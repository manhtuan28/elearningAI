<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class ActivityController extends Controller
{
    public function heartbeat(Request $request)
    {
        $user = auth()->user();
        if (!$user) return response()->json(['status' => 'guest']);

        $user->update([
            'last_seen_at' => now(),
            'total_learning_minutes' => $user->total_learning_minutes + 1
        ]);

        Cache::put('user-is-online-' . $user->id, true, now()->addMinutes(2));

        return response()->json(['status' => 'alive']);
    }
}