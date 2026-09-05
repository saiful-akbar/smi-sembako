@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden animate-gradient" style="background-size: 400% 400%;">
        
        <!-- Login Card -->
        <div class="max-w-md w-full space-y-8 relative z-10" x-data="loginForm()">
            <!-- Card Container -->
            <div class="bg-white/80 backdrop-blur-2xl rounded-3xl shadow-2xl shadow-indigo-500/20 p-10 border border-white/20 animate-scale-in">
                
                <!-- Logo & Title -->
                <div class="text-center mb-8">
                    <div class="relative inline-block mb-6">
                        <!-- Glow Effect -->
                        <div class="decorative absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-3xl blur-2xl opacity-50 animate-pulse-slow"></div>
                        
                        <!-- Logo -->
                        <img src="{{ logo() }}" alt="{{ config('app.name') }} Logo" class="w-30 mx-auto" loading="lazy" decoding="async">
                    </div>
                </div>

                <!-- Login Form -->
                <form class="space-y-6" action="{{ route('login') }}" method="POST">
                    @csrf
                    <input type="hidden" name="remember" value="true">
                    
                    <!-- Email Field -->
                    <div class="group">
                        <label for="email" class="block text-sm font-bold text-slate-700 mb-2 flex items-center gap-2">
                            <span class="material-icons text-indigo-600 text-lg">email</span>
                            Email Address
                        </label>
                        <div class="relative">
                            <input id="email" 
                                   name="email" 
                                   type="email" 
                                   autocomplete="email" 
                                   required
                                   x-model="email"
                                   class="appearance-none relative block w-full px-5 py-4 border-2 border-slate-200 placeholder-slate-400 text-slate-900 rounded-2xl focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 font-medium hover:border-indigo-300"
                                   placeholder="nama@email.com" 
                                   value="{{ old('email') }}">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <span class="material-icons text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                    alternate_email
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="group" x-data="{ showPassword: false }">
                        <label for="password" class="block text-sm font-bold text-slate-700 mb-2 flex items-center gap-2">
                            <span class="material-icons text-indigo-600 text-lg">lock</span>
                            Password
                        </label>
                        <div class="relative">
                            <input id="password" 
                                   name="password" 
                                   :type="showPassword ? 'text' : 'password'" 
                                   autocomplete="current-password" 
                                   required
                                   x-model="password"
                                   class="appearance-none relative block w-full px-5 py-4 border-2 border-slate-200 placeholder-slate-400 text-slate-900 rounded-2xl focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 font-medium hover:border-indigo-300"
                                   placeholder="••••••••">
                            <button type="button" 
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center hover:scale-110 transition-transform touch-manipulation">
                                <span class="material-icons text-slate-400 group-focus-within:text-indigo-600 transition-colors" 
                                      x-text="showPassword ? 'visibility_off' : 'visibility'">
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Error Message -->
                    @if ($errors->any())
                        <div class="bg-gradient-to-r from-red-50 to-pink-50 border-2 border-red-200 text-red-700 px-5 py-4 rounded-2xl relative animate-wiggle" role="alert">
                            <div class="flex items-center gap-3">
                                <span class="material-icons text-red-500 animate-pulse">error_outline</span>
                                <span class="block font-semibold">{{ $errors->first() }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                                class="group relative w-full flex justify-center items-center gap-3 py-4 px-6 border-0 text-base font-bold rounded-2xl text-white bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-500 hover:from-indigo-600 hover:via-purple-700 hover:to-pink-600 shadow-xl shadow-indigo-500/50 hover:shadow-2xl hover:shadow-indigo-500/60 focus:outline-none focus:ring-4 focus:ring-indigo-500/50 active:scale-95 transition-all duration-300 overflow-hidden touch-manipulation">
                            <!-- Shine Effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/30 to-white/0 translate-x-[-200%] group-hover:translate-x-[200%] transition-transform duration-700"></div>
                            
                            <span class="material-icons text-2xl animate-pulse-slow">login</span>
                            <span class="relative z-10">Masuk Sekarang</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function loginForm() {
            return {
                email: '{{ old('email') }}',
                password: '',
                fillForm(emailValue, passwordValue) {
                    this.email = emailValue;
                    this.password = passwordValue;
                    
                    // Visual feedback
                    const emailInput = document.getElementById('email');
                    const passwordInput = document.getElementById('password');
                    
                    if (emailInput) {
                        emailInput.classList.add('ring-4', 'ring-green-500/30', 'border-green-500');
                        setTimeout(() => {
                            emailInput.classList.remove('ring-4', 'ring-green-500/30', 'border-green-500');
                        }, 1000);
                    }
                    
                    if (passwordInput) {
                        passwordInput.classList.add('ring-4', 'ring-green-500/30', 'border-green-500');
                        setTimeout(() => {
                            passwordInput.classList.remove('ring-4', 'ring-green-500/30', 'border-green-500');
                        }, 1000);
                    }
                    
                    // Show success notification
                    console.log('✅ Demo user berhasil di-load:', emailValue);
                }
            }
        }
    </script>
@endsection
