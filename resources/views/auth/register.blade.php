@extends('layouts.auth')
@section('title', 'Register')

@section('content')
<div class="bg-[#141416] border border-white/[0.07] rounded-xl p-8">
    <h2 class="text-lg font-semibold text-white mb-1">Create account</h2>
    <p class="text-sm text-white/40 mb-6">Join NeverMC Trading Journal</p>

    <form method="POST" action="{{ route('register.submit') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-medium text-white/50 mb-1.5">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="w-full bg-white/[0.04] border border-white/[0.08] rounded-lg px-3.5 py-2.5 text-sm text-white placeholder-white/25 transition-colors"
                   placeholder="Your name">
        </div>

        <div>
            <label class="block text-xs font-medium text-white/50 mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full bg-white/[0.04] border border-white/[0.08] rounded-lg px-3.5 py-2.5 text-sm text-white placeholder-white/25 transition-colors"
                   placeholder="you@example.com">
        </div>

        <div>
            <label class="block text-xs font-medium text-white/50 mb-1.5">Password</label>
            <input type="password" name="password" required
                   class="w-full bg-white/[0.04] border border-white/[0.08] rounded-lg px-3.5 py-2.5 text-sm text-white placeholder-white/25 transition-colors"
                   placeholder="••••••••">
        </div>

        <div>
            <label class="block text-xs font-medium text-white/50 mb-1.5">Confirm Password</label>
            <input type="password" name="password_confirmation" required
                   class="w-full bg-white/[0.04] border border-white/[0.08] rounded-lg px-3.5 py-2.5 text-sm text-white placeholder-white/25 transition-colors"
                   placeholder="••••••••">
        </div>

        @if($errors->any())
        <p class="text-xs text-red-400">{{ $errors->first() }}</p>
        @endif

        <button type="submit"
                class="w-full bg-accent hover:bg-accent-dark text-white font-medium text-sm py-2.5 rounded-lg transition-colors mt-2">
            Create account
        </button>
    </form>

    <p class="text-xs text-white/30 text-center mt-6">
        Already have an account?
        <a href="{{ route('login') }}" class="text-accent-light hover:text-accent transition-colors">Sign in</a>
    </p>
</div>
@endsection