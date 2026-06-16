@extends('layouts.app')

@section('title', 'Create Account')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-white">
            Create Account
        </h1>

        <p class="text-white/50 text-sm mt-1">
            Add a funded, challenge or personal account.
        </p>
    </div>

    <div class="bg-surface-2 border border-white/[0.06] rounded-2xl p-6">

        <form action="{{ route('accounts.store') }}" method="POST">

            @csrf

            <div class="space-y-5">

                <div>
                    <label class="block text-sm text-white/60 mb-2">
                        Account Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white"
                        placeholder="5ers Funded 100K">
                </div>

                <div>
                    <label class="block text-sm text-white/60 mb-2">
                        Broker / Firm
                    </label>

                    <input
                        type="text"
                        name="broker"
                        class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white"
                        placeholder="The5ers">
                </div>

                <div>
                    <label class="block text-sm text-white/60 mb-2">
                        Account Type
                    </label>

                    <select
                        name="type"
                        class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white">

                        <option value="funded">Funded</option>
                        <option value="challenge">Challenge</option>
                        <option value="personal">Personal</option>

                    </select>
                </div>

                <div>
                    <label class="block text-sm text-white/60 mb-2">
                        Starting Balance
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        name="starting_balance"
                        class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white"
                        placeholder="100000">
                </div>

                <div class="flex gap-3 pt-2">

                    <button
                        type="submit"
                        class="bg-[#3D63FF] hover:bg-[#4d72ff] text-white px-5 py-3 rounded-xl text-sm font-medium transition">

                        Create Account

                    </button>

                    <a href="{{ route('accounts.index') }}"
                       class="border border-white/10 px-5 py-3 rounded-xl text-white/70 text-sm">

                        Cancel

                    </a>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection