<x-app-layout>
    <div class="p-6">

        <h1 class="text-xl font-bold mb-4">Daftar Antrian</h1>

        @php
        $sedangDipanggil = \App\Models\Antrian::where('is_current', true)->first();
        @endphp

        @if($sedangDipanggil)
        <div class="bg-green-100 p-4 rounded mb-4 shadow">
            <strong>Nomor sedang dipanggil:</strong>
            <span class="inline-block bg-white px-4 py-2 rounded shadow text-xl font-bold text-green-600">
                {{ $sedangDipanggil->nomor }}
            </span>
        </div>
        @else
        <div class="bg-yellow-100 p-4 rounded mb-4 shadow">Belum ada nomor yang sedang dipanggil.</div>
        @endif
        {{-- Tombol Reset --}}
        <form action="{{ route('antrian.reset') }}" method="POST" class="mb-4">
            @csrf
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
                onclick="return confirm('Yakin reset semua antrian?')">
                🔄 Reset Antrian
            </button>
        </form>

        <table class="table-auto w-full text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2">No</th>
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">Nomor Antrian</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($antrians as $antrian)
                <tr class="{{ $antrian->status === 'dipanggil' ? 'bg-green-100 font-bold' : '' }}">
                    <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="border px-4 py-2">{{ $antrian->nama }}</td>
                    <td class="border px-4 py-2">{{ $antrian->nomor }}</td>
                    <td class="border px-4 py-2">
                        @if($antrian->status === 'dipanggil')
                        <span class="text-green-600">Dipanggil</span>
                        @else
                        <span class="text-gray-600">Menunggu</span>
                        @endif
                    </td>
                    <td class="border px-4 py-2">
                        @if($antrian->status !== 'dipanggil')
                        <form action="{{ route('antrian.panggil', $antrian->id) }}" method="POST">
                            @csrf
                            <button class=" btn-panggil bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">
                                Panggil
                            </button>
                        </form>
                        @else
                        <span class="text-green-600">Sudah dipanggil</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4">Belum ada data antrian.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Loading overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="text-center">
            <svg class="animate-spin h-10 w-10 text-white mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
            </svg>
            <p class="text-white text-lg font-semibold">Memanggil nomor, mohon tunggu...</p>
        </div>
    </div>
    <!-- note refresh browser -->
    <div class="bg-white-100 p-4 rounded mb-4 shadow">
        <div class="p-6 text-gray-900">
            {{ __("!!Silahkan Refresh Browser Setelah Memanggil Nomor Antrian, Supaya Suara Terdengar ") }}
        </div>
    </div>

    <!-- Notifikasi Suara  -->
    @if(session('tts'))
    <audio id="dingSound" src="{{ asset('sounds/ding-dong.mp3') }}"></audio>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loadingOverlay = document.getElementById("loadingOverlay");
            const nomor = "{{ session('tts') }}";
            const text = "Nomor Antrian " + nomor + ". Silakan ke loket.";

            const disableButtons = (disabled) => {
                document.querySelectorAll('.btn-panggil').forEach(btn => {
                    btn.disabled = disabled;
                });
            };

            setTimeout(() => {
                loadingOverlay.classList.remove("hidden");
                disableButtons(true);

                if (window.speechSynthesis.speaking) {
                    window.speechSynthesis.cancel();
                }

                const ding = document.getElementById("dingSound");
                ding.play();

                ding.onended = function() {
                    setTimeout(() => {
                        if (window.speechSynthesis.speaking) {
                            window.speechSynthesis.cancel();
                        }

                        const checkAndSpeak = () => {
                            if (!window.speechSynthesis.speaking) {
                                const utter = new SpeechSynthesisUtterance(text);
                                utter.lang = 'id-ID';
                                utter.volume = 1;
                                utter.rate = 0.9;
                                utter.pitch = 1;

                                utter.onend = function() {
                                    loadingOverlay.classList.add("hidden");

                                    // ⏱️ Bonus: delay sebelum tombol aktif kembali
                                    setTimeout(() => {
                                        disableButtons(false);
                                    }, 2500);
                                    window.location.reload();
                                };

                                window.speechSynthesis.speak(utter);
                            } else {
                                setTimeout(checkAndSpeak, 100);
                            }
                        };

                        checkAndSpeak();
                    }, 300);
                };
            }, 500);
        });
    </script>
    @endif



</x-app-layout>
