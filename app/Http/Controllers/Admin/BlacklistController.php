<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class BlacklistController extends Controller
{
    /**
     * Display a listing of blocked users.
     */
    public function index()
    {
        $users = User::where('blocked', true)->paginate(10);
        return view('admin.blacklist.index', compact('users'));
    }

    /**
     * Toggle blocked status for a user.
     */
    public function toggle(User $user)
    {
        $user->blocked = !$user->blocked;
        $user->blocked_at = $user->blocked ? now() : null;
        $user->save();

        $message = $user->blocked 
            ? __('blacklist.blocked') 
            : __('blacklist.unblocked');

        return redirect()->route('admin.blacklist.index')
            ->with('messages', [
                [
                    'sender' => 'System',
                    'content' => $message,
                    'timestamp' => now()
                ]
            ]);
    }
}

