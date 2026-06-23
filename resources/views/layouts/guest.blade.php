<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Login' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px) translateX(0px); }
            50% { transform: translateY(-14px) translateX(6px); }
        }

        @keyframes floatMedium {
            0%, 100% { transform: translateY(0px) translateX(0px); }
            50% { transform: translateY(12px) translateX(-8px); }
        }

        @keyframes pulseGlow {
            0%, 100% { opacity: .35; transform: scale(1); }
            50% { opacity: .6; transform: scale(1.08); }
        }

        @keyframes fadeUp {
            0% {
                opacity: 0;
                transform: translateY(24px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInSoft {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .animate-fade-up {
            animation: fadeUp .8s ease-out both;
        }

        .animate-fade-soft {
            animation: fadeInSoft 1.2s ease-out both;
        }

        .animate-float-slow {
            animation: floatSlow 7s ease-in-out infinite;
        }

        .animate-float-medium {
            animation: floatMedium 9s ease-in-out infinite;
        }

        .animate-pulse-glow {
            animation: pulseGlow 6s ease-in-out infinite;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }

        .bg-grid {
            background-image:
                linear-gradient(to right, rgba(255,255,255,0.06) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255,255,255,0.06) 1px, transparent 1px);
            background-size: 32px 32px;
        }
    </style>
</head>
<body class="min-h-screen overflow-x-hidden bg-slate-950 text-slate-800">
    @yield('content')
</body>
</html>