<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(): View
    {
        $pending  = User::where('status', 'pending')->where('role', 'member')->orderBy('created_at', 'desc')->get();
        $approved = User::where('status', 'approved')->where('role', 'member')->orderBy('name')->get();
        $rejected = User::where('status', 'rejected')->where('role', 'member')->orderBy('updated_at', 'desc')->get();

        return view('admin.members.index', compact('pending', 'approved', 'rejected'));
    }

    public function approve(User $user): RedirectResponse
    {
        $user->update(['status' => 'approved']);

        return back()->with('success', "Akun {$user->name} telah disetujui.");
    }

    public function reject(User $user): RedirectResponse
    {
        $user->update(['status' => 'rejected']);

        return back()->with('success', "Akun {$user->name} telah ditolak.");
    }
}
