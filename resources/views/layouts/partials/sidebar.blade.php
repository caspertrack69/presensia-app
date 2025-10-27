@php
    $mobile = $mobile ?? false;
@endphp

<div class="flex h-full flex-col gap-6">
    <div class="flex items-center gap-3">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-lg font-semibold text-white shadow-sm">
            PR
        </span>
        <div>
            <p class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ config('app.name', 'Presensia') }}</p>
            <p class="text-xs font-medium uppercase tracking-wide text-blue-600 dark:text-blue-400">Smart Attendance</p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white/80 p-4 shadow-sm backdrop-blur dark:border-slate-800 dark:bg-slate-900/70">
        <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ \Illuminate\Support\Str::of($user->name)->limit(28) }}</p>
        <p class="mt-1 text-xs font-medium uppercase tracking-wide text-blue-600 dark:text-blue-400">{{ $user->role }}</p>
        <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">Kelola absensi, cuti, dan insight kehadiran tim secara real-time.</p>
    </div>

    @foreach ($navigationSections as $section => $items)
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">{{ $section }}</p>
            <div class="mt-3 space-y-1.5">
                @foreach ($items as $item)
                    <a href="{{ $item['href'] }}" @class([
                        'flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2',
                        'bg-blue-600 text-white shadow-lg shadow-blue-500/30 dark:bg-blue-500 dark:text-slate-950' => $item['active'],
                        'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800/70' => ! $item['active'],
                    ])>
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white/70 text-slate-600 dark:border-slate-700 dark:bg-slate-900/80 dark:text-slate-200">
                            {!! $item['icon'] !!}
                        </span>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endforeach

    <div class="mt-auto space-y-3">
        <button type="button" @click="toggleTheme()" class="flex w-full items-center justify-between rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-left text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-800 dark:bg-slate-900/70 dark:text-slate-200 dark:hover:bg-slate-800">
            <div class="flex items-center gap-3">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-200">
                    <span x-show="theme === 'light'" x-cloak>
                        <svg class="h-5 w-5" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1.5M12 19.5V21M4.5 12H3M21 12h-1.5M5.47 5.47l1.06 1.06M17.47 17.47l1.06 1.06M18.53 5.47l-1.06 1.06M6.53 17.47l-1.06 1.06M12 7.5a4.5 4.5 0 100 9 4.5 4.5 0 000-9z" />
                        </svg>
                    </span>
                    <span x-show="theme === 'dark'" x-cloak>
                        <svg class="h-5 w-5" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.75A9.75 9.75 0 1111.25 3 7.5 7.5 0 0021 12.75z" />
                        </svg>
                    </span>
                </span>
                <div>
                    <p>Theme</p>
                    <p class="text-xs font-normal text-slate-500 dark:text-slate-400">Switch between light & dark</p>
                </div>
            </div>
            <span class="text-xs uppercase tracking-wide text-slate-400 dark:text-slate-500" x-text="theme === 'dark' ? 'Dark' : 'Light'"></span>
        </button>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-rose-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-600 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:ring-offset-2">
                <svg class="h-4 w-4" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                </svg>
                Logout
            </button>
        </form>
    </div>
</div>
