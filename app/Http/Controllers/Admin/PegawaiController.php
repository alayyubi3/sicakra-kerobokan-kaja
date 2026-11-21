<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PegawaiController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('nip', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('jabatan', 'like', "%{$keyword}%")
                    ->orWhere('bidang_seksi', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status_aktif', $request->status === 'aktif');
        }

        $pegawaiList = $query->withCount('laporanKinerjas')
            ->orderBy('role', 'asc')
            ->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.pegawai.index', compact('pegawaiList'));
    }

    public function create(): View
    {
        return view('admin.pegawai.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nip' => ['required', 'string', 'max:50', 'unique:users,nip'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'jabatan' => ['required', 'string', 'max:255'],
            'bidang_seksi' => ['required', 'string', 'max:255'],
            'role' => ['required', 'in:admin,staff'],
            'status_aktif' => ['boolean'],
        ], [
            'name.required' => 'Nama lengkap pegawai wajib diisi.',
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP sudah terdaftar dalam sistem.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar dalam sistem.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
            'jabatan.required' => 'Jabatan pegawai wajib diisi.',
            'bidang_seksi.required' => 'Bidang atau seksi wajib diisi.',
        ]);

        User::create([
            'name' => $validated['name'],
            'nip' => $validated['nip'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'jabatan' => $validated['jabatan'],
            'bidang_seksi' => $validated['bidang_seksi'],
            'role' => $validated['role'],
            'status_aktif' => $request->boolean('status_aktif', true),
        ]);

        return redirect()->route('admin.pegawai.index')
            ->with('success', 'Akun pegawai '.$validated['name'].' berhasil ditambahkan.');
    }

    public function edit(User $pegawai): View
    {
        return view('admin.pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, User $pegawai): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nip' => ['required', 'string', 'max:50', Rule::unique('users', 'nip')->ignore($pegawai->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($pegawai->id)],
            'jabatan' => ['required', 'string', 'max:255'],
            'bidang_seksi' => ['required', 'string', 'max:255'],
            'role' => ['required', 'in:admin,staff'],
            'status_aktif' => ['boolean'],
        ], [
            'nip.unique' => 'NIP sudah digunakan oleh akun lain.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
        ]);

        // Prevent admin deactivating or demoting themselves
        if ($pegawai->id === Auth::id()) {
            if (! $request->boolean('status_aktif')) {
                return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
            }
            if ($validated['role'] !== 'admin') {
                return back()->with('error', 'Anda tidak dapat mengubah peran akun Anda sendiri.');
            }
        }

        $pegawai->update([
            'name' => $validated['name'],
            'nip' => $validated['nip'],
            'email' => $validated['email'],
            'jabatan' => $validated['jabatan'],
            'bidang_seksi' => $validated['bidang_seksi'],
            'role' => $validated['role'],
            'status_aktif' => $request->boolean('status_aktif', true),
        ]);

        return redirect()->route('admin.pegawai.index')
            ->with('success', 'Data pegawai '.$pegawai->name.' berhasil diperbarui.');
    }

    public function resetPassword(Request $request, User $pegawai): RedirectResponse
    {
        $request->validate([
            'new_password' => ['required', 'string', 'min:6'],
        ], [
            'new_password.required' => 'Password baru harus diisi.',
            'new_password.min' => 'Password baru minimal 6 karakter.',
        ]);

        $pegawai->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password pegawai '.$pegawai->name.' berhasil direset.');
    }

    public function toggleStatus(User $pegawai): RedirectResponse
    {
        if ($pegawai->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $pegawai->update([
            'status_aktif' => ! $pegawai->status_aktif,
        ]);

        $statusStr = $pegawai->status_aktif ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun {$pegawai->name} berhasil {$statusStr}.");
    }

    public function destroy(User $pegawai): RedirectResponse
    {
        if ($pegawai->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $name = $pegawai->name;
        $pegawai->delete();

        return redirect()->route('admin.pegawai.index')
            ->with('success', "Data akun pegawai {$name} telah dihapus.");
    }
}
