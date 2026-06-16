@extends('layouts.auth')
@section('title', 'Sign in')

@section('content')
<div>
    <h2 class="text-2xl font-bold tracking-tight text-white mb-1">Welcome back</h2>
    <p class="text-sm text-white/40 mb-6">Sign in to your NeverMC account</p>

    <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-medium text-white/50 mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full bg-white/[0.04] border border-white/[0.08] rounded-lg px-3.5 py-2.5 text-sm text-white placeholder-white/25 transition-colors focus:border-brand-500 focus:ring-1 focus:ring-brand-500 focus:outline-none"
                   placeholder="you@example.com">
        </div>

        <div>
            <label class="block text-xs font-medium text-white/50 mb-1.5">Password</label>
            <input type="password" name="password" required
                   class="w-full bg-white/[0.04] border border-white/[0.08] rounded-lg px-3.5 py-2.5 text-sm text-white placeholder-white/25 transition-colors focus:border-brand-500 focus:ring-1 focus:ring-brand-500 focus:outline-none"
                   placeholder="••••••••">
        </div>

        @if($errors->any())
        <p class="text-xs text-red-400">{{ $errors->first() }}</p>
        @endif

        <div class="flex items-center gap-2">
            <input type="checkbox" name="remember" id="remember" class="accent-brand-500">
            <label for="remember" class="text-xs text-white/40">Remember me</label>
        </div>

        <button type="submit"
                class="w-full bg-brand-500 hover:bg-brand-400 text-white font-medium text-sm py-2.5 rounded-lg transition-colors mt-2">
            Sign in
        </button>
    </form>

    <p class="text-xs text-white/30 text-center mt-6">
        No account?
        <a href="{{ route('register') }}" class="text-brand-400 hover:text-brand-300 transition-colors">Create one</a>
    </p>
</div>
@endsection