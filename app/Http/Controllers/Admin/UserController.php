<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = new User();
        return view('admin.users.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation

        $data = $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'phone_number' => 'nullable|string',
            ],
            [
                'name.required' => 'The name is required',
                'email.required' => 'The email is required',
                'email.email' => 'Please insert a valid email',
                'email.unique' => 'This email already exists',
                'password.required' => 'The password is required',

            ]
        );

        // Insert User
        $user = new User();
        $user->fill($data);
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'User added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Validation

        $data = $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:8',
                'phone_number' => 'nullable|string',
            ],
            [
                'name.required' => 'The name is required',
                'email.required' => 'The email is required',
                'email.email' => 'Please insert a valid email',
                'password.required' => 'The password is required',
            ]
        );

        // Update User
        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('messages', [
                [
                    'sender' => 'System',
                    'content' => 'User updated successfully.',
                    'timestamp' => now()
                ]
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
