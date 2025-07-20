<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory & Asset Management</title>
    <link rel="stylesheet" href="{{ asset('css/welcome-styles.css') }}">
</head>
<body>
    <div class="hero">
        <div class="hero-content">
            <div class="hero-welcome">
                <h1>Inventory & Asset Management System</h1>
                <p>Efficiently track, manage, and organize your inventory and assets in one place.</p>
                
                
                <div class="hero-btns">
                    @if (Route::has('login'))
                    <div class="auth-links">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn">Log in</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn register">Register</a>
                            @endif
                        @endauth
                    </div>
                    @endif
                </div>
            </div>
            <div class="hero-image">
                <img src="{{ asset('images/Designer.jpeg') }}" alt="Inventory System">
            </div>
        </div>
    </div>
    
    <footer>
        <div class="footer-content">
            <p>&copy; {{ date('Y') }} School Inventory System. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>