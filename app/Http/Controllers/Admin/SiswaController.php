<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'siswa');
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('nis', 'like', '%' . $request->search . '%');
            });
        }
        $siswas = $query->latest()->paginate(10)->withQueryString();
        return view('admin.siswa.index', compact('siswas'));
    }

    public function create()
    {
        return view('admin.siswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'nis'      => 'nullable|string|max:20',
            'kelas'    => 'nullable|string|max:50',
            'telepon'  => 'nullable|string|max:15',
            'alamat'   => 'nullable|string',
        ]);

        User::create([
            ...$request->except('password', 'password_confirmation'),
            'password' => Hash::make($request->password),
            'role'     => 'siswa',
        ]);

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil ditambahkan!');
    }

    public function edit(User $siswa)
    {
        return view('admin.siswa.edit', compact('siswa'));
    }

    public function update(Request $request, User $siswa)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $siswa->id,
            'password' => 'nullable|min:8|confirmed',
            'nis'      => 'nullable|string|max:20',
            'kelas'    => 'nullable|string|max:50',
            'telepon'  => 'nullable|string|max:15',
            'alamat'   => 'nullable|string',
        ]);

        $data = $request->except('password', 'password_confirmation');
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $siswa->update($data);
        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function destroy(User $siswa)
    {
        if ($siswa->peminjamans()->whereIn('status', ['menunggu', 'dipinjam'])->exists()) {
            return back()->with('error', 'Siswa masih memiliki peminjaman aktif!');
        }
        $siswa->delete();
        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil dihapus!');
    }
}
