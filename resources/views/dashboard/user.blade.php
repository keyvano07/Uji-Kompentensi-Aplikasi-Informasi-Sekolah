@extends('layouts.app')

@section('title', 'Dashboard User')

@section('header')
    <div class="flex items-center gap-3">
        Ringkasan Dashboard
        <span class="bg-gray-100 text-gray-500 px-3 py-1 rounded-full text-xs font-normal">Anda masuk sebagai pengguna</span>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Total Info Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col">
                <span class="text-sm font-medium text-gray-500 uppercase">Total Info</span>
                <span class="text-4xl font-bold text-gray-800 mt-2">{{ $info_count }}</span>
                <div class="flex items-center mt-4 text-green-600 text-sm font-medium">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    <span>+5% minggu ini</span>
                </div>
            </div>
        </div>

        <!-- System Status Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col">
                <span class="text-sm font-medium text-gray-500 uppercase">Aktif</span>
                <span class="text-4xl font-bold text-gray-800 mt-2">OK</span>
                <div class="flex items-center mt-4 text-green-600 text-sm font-medium">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Sistem berjalan</span>
                </div>
            </div>
        </div>
    </div>
@endsection
