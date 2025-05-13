<x-filament::page>
    <h2 class="text-xl font-bold mb-4">Tugas Saya</h2>
    
    <div class="space-y-4">
        @foreach ($this->tugas as $tugas)
            <div class="border p-4 rounded shadow">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-semibold">{{ $tugas->nama_tugas }}</h3>
                        <p class="text-sm text-gray-600">Deadline: {{ $tugas->deadline }}</p>
                        
                        @if($tugas->file)
                            <a href="{{ Storage::url($tugas->file) }}" target="_blank" class="text-blue-500 underline text-sm">
                                Lihat File
                            </a>
                        @endif
                    </div>
                    
                    <div>
                        <form wire:submit.prevent="updateStatus({{ $tugas->id }}, $event.target.status.value)">
                            <div class="flex items-center space-x-2">
                                <select name="status" class="text-sm rounded border-gray-300 py-1">
                                    <option value="menunggu" {{ $tugas->status == 'menunggu' ? 'selected' : '' }}>
                                        Belum Dikerjakan
                                    </option>
                                    <option value="proses" {{ $tugas->status == 'proses' ? 'selected' : '' }}>
                                        Sedang Dikerjakan
                                    </option>
                                    <option value="selesai" {{ $tugas->status == 'selesai' ? 'selected' : '' }}>
                                        Selesai
                                    </option>
                                </select>
                                <button type="submit" class="px-2 py-1 bg-blue-500 text-black rounded text-xs">
                                    Simpan
                                </button>
                            </div>
                        </form>
                        
                        <p class="text-sm mt-2 {{ $tugas->status == 'Selesai' ? 'text-green-600' : ($tugas->status == 'Sedang Dikerjakan' ? 'text-yellow-600' : 'text-red-600') }}">
                            Status Sekarang: {{ $tugas->status }}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <x-filament-actions::modals />
</x-filament::page>