<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bondowoso</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .marquee-container {
            overflow: hidden;
            white-space: nowrap;
            position: absolute;
            bottom: 2rem;
            width: 80%;
        }
        .marquee {
            display: inline-block;
            animation: marquee 20s linear infinite;
        }
        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        /* Bondowoso Green */
        .bg-bondowoso { background-color: #064e3b; }
        .text-bondowoso { color: #064e3b; }
    </style>
</head>
<body class="h-screen w-full flex font-sans">
    
    <!-- Left Side (Green) -->
    <div class="w-1/2 bg-bondowoso flex flex-col justify-between p-12 text-white relative">
        <div class="z-10">
            <h1 class="text-3xl font-bold mb-8">Bondowoso</h1>
            <div class="mt-20">
                <h2 class="text-4xl font-bold leading-tight mb-4">
                    {{ \App\Models\Info::latest()->first()->text ?? 'Menolak lupa untuk generasi masa depan' }}
                </h2>
                <div class="w-16 h-1 bg-yellow-400"></div>
            </div>
        </div>

        <!-- Bus Illustration (CSS Shapes) -->
        <div class="relative z-10 w-full mb-10 opacity-80">
            <div class="w-full h-32 bg-white/10 rounded-xl border border-white/20 relative">
                <!-- Windows -->
                <div class="flex justify-around pt-6 px-4">
                    <div class="w-1/4 h-16 bg-white/20 rounded-md mx-1"></div>
                    <div class="w-1/4 h-16 bg-white/20 rounded-md mx-1"></div>
                    <div class="w-1/4 h-16 bg-white/20 rounded-md mx-1"></div>
                </div>
                <!-- Wheels -->
                <div class="absolute -bottom-5 left-10 w-12 h-12 bg-yellow-500 rounded-full"></div>
                <div class="absolute -bottom-5 right-10 w-12 h-12 bg-yellow-500 rounded-full"></div>
            </div>
            <!-- Road -->
            <div class="w-full h-1 bg-white/30 mt-4"></div>
        </div>

        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <!-- Add noise/pattern if needed -->
        </div>
    </div>

    <!-- Right Side (White) -->
    <div class="w-1/2 bg-white flex flex-col justify-center items-center p-12 relative overflow-hidden">
        
        <div class="w-full max-w-md z-10">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang Kembali!</h2>
            <p class="text-gray-500 mb-8">Masukkan detail Anda untuk melanjutkan.</p>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Nama Pengguna (Email)</label>
                    <input type="email" name="email" id="email" class="shadow-sm appearance-none border rounded w-full py-3 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-700 focus:border-transparent" placeholder="example@gmail.com" required>
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Kata Sandi</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" class="shadow-sm appearance-none border rounded w-full py-3 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-700 focus:border-transparent pr-10" placeholder="******" required>
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-green-700 focus:outline-none">
                            <!-- Eye Icon -->
                            <svg id="eye-icon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <!-- Eye Off Icon (Hidden by default) -->
                            <svg id="eye-off-icon" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.574-2.59M5.22 5.22a3 3 0 004.22 4.22m3.605-1.545a3 3 0 114.22 4.22M19.07 4.93L4.93 19.07" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex justify-end mt-2">
                        <a href="#" class="inline-block align-baseline font-bold text-sm text-yellow-600 hover:text-yellow-800">
                            Lupa kata sandi?
                        </a>
                    </div>
                </div>

                <script>
                    function togglePassword() {
                        const passwordInput = document.getElementById('password');
                        const eyeIcon = document.getElementById('eye-icon');
                        const eyeOffIcon = document.getElementById('eye-off-icon');

                        if (passwordInput.type === 'password') {
                            passwordInput.type = 'text';
                            eyeIcon.classList.add('hidden');
                            eyeOffIcon.classList.remove('hidden');
                        } else {
                            passwordInput.type = 'password';
                            eyeIcon.classList.remove('hidden');
                            eyeOffIcon.classList.add('hidden');
                        }
                    }
                </script>
                <div class="flex items-center justify-between">
                    <button class="bg-bondowoso hover:bg-green-900 text-white font-bold py-3 px-4 rounded w-full focus:outline-none focus:shadow-outline transition duration-300" type="submit">
                        Masuk
                    </button>
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('register') }}" class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800">
                        Belum punya akun? <span class="underline">Daftar gratis</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Running Text -->
        <div class="absolute bottom-0 w-full bg-gray-50 py-3 overflow-hidden border-t border-gray-100">
             <div class="marquee whitespace-nowrap text-bondowoso font-medium">
                @foreach(\App\Models\Info::latest()->get() as $info)
                    <span class="mx-8">{{ $info->text }}</span> •
                @endforeach
            </div>
        </div>
    </div>

</body>
</html>
