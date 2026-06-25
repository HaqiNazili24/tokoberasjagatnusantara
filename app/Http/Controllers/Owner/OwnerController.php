<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class OwnerController extends Controller
{
    /**
     * Tampilan Dashboard Owner
     */
    public function index()
    {
        $totalRevenue = Order::where('status', 'selesai')->sum('total');
        $totalOrders = Order::count();
        $recentOrders = Order::with(['user', 'courier'])->latest()->take(10)->get();
        $lowStockProducts = Product::where('stock', '<', 10)->get();
        $allProducts = Product::orderBy('stock', 'asc')->get();
        $estimatedProfit = $totalRevenue * 0.15; // Estimasi margin profit 15%

        // Count of users by role
        $countKaryawan = User::where('role', 'karyawan')->count();
        $countKurir = User::where('role', 'kurir')->count();
        $countCustomer = User::where('role', 'customer')->count();

        return view('owner.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'recentOrders',
            'lowStockProducts',
            'allProducts',
            'estimatedProfit',
            'countKaryawan',
            'countKurir',
            'countCustomer'
        ));
    }

    /**
     * Tampilan Daftar Akun Karyawan dan Kurir
     */
    public function usersIndex()
    {
        $users = User::whereIn('role', ['karyawan', 'kurir'])->latest()->get();
        return view('owner.users.index', compact('users'));
    }

    /**
     * Tampilan Form Tambah Akun Baru
     */
    public function usersCreate()
    {
        return view('owner.users.create');
    }

    /**
     * Simpan Akun Baru
     */
    public function usersStore(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'numeric', 'digits_between:10,15'],
            'role' => ['required', 'in:karyawan,kurir'],
            'password' => ['required', Password::min(6)],
        ]);

        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return redirect()->route('owner.users.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    /**
     * Tampilan Form Edit Akun
     */
    public function usersEdit(User $user)
    {
        if (!in_array($user->role, ['karyawan', 'kurir'])) {
            return redirect()->route('owner.users.index')->with('error', 'Akses ditolak.');
        }
        return view('owner.users.edit', compact('user'));
    }

    /**
     * Perbarui Akun
     */
    public function usersUpdate(Request $request, User $user)
    {
        if (!in_array($user->role, ['karyawan', 'kurir'])) {
            return redirect()->route('owner.users.index')->with('error', 'Akses ditolak.');
        }

        $rules = [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['required', 'numeric', 'digits_between:10,15'],
            'role' => ['required', 'in:karyawan,kurir'],
        ];

        if ($request->filled('password')) {
            $rules['password'] = [Password::min(6)];
        }

        $data = $request->validate($rules);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return redirect()->route('owner.users.index')->with('success', 'Akun berhasil diperbarui.');
    }

    /**
     * Hapus Akun
     */
    public function usersDestroy(User $user)
    {
        if (!in_array($user->role, ['karyawan', 'kurir'])) {
            return redirect()->route('owner.users.index')->with('error', 'Akses ditolak.');
        }

        $user->delete();
        return redirect()->route('owner.users.index')->with('success', 'Akun berhasil dihapus.');
    }

    /**
     * Tampilan Laporan Audit Log
     */
    public function auditLogs(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('action')) {
            $query->where('action_type', 'like', '%' . $request->action . '%');
        }

        $logs = $query->paginate(25);
        return view('owner.audit_logs.index', compact('logs'));
    }
}
