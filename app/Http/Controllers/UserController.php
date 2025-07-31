<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search by name or email
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by role using Spatie's relation
        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        //shows all users with their roles and status
        $users = $query->with('roles')->orderBy('created_at', 'desc')->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name');
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'roles' => 'required|array',
            'status' => 'required|in:active,inactive',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status' => $request->status,
        ]);

        $user->assignRole($request->roles);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('roles');

        return view('users.show', [
            'user' => $user,
            
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //show a single user and assign roles
        $user = User::findOrFail($id);
        $roles = Role::pluck('name', 'name'); // ['admin' => 'admin', 'staff' => 'staff]
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //update user roles and status
        $request->validate([
            'roles' => 'required|array',
            'status' => 'required|in:active,inactive',
        ]);

        $user = User::findOrFail($id);

        // Sync roles
        $user->syncRoles($request->roles);

        // Update status
        $user->status = $request->status;
        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = !$user->status;
        $user->save();

        return back()->with('success', 'User status updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

}
