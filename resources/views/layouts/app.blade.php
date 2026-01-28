<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Bondowoso</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .bg-bondowoso { background-color: #064e3b; }
        .text-bondowoso { color: #064e3b; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-800">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content -->
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
            <!-- Header -->
            <header class="bg-white border-b border-gray-200">
                <div class="px-6 py-4 flex justify-between items-center">
                    <div class="text-xl font-semibold text-gray-800">@yield('header')</div>
                    
                    <div class="flex items-center gap-4">
                        <div class="text-sm text-right">
                            <p class="font-medium text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-gray-500 text-xs">{{ ucfirst(Auth::user()->role) }}</p>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-bondowoso flex items-center justify-center text-white font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="w-full grow p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
