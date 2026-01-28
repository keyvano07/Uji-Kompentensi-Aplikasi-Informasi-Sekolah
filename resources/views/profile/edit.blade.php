@extends('layouts.app')

@section('title', 'Profil Saya')
@section('header', 'Profil Saya')

@section('content')
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-sm border border-gray-100 p-6">
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name Input -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="focus:ring-bondowoso focus:border-bondowoso block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2.5 border px-3">
                    </div>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                            class="focus:ring-bondowoso focus:border-bondowoso block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2.5 border px-3">
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Role Display (Read Only) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <div class="relative rounded-md shadow-sm">
                    <input type="text" value="{{ ucfirst($user->role) }}" disabled
                        class="bg-gray-50 block w-full sm:text-sm border-gray-300 rounded-md py-2.5 border px-3 text-gray-500 cursor-not-allowed">
                </div>
                <p class="mt-1 text-xs text-gray-500">Role tidak dapat diubah.</p>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="bg-bondowoso hover:bg-green-800 text-white px-6 py-2.5 rounded-lg font-medium shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bondowoso">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
