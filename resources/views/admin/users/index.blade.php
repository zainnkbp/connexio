@extends('layouts.app')

@section('title', 'Kelola Pengguna - Connexio')

@section('content')
<header class="mb-lg flex justify-between items-end border-b border-outline-variant/10 pb-4">
    <div>
        <p class="font-label-caps text-xs text-secondary uppercase tracking-widest mb-1">Manajemen Akses</p>
        <h1 class="text-3xl font-black text-primary tracking-tight">Kelola Pengguna</h1>
    </div>
    <div class="flex gap-2">
        <button onclick="openAddModal()" class="flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-lg text-sm font-bold shadow-md hover:bg-blue-800 hover:shadow-lg transition-all">
            <span class="material-symbols-outlined text-[18px]">person_add</span>
            Tambah Pengguna
        </button>
    </div>
</header>

<section class="glass-card rounded-xl overflow-hidden shadow-[0px_4px_20px_rgba(30,58,138,0.03)] border border-slate-100">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Nama Jelas</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Username</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $user)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-slate-800">{{ $user->nama_jelas }}</div>
                        </td>
                        <td class="px-6 py-4 font-mono text-sm font-bold text-primary">{{ $user->username }}</td>
                        <td class="px-6 py-4">
                            @if($user->role === 'Admin')
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">Admin</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-800">Teknisi</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right flex justify-end gap-2">
                            <button onclick="openEditModal('{{ $user->id_user }}', '{{ $user->nama_jelas }}', '{{ $user->username }}', '{{ $user->role }}')" class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition-colors" title="Edit">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                            </button>
                            @if($user->id_user !== Auth::id())
                            <form action="{{ route('admin.users.destroy', $user->id_user) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-full bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100 transition-colors" title="Hapus">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-slate-400 py-12 text-sm">
                            Belum ada data pengguna.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<!-- Modal Tambah Pengguna -->
<div id="modal-add" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-on-surface/20 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden animate-fade-in-up border border-outline-variant/30">
        <div class="px-6 py-4 border-b border-outline-variant/10 flex justify-between items-center bg-surface-container-lowest">
            <h3 class="text-lg font-bold text-primary">Tambah Pengguna Baru</h3>
            <button type="button" onclick="closeAddModal()" class="text-on-surface-variant hover:text-error transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('admin.users.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Nama Jelas</label>
                <input type="text" name="nama_jelas" required class="w-full rounded-lg border-outline-variant/50 focus:border-primary focus:ring-primary shadow-sm">
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Role</label>
                <select name="role" required class="w-full rounded-lg border-outline-variant/50 focus:border-primary focus:ring-primary shadow-sm">
                    <option value="Admin">Admin</option>
                    <option value="Teknisi" selected>Teknisi</option>
                </select>
            </div>

            <div class="mb-4">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider">Username</label>
                </div>
                <div class="flex gap-2">
                    <input type="text" id="add_username" name="username" required class="w-full rounded-lg border-outline-variant/50 focus:border-primary focus:ring-primary shadow-sm font-mono text-sm">
                    <button type="button" onclick="generateRandom('add_username')" class="px-3 bg-secondary/10 text-secondary rounded-lg hover:bg-secondary/20 transition-colors flex items-center justify-center" title="Generate Acak">
                        <span class="material-symbols-outlined text-[20px]">autorenew</span>
                    </button>
                </div>
            </div>

            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider">Password</label>
                </div>
                <div class="flex gap-2">
                    <input type="text" id="add_password" name="password" required minlength="6" class="w-full rounded-lg border-outline-variant/50 focus:border-primary focus:ring-primary shadow-sm font-mono text-sm">
                    <button type="button" onclick="generateRandomPassword('add_password')" class="px-3 bg-secondary/10 text-secondary rounded-lg hover:bg-secondary/20 transition-colors flex items-center justify-center" title="Generate Acak">
                        <span class="material-symbols-outlined text-[20px]">autorenew</span>
                    </button>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 rounded-lg text-sm font-bold text-on-surface-variant hover:bg-surface-variant transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2 rounded-lg text-sm font-bold bg-primary text-white hover:bg-blue-800 transition-colors shadow-md">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Pengguna -->
<div id="modal-edit" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-on-surface/20 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden animate-fade-in-up border border-outline-variant/30">
        <div class="px-6 py-4 border-b border-outline-variant/10 flex justify-between items-center bg-surface-container-lowest">
            <h3 class="text-lg font-bold text-primary">Edit Pengguna</h3>
            <button type="button" onclick="closeEditModal()" class="text-on-surface-variant hover:text-error transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="form-edit" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Nama Jelas</label>
                <input type="text" id="edit_nama_jelas" name="nama_jelas" required class="w-full rounded-lg border-outline-variant/50 focus:border-primary focus:ring-primary shadow-sm">
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Role</label>
                <select id="edit_role" name="role" required class="w-full rounded-lg border-outline-variant/50 focus:border-primary focus:ring-primary shadow-sm">
                    <option value="Admin">Admin</option>
                    <option value="Teknisi">Teknisi</option>
                </select>
            </div>

            <div class="mb-4">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider">Username</label>
                </div>
                <div class="flex gap-2">
                    <input type="text" id="edit_username" name="username" required class="w-full rounded-lg border-outline-variant/50 focus:border-primary focus:ring-primary shadow-sm font-mono text-sm">
                    <button type="button" onclick="generateRandom('edit_username')" class="px-3 bg-secondary/10 text-secondary rounded-lg hover:bg-secondary/20 transition-colors flex items-center justify-center" title="Generate Acak">
                        <span class="material-symbols-outlined text-[20px]">autorenew</span>
                    </button>
                </div>
            </div>

            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider">Password (Kosongkan jika tidak ingin mengubah)</label>
                </div>
                <div class="flex gap-2">
                    <input type="text" id="edit_password" name="password" minlength="6" class="w-full rounded-lg border-outline-variant/50 focus:border-primary focus:ring-primary shadow-sm font-mono text-sm" placeholder="***">
                    <button type="button" onclick="generateRandomPassword('edit_password')" class="px-3 bg-secondary/10 text-secondary rounded-lg hover:bg-secondary/20 transition-colors flex items-center justify-center" title="Generate Acak">
                        <span class="material-symbols-outlined text-[20px]">autorenew</span>
                    </button>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-lg text-sm font-bold text-on-surface-variant hover:bg-surface-variant transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2 rounded-lg text-sm font-bold bg-primary text-white hover:bg-blue-800 transition-colors shadow-md">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.3s ease-out forwards;
    }
</style>
<script>
    function openAddModal() {
        $('#modal-add').removeClass('hidden');
    }

    function closeAddModal() {
        $('#modal-add').addClass('hidden');
    }

    function openEditModal(id, nama, username, role) {
        $('#edit_nama_jelas').val(nama);
        $('#edit_username').val(username);
        $('#edit_role').val(role);
        $('#edit_password').val('');
        
        let url = "{{ route('admin.users.update', ':id') }}";
        url = url.replace(':id', id);
        $('#form-edit').attr('action', url);
        
        $('#modal-edit').removeClass('hidden');
    }

    function closeEditModal() {
        $('#modal-edit').addClass('hidden');
    }

    // Function to generate random username
    function generateRandom(inputId) {
        const chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        let result = 'user_';
        for (let i = 0; i < 5; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        $('#' + inputId).val(result);
    }

    // Function to generate random password
    function generateRandomPassword(inputId) {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$';
        let result = '';
        for (let i = 0; i < 8; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        $('#' + inputId).val(result);
    }
</script>
@endsection
