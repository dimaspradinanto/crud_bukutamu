<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Dashboard Antrian</h2>
    </x-slot>
    <div class="bg-blue-100 text-blue-800 p-4 rounded mb-4 font-semibold shadow">
        Nomor Terakhir Dipanggil: {{ $nomorSekarang }}
    </div>

    <!-- Modal Popup -->
    @if (session('status'))
    <div
        x-data="{ open: true }"
        x-show="open"
        class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full text-center">
            <h3 class="text-xl font-bold text-green-700 mb-4">✅ Login Berhasil</h3>
            <p class="text-gray-700">{{ session('status') }}</p>
            <button @click="open = false" class="mt-6 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Tutup
            </button>
        </div>
    </div>
    @endif


    <div class="grid p-4 grid-cols-1 mt-6 sm:grid-cols-2 gap-4 mb-6">
        <!-- Card 1: Total Antrian -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-700">Total Antrian</h2>
            <p class="mt-2 text-3xl font-bold text-blue-600">{{ $total }}</p>
        </div>

        <!-- Card 2: Sudah Dipanggil -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-700">Sudah Dipanggil</h2>
            <p class="mt-2 text-3xl font-bold text-green-600">{{ $dipanggil }}</p>
        </div>
    </div>
</x-app-layout>