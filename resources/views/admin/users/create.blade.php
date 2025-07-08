<x-app-layout>
    <div class="p-6 max-w-xl">
        <h2 class="text-2xl font-bold mb-4">Tambah User Baru</h2>

        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block mb-1">Nama</label>
                <input type="text" name="name" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Email</label>
                <input type="email" name="email" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Password</label>
                <input type="password" name="password" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Role</label>
                <select name="role" class="w-full border rounded p-2" required>
                    <option value="staff">Staff</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Simpan</button>

                <a href="{{ route('users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded inline-block">
                    Kembali
                </a>
            </div>
        </form>

    </div>
</x-app-layout>