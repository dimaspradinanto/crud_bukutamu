<x-guest-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                {{ __("Selamat datang di Website Buku Tamu Kementrian Agama, Silahkan isi nama dan ambil nomor antrian .") }}
            </div>
        </div>
    </div>
    <div class="max-w-md mx-auto  p-6 bg-white shadow rounded">
        <h2 class="text-lg font-bold mb-4">Ambil Nomor Antrian</h2>
        @if (session('info'))
        <div class="bg-yellow-100 text-yellow-800 p-3 rounded mb-4">
            {{ session('info') }}
        </div>
        @endif

        <form action="{{ route('antrian.ambil') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block font-medium">Nama</label>
                <input type="text" name="nama" class="w-full border rounded px-3 py-2" required>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">
                Ambil Antrian
            </button>
        </form>
    </div>

</x-guest-layout>