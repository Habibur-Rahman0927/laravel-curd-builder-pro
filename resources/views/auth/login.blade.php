<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Login - Laravel CRUD Builder</title>
    <meta name="description" content="Login to the Laravel CRUD Builder - the ultimate tool for automated CRUD operations with flexible customization options.">
    <meta name="keywords" content="Laravel, CRUD, Builder, Login, Authentication, Admin, Automated, Database">
    <meta name="robots" content="index, follow">

    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets/css/custom-style-login-register.css') }}" rel="stylesheet">

    <!-- Include the style for the animation -->
    <style>
        body {
            margin: 0;
            overflow: hidden;
            font-family: 'Nunito', sans-serif;
            position: relative;
            color: #fff; /* White text color */
        }

        /* Background Gradient */
        .bg-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(ellipse at top, #151a33, #000);
            z-index: -1;
        }

        /* Add some style to the canvas */
        canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .container {
            position: relative;
            z-index: 10;
        }

        .logo {
            font-size: 2rem;
            font-weight: bold;
            color: #fff;
            text-align: center;
            margin-bottom: 20px;
        }

        .card {
            position: relative;
            z-index: 20;
            background-color: rgba(255, 255, 255, 0.2); /* Semi-transparent white */
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            color: white; /* Make all card text white */
        }

        .form-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }

        .form-control {
            padding-left: 40px;
            background-color: rgba(255, 255, 255, 0.3); /* Slight transparent input background */
            border: 1px solid #fff;
            color: #fff; /* Text color for input */
        }

        .form-check-label, .card-title, .form-check {
            color: #fff; /* Make labels and card text white */
        }

        .form-check-input {
            border: 1px solid #fff;
        }

        .btn-primary {
            background-color: #3498db;
            border: 1px solid #3498db;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }

        .d-flex a {
            color: #fff;
            text-decoration: none;
        }

        .d-flex a:hover {
            color: #3498db;
        }
    </style>
</head>

<body>

    <!-- Background Gradient and Animation -->
    <div class="bg-animation"></div>
    <canvas id="networkCanvas"></canvas>

    <div class="container">
        <a href="#" class="logo">Laravel CRUD Builder</a>
        <div class="card">
            <h5 class="card-title">Login to Your Account</h5>
            <p class="text-center small mb-4">Enter your email & password to login</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <!-- Email Address -->
                <div class="form-group">
                    <i class="bi bi-envelope input-icon"></i>
                    <label for="email" class="visually-hidden">Email</label>
                    <input id="email" class="form-control" type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus autocomplete="username">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <i class="bi bi-lock input-icon"></i>
                    <label for="password" class="visually-hidden">Password</label>
                    <input id="password" class="form-control" type="password" name="password" placeholder="Password" required autocomplete="current-password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="form-check mb-3">
                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                    <label class="form-check-label" for="remember_me">Remember me</label>
                </div>

                <button type="submit" class="btn btn-primary">Log in</button>

                <div class="d-flex justify-content-between mt-3">
                    @if (Route::has('password.request'))
                        <a class="text-decoration-none" href="{{ route('password.request') }}">Forgot your password?</a>
                    @endif
                    <a class="text-decoration-none" href="{{ route('register') }}">Don't have an account?</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Canvas Animation Script -->
    <script>
        const canvas = document.getElementById('networkCanvas');
        const ctx = canvas.getContext('2d');

        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        let nodes = [];
        const totalNodes = 80;
        const maxDistance = 150;
        const mouseRadius = 200;

        const mouse = { x: null, y: null };

        class Node {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.vx = (Math.random() - 0.5) * 0.7;
                this.vy = (Math.random() - 0.5) * 0.7;
                this.radius = Math.random() * 3 + 1;
            }

            move() {
                this.x += this.vx;
                this.y += this.vy;

                // Bounce on edges
                if (this.x <= 0 || this.x >= canvas.width) this.vx *= -1;
                if (this.y <= 0 || this.y >= canvas.height) this.vy *= -1;

                // Interact with mouse
                let dx = mouse.x - this.x;
                let dy = mouse.y - this.y;
                let distance = Math.sqrt(dx * dx + dy * dy);

                if (distance < mouseRadius && distance > 0) {
                    let angle = Math.atan2(dy, dx);
                    let moveFactor = (mouseRadius - distance) / mouseRadius;
                    this.x -= Math.cos(angle) * moveFactor * 2;
                    this.y -= Math.sin(angle) * moveFactor * 2;
                }
            }

            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
                ctx.fillStyle = '#ffffff';
                ctx.fill();
            }
        }

        function createNodes() {
            nodes = [];
            for (let i = 0; i < totalNodes; i++) {
                nodes.push(new Node());
            }
        }

        function drawLines() {
            for (let i = 0; i < totalNodes; i++) {
                for (let j = i + 1; j < totalNodes; j++) {
                    const dist = Math.hypot(nodes[i].x - nodes[j].x, nodes[i].y - nodes[j].y);
                    if (dist < maxDistance) {
                        ctx.strokeStyle = `rgba(255, 255, 255, ${1 - dist / maxDistance})`;
                        ctx.lineWidth = 1;
                        ctx.beginPath();
                        ctx.moveTo(nodes[i].x, nodes[i].y);
                        ctx.lineTo(nodes[j].x, nodes[j].y);
                        ctx.stroke();
                    }
                }
                // Line to mouse
                const mouseDist = Math.hypot(nodes[i].x - mouse.x, nodes[i].y - mouse.y);
                if (mouseDist < mouseRadius) {
                    ctx.strokeStyle = `rgba(255, 255, 255, ${1 - mouseDist / mouseRadius})`;
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    ctx.moveTo(nodes[i].x, nodes[i].y);
                    ctx.lineTo(mouse.x, mouse.y);
                    ctx.stroke();
                }
            }
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            nodes.forEach(node => {
                node.move();
                node.draw();
            });
            drawLines();
            requestAnimationFrame(animate);
        }

        window.addEventListener('mousemove', (e) => {
            mouse.x = e.clientX;
            mouse.y = e.clientY;
        });

        window.addEventListener('mouseout', () => {
            mouse.x = null;
            mouse.y = null;
        });

        window.addEventListener('resize', () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            createNodes(); // Reinitialize on resize for consistency
        });

        createNodes();
        animate();
    </script>

</body>

</html>
