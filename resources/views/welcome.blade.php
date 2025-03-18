<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel CRUD Builder</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            body { margin: 0; overflow: hidden; font-family: 'Poppins', sans-serif; }
        </style>
    @endif

    <style>
        /* Global enhancements */
        h1, h2, p, a {
            transition: all 0.3s ease-in-out;
        }

        h1 {
            text-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
        }

        /* Button Hover */
        .btn-cta {
            background: linear-gradient(135deg, #7f5af0, #2cb67d);
            padding: 12px 28px;
            color: white;
            border-radius: 50px;
            font-size: 1.1rem;
            box-shadow: 0 8px 24px rgba(0,0,0,0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-cta:hover {
            background: linear-gradient(135deg, #2cb67d, #7f5af0);
            box-shadow: 0 8px 32px rgba(0,0,0,0.5);
            transform: translateY(-3px);
        }

        /* Card Enhancement */
        .glass-card {
            background: rgba(255, 255, 255, 0.12);
            border-radius: 20px;
            padding: 24px;
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
        }

        .glass-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.35);
        }

        /* Header links */
        .nav-link {
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 16px;
            border-radius: 12px;
            color: white;
            font-weight: 500;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        /* Footer Version */
        .version-info {
            margin-top: 30px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }
    </style>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">

</head>
<body class="min-h-screen flex items-center justify-center relative">

    <!-- Gradient Background -->
    <div class="absolute top-0 left-0 w-full h-full" style="background: radial-gradient(ellipse at top, #151a33, #000); z-index: 0;"></div>

    <!-- Canvas -->
    <canvas id="networkCanvas" class="absolute top-0 left-0 w-full h-full"></canvas>

    <!-- Transparent Header -->
    <header class="absolute top-0 left-0 w-full p-6 flex justify-end z-20">
        @if (Route::has('login'))
            <nav class="flex space-x-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="nav-link">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="nav-link">Register</a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    <!-- Centered Content -->
    <div class="relative z-10 text-center py-16 max-w-6xl mx-auto px-6">
        <h1 class="text-white text-6xl font-bold mb-8 leading-tight">Effortless Laravel CRUD Builder</h1>
        <p class="text-gray-300 text-xl mb-12">Supercharge your backend development with auto-generated APIs, customizable forms, and powerful relation management tools.</p>
        <a class="btn-cta" id="openModal">Get The Project</a>

        <!-- Feature Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-16">
            <div class="glass-card">
                <h2 class="text-white text-2xl mb-3">⚙️ Instant CRUD Generation</h2>
                <p class="text-gray-200">Auto-generate full CRUD (including API) with models, migrations, and more.</p>
            </div>
            <div class="glass-card">
                <h2 class="text-white text-2xl mb-3">📄 Smart Forms & Validation</h2>
                <p class="text-gray-200">Build forms with custom validation rules in seconds.</p>
            </div>
            <div class="glass-card">
                <h2 class="text-white text-2xl mb-3">📚 Auto API Documentation</h2>
                <p class="text-gray-200">Generate Swagger API docs automatically for easy testing.</p>
            </div>
            <div class="glass-card">
                <h2 class="text-white text-2xl mb-3">🔐 Roles & Permissions</h2>
                <p class="text-gray-200">Role-based user management out of the box.</p>
            </div>
            <div class="glass-card">
                <h2 class="text-white text-2xl mb-3">🔗 Relation Builder</h2>
                <p class="text-gray-200">Connect models visually and generate relations instantly.</p>
            </div>
            <div class="glass-card">
                <h2 class="text-white text-2xl mb-3">🚀 Pre-configured Packages</h2>
                <p class="text-gray-200">Essential packages configured for rapid development and localization support</p>
            </div>
        </div>

        <div class="version-info">CRUD Builder v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</div>
    </div>
    <div id="projectModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full text-center">
            <h2 class="text-xl font-semibold mb-4">Get The Project</h2>
            <p class="text-gray-600 mb-4">Enter your email, and we will send you the code link.</p>
            <input type="email" id="userEmail" class="w-full border p-2 rounded-md mb-4" placeholder="Your email" required>
            <button id="submitEmail" class="bg-blue-500 text-white px-4 py-2 rounded-md">Submit</button>
            <button id="closeModal" class="text-gray-500 mt-2 block">Close</button>
        </div>
    </div>


    <script>
        document.getElementById('openModal').addEventListener('click', function() {
            document.getElementById('projectModal').classList.remove('hidden');
        });

        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('projectModal').classList.add('hidden');
        });

        document.getElementById('submitEmail').addEventListener('click', function() {
            let email = document.getElementById('userEmail').value;
            if (email) {
                fetch('/project-request', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        email: email
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message); // Show success message
                    document.getElementById('projectModal').classList.add('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Something went wrong. Please try again later.');
                });
            } else {
                alert('Please enter a valid email address.');
            }
        });
    </script>
    <!-- Canvas Animation Script (unchanged from your version) -->
    <script>
        const canvas = document.getElementById('networkCanvas');
        const ctx = canvas.getContext('2d');

        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        let nodes = [];
        const totalNodes = 100;
        const maxDistance = 170;
        const mouseRadius = 250;

        const mouse = { x: null, y: null };

        class Node {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.vx = (Math.random() - 0.5) * 0.5;
                this.vy = (Math.random() - 0.5) * 0.5;
                this.radius = Math.random() * 3 + 1;
            }

            move() {
                this.x += this.vx;
                this.y += this.vy;
                if (this.x <= 0 || this.x >= canvas.width) this.vx *= -1;
                if (this.y <= 0 || this.y >= canvas.height) this.vy *= -1;
                const dx = mouse.x - this.x, dy = mouse.y - this.y, dist = Math.hypot(dx, dy);
                if (dist < mouseRadius) {
                    const angle = Math.atan2(dy, dx);
                    const move = (mouseRadius - dist) / mouseRadius;
                    this.x -= Math.cos(angle) * move * 1.5;
                    this.y -= Math.sin(angle) * move * 1.5;
                }
            }

            draw() { ctx.beginPath(); ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2); ctx.fillStyle = '#fff'; ctx.fill(); }
        }

        const createNodes = () => nodes = Array.from({length: totalNodes}, () => new Node());
        const animate = () => { ctx.clearRect(0, 0, canvas.width, canvas.height); nodes.forEach(n => (n.move(), n.draw())); requestAnimationFrame(animate); };
        window.addEventListener('resize', createNodes); createNodes(); animate();
    </script>

</body>
</html>
