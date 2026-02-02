@extends('layouts.app')

@section('title', 'Kelola Pengguna')
@section('header', 'Kelola Pengguna')

@section('content')
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 min-h-[500px]">
        
        <!-- Header & Search -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h3 class="text-lg font-bold text-gray-700 w-full md:w-auto">Daftar Pengguna</h3>
            
            <div class="flex items-center gap-4 w-full md:w-auto">
                <form action="{{ route('users.index') }}" method="GET" class="relative w-full md:w-auto">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pengguna..." 
                        class="pl-4 pr-10 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-green-500 w-full md:w-64 text-gray-600 placeholder-gray-400 focus:outline-none transition-shadow">
                    <button type="submit" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </form>
                
                <a href="{{ route('users.create') }}" class="px-4 py-2 bg-green-900 text-white text-sm font-medium rounded-lg hover:bg-green-800 transition-colors whitespace-nowrap">
                    Tambah User
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-16">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Nama Pengguna</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider w-40">Peran</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider w-32">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-xs font-bold text-gray-400">#{{ $user->id }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($user->profile && $user->profile->foto)
                                    <img src="{{ asset('storage/' . $user->profile->foto) }}" 
                                         alt="{{ $user->name }}" 
                                         class="flex-shrink-0 h-8 w-8 rounded-full object-cover mr-3">
                                @else
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-xs uppercase mr-3">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                                <a href="{{ route('users.show', $user->id) }}" class="text-sm font-medium text-gray-900 hover:text-green-800 transition-colors">
                                    {{ $user->name }}
                                </a>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <!-- Inline Role Edit -->
                            <form action="{{ route('users.update', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <!-- Keep existing name/email to pass validation if Controller requires it, 
                                     OR ideally update Controller to allow partial updates. 
                                     For now, we assume Controller needs name/email, so we send current ones as hidden. -->
                                <input type="hidden" name="name" value="{{ $user->name }}">
                                <input type="hidden" name="email" value="{{ $user->email }}">
                                
                                <select name="role" onchange="this.form.submit()" 
                                    class="text-xs font-medium rounded-full px-3 py-1 border-0 cursor-pointer focus:ring-0
                                    {{ $user->role === 'admin' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-xs font-medium text-green-600">Aktif</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($user->id !== Auth::id())
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus pengguna ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">Hapus</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500 text-sm">
                            Tidak ada pengguna ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
@endsection
