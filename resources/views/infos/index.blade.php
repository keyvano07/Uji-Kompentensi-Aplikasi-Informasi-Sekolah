@extends('layouts.app')

@section('title', 'Info')
@section('header', 'Info')

@section('content')
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 min-h-[500px]">
        
        <!-- Header & Search -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div class="flex items-center gap-4 w-full md:w-auto">
                <h3 class="text-lg font-bold text-gray-700">Info</h3>
                <form action="{{ route('infos.index') }}" method="GET" class="relative" id="searchForm">
                    <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Cari info.." 
                        class="pl-4 pr-10 py-1.5 bg-gray-100 border-none rounded-md text-sm focus:ring-1 focus:ring-green-500 w-64 text-gray-600 placeholder-gray-400">
                    <button type="submit" class="absolute right-2 top-1.5 text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </form>
            </div>
            
            @if(Auth::user()->role === 'admin')
            <button onclick="document.getElementById('addInfoModal').classList.remove('hidden')" class="text-sm font-bold text-gray-600 hover:text-bondowoso transition-colors">
                Tambah Info
            </button>
            @endif
        </div>

        <div class="mb-6">
            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">TEKS LOGIN BERJALAN</h4>
            <div id="runningTextPreview" class="p-3 bg-gray-50 rounded text-gray-600 text-sm">
                @if($infos->count() > 0)
                    {{ $infos->first()->text }}
                @else
                    Belum ada info.
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-16">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Teks</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-48">Dibuat</th>
                        @if(Auth::user()->role === 'admin')
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-32">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody id="infosTableBody" class="divide-y divide-gray-100">
                    @forelse($infos as $info)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-4 py-4 text-xs font-bold text-gray-500">#{{ $info->id }}</td>
                        <td class="px-4 py-4 text-sm text-gray-700">{{ $info->text }}</td>
                        <td class="px-4 py-4 text-xs text-gray-500">{{ $info->created_at->format('d/m/Y, H.i.s') }}</td>
                        @if(Auth::user()->role === 'admin')
                        <td class="px-4 py-4 text-xs font-medium">
                            <div class="flex gap-3">
                                <button onclick="openEditModal({{ $info->id }}, '{{ addslashes($info->text) }}')" class="text-gray-900 hover:text-bondowoso font-bold">Ubah</button>
                                <form action="{{ route('infos.destroy', $info->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus info ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-bold">Hapus</button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-10 text-center text-gray-500 text-sm">
                            Tidak ada data info ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ... Modals ... -->
    <!-- Add Modal -->
    <div id="addInfoModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center backdrop-blur-sm">
        <div class="relative p-5 border w-full max-w-md shadow-lg rounded-xl bg-white m-4">
            <div class="mb-5">
                <h3 class="text-lg font-bold text-gray-900">Tambah Info Baru</h3>
                <p class="text-sm text-gray-500">Masukkan teks informasi yang akan ditampilkan.</p>
            </div>
            <form action="{{ route('infos.store') }}" method="POST">
                @csrf
                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Teks Info</label>
                    <textarea name="text" rows="3" class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 resize-none" required placeholder="Contoh: Pengumuman penting..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('addInfoModal').classList.add('hidden')" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-bondowoso text-white rounded-lg hover:bg-green-800 transition-colors font-medium text-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editInfoModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center backdrop-blur-sm">
        <div class="relative p-5 border w-full max-w-md shadow-lg rounded-xl bg-white m-4">
            <div class="mb-5">
                <h3 class="text-lg font-bold text-gray-900">Ubah Info</h3>
                <p class="text-sm text-gray-500">Edit teks informasi yang sudah ada.</p>
            </div>
            <form id="editInfoForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Teks Info</label>
                    <textarea name="text" id="editInfoText" rows="3" class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 resize-none" required></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('editInfoModal').classList.add('hidden')" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-bondowoso text-white rounded-lg hover:bg-green-800 transition-colors font-medium text-sm">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, text) {
            document.getElementById('editInfoForm').action = '/infos/' + id;
            document.getElementById('editInfoText').value = text;
            document.getElementById('editInfoModal').classList.remove('hidden');
        }
        
        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                document.getElementById('addInfoModal').classList.add('hidden');
                document.getElementById('editInfoModal').classList.add('hidden');
            }
        });

        // Live Search
        const searchInput = document.getElementById('searchInput');
        const tableBody = document.getElementById('infosTableBody');
        const runningTextPreview = document.getElementById('runningTextPreview');
        let timeout = null;

        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            const query = this.value;

            timeout = setTimeout(() => {
                fetch(`{{ route('infos.index') }}?search=${query}`)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        
                        // Update Table
                        const newTableBody = doc.getElementById('infosTableBody');
                        if (newTableBody) {
                            tableBody.innerHTML = newTableBody.innerHTML;
                        }

                        // Update Running Text Preview
                        const newRunningText = doc.getElementById('runningTextPreview');
                        if (newRunningText) {
                            runningTextPreview.innerHTML = newRunningText.innerHTML;
                        }
                    })
                    .catch(error => console.error('Error fetching search results:', error));
            }, 300); // 300ms debounce
        });

        // Prevent form submit on enter for search (optional, or let it work as standard reload)
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Let JS handle it, or remove this to allow Enter to reload
        });
    </script>
@endsection
