<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — NeverMC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        surface: { DEFAULT: '#0f0f10', 1: '#141416', 2: '#1a1a1c' },
                        accent: { DEFAULT: '#7c6aff', light: '#a99fff' },
                    },
                    fontFamily: { sans: ['"Inter"', 'system-ui', 'sans-serif'] },
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { background: #0f0f10; color: #e2e2e6; font-family: 'Inter', sans-serif; }
        input:focus { outline: none; border-color: rgba(124,106,255,0.5) !important; box-shadow: 0 0 0 3px rgba(124,106,255,0.1); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-surface">

    {{-- Subtle background grid --}}
    <div class="fixed inset-0 pointer-events-none" style="background-image: linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px); background-size: 40px 40px;"></div>

    <div class="relative w-full max-w-sm px-4">
        {{-- Logo --}}
        <div class="text-center mb-10">
            <span class="text-2xl font-semibold tracking-tight">
                <span class="text-accent">Never</span><span class="text-white">MC</span>
            </span>
            <p class="text-sm text-white/40 mt-1">Trading Journal</p>
        </div>

        @yield('content')
    </div>
</body>
</html>