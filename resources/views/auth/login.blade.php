<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Vivra Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #FEF5E7 0%, #FAF3E0 100%);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
        }
        .login-card {
            background: linear-gradient(135deg, #FFFBF0 0%, #FFFAF4 100%);
            border: 1px solid #E8DCC8;
            box-shadow: 0 20px 60px rgba(139, 64, 14, 0.15);
        }
        .btn-login {
            background: linear-gradient(135deg, #D97706 0%, #B45309 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #B45309 0%, #92400E 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(139, 64, 14, 0.2);
        }
        .input-field {
            border: 1px solid #E8DCC8;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .input-field:focus {
            outline: none;
            border-color: #D97706;
            box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.1);
        }
    </style>
</head>
<body>
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="login-card rounded-2xl p-8 max-w-md w-full">
            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center text-white font-bold text-3xl mx-auto mb-4">V</div>
                <h1 class="text-3xl font-bold text-orange-600">Vivra</h1>
                <p class="text-sm text-gray-600 mt-2">Panel Administrativo</p>
            </div>

            <!-- Form -->
            <form action="/login" method="POST" class="space-y-4">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" class="input-field w-full" placeholder="admin@vivra.com" required>
                    @error('email')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Contraseña</label>
                    <input type="password" name="password" class="input-field w-full" placeholder="••••••••" required>
                    @error('password')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="remember" name="remember" class="w-4 h-4">
                    <label for="remember" class="text-sm text-gray-600">Recuérdame</label>
                </div>

                <!-- Login Button -->
                <button type="submit" class="btn-login mt-6">Iniciar Sesión</button>

                <!-- Error General -->
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        Credenciales inválidas
                    </div>
                @endif
            </form>

            <!-- Footer -->
            <div class="text-center mt-8 text-sm text-gray-600">
                <p>¿Olvidaste tu contraseña? <a href="#" class="text-orange-600 hover:text-orange-700 font-bold">Recuperar</a></p>
            </div>
        </div>
    </div>
</body>
</html>
