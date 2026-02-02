@extends('layouts.app')

@section('title', 'Profil Pengguna')
@section('header', 'Profil Pengguna')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('users.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Daftar Pengguna
            </a>
        </div>

        <!-- Profile Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <!-- Header with Background -->
            <div class="bg-gradient-to-r from-green-900 to-green-700 h-32"></div>
            
            <!-- Profile Info -->
            <div class="px-8 pb-8">
                <!-- Avatar & Basic Info -->
                <div class="flex flex-col sm:flex-row items-center sm:items-end -mt-16 mb-6">
                    <div class="relative">
                        @if($user->profile && $user->profile->foto)
                            <img src="{{ asset('storage/' . $user->profile->foto) }}" 
                                 alt="{{ $user->name }}" 
                                 class="w-32 h-32 rounded-full border-4 border-white shadow-lg object-cover">
                        @else
                            <div class="w-32 h-32 rounded-full border-4 border-white shadow-lg bg-gray-200 flex items-center justify-center">
                                <span class="text-4xl font-bold text-gray-500">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-4 sm:mt-0 sm:ml-6 text-center sm:text-left flex-1">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                        <p class="text-gray-600">{{ $user->email }}</p>
                        
                        <div class="mt-3 flex flex-wrap gap-2 justify-center sm:justify-start">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                {{ $user->role === 'admin' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                                </svg>
                                {{ ucfirst($user->role) }}
                            </span>
                            
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <hr class="my-6 border-gray-100">

                <!-- Profile Details -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pengguna</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Nama</div>
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Email</div>
                                <div class="text-sm font-medium text-gray-900">{{ $user->email }}</div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Terdaftar Sejak</div>
                                <div class="text-sm font-medium text-gray-900">{{ $user->created_at->format('d F Y') }}</div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Terakhir Diperbarui</div>
                                <div class="text-sm font-medium text-gray-900">{{ $user->updated_at->format('d F Y, H:i') }}</div>
                            </div>
                        </div>
                    </div>

                    @if($user->profile)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Biografi</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            @if($user->profile->bio)
                                <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $user->profile->bio }}</p>
                            @else
                                <p class="text-sm text-gray-500 italic">Biografi belum diisi.</p>
                            @endif
                        </div>
                        
                        @if($user->profile->updated_at)
                        <div class="mt-2 text-xs text-gray-500">
                            Profil terakhir diperbarui: {{ \Carbon\Carbon::parse($user->profile->updated_at)->format('d F Y, H:i') }}
                        </div>
                        @endif
                    </div>
                    @else
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Biografi</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500 italic">Profil belum dibuat.</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
