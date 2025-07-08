<x-guest-layout>
    <div class="bg-gray-100 p-4 rounded shadow mb-4">
        <h2 class="text-lg font-bold">📋 Info Antrian</h2>
        <p>Total Antrian: {{ $total }}</p>
        <p>Sudah Dipanggil: {{ $dipanggil }}</p>
        <p class="mt-2 text-blue-700 font-semibold">Nomor Dipanggil Saat Ini: {{ $nomorSekarang }}</p>
    </div>
    <div class="text-center mt-10">
        <h1 class="text-2xl font-bold">Nomor Antrian Anda:</h1>
        <div class="text-6xl mt-4 font-mono">{{ $nomor }}</div>

        <a href="/" class="text-blue-600 mt-6 inline-block">Kembali</a>
    </div>
</x-guest-layout>