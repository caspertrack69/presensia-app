@php
    $mobile = $mobile ?? false;
@endphp

<div class="flex h-full flex-col gap-5">
    <div class="rounded-3xl border border-slate-200 bg-white px-4 py-4 shadow-sm dark:border-slate-800 dark:bg-slate-900/80">
        <div class="flex items-center gap-3">
            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-600 text-lg font-semibold text-white shadow-lg shadow-blue-500/40">
                PR
            </span>
            <div class="flex flex-col">
                <p class="text-sm font-semibold tracking-tight text-slate-900 dark:text-slate-50">{{ config('app.name', 'Presensia') }}</p>
                <p class="text-xs font-medium uppercase tracking-wide text-blue-600 dark:text-blue-400">Smart Attendance</p>
            </div>
        </div>
        <div class="mt-4 rounded-2xl border border-slate-100/70 bg-slate-50/70 px-4 py-3 text-xs dark:border-slate-800/60 dark:bg-slate-950/40">
            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ \Illuminate\Support\Str::of($user->name)->limit(28) }}</p>
            <p class="mt-1 text-[11px] font-semibold uppercase tracking-wide text-blue-600 dark:text-blue-400">{{ $user->role }}</p>
            <p class="mt-2 text-xs leading-relaxed text-slate-500 dark:text-slate-400">Kelola absensi, cuti, dan insight kehadiran tim secara real-time.</p>
        </div>
    </div>

    @foreach ($navigationSections as $section => $items)
        <div class="space-y-2">
            <p class="px-3 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">{{ $section }}</p>
            <div class="space-y-1">
                @foreach ($items as $item)
                    <a href="{{ $item['href'] }}" @class([
                        'group relative flex items-center gap-3 rounded-2xl border px-3 py-2.5 text-sm font-medium transition-all duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2',
                        'border-transparent bg-blue-600 text-white shadow-lg shadow-blue-500/40 dark:bg-blue-500 dark:text-slate-950' => $item['active'],
                        'border-slate-100 text-slate-600 hover:border-blue-100 hover:bg-slate-100/70 hover:text-slate-800 dark:border-slate-800 dark:text-slate-300 dark:hover:border-slate-700 dark:hover:bg-slate-900/60 dark:hover:text-slate-100' => ! $item['active'],
                    ])>
                        <span @class([
                            'inline-flex h-9 w-9 items-center justify-center rounded-xl transition-all duration-150',
                            'bg-white/15 text-white' => $item['active'],
                            'border border-slate-200 bg-white/70 text-slate-600 group-hover:border-blue-200 group-hover:text-blue-600 dark:border-slate-700 dark:bg-slate-900/80 dark:text-slate-200 dark:group-hover:border-blue-400/50 dark:group-hover:text-blue-300' => ! $item['active'],
                        ])>
                            {!! $item['icon'] !!}
                        </span>
                        <div class="flex flex-1 items-center justify-between">
                            <span>{{ $item['label'] }}</span>
                            @if ($item['active'])
                                <span class="inline-flex h-2.5 w-2.5 shrink-0 rounded-full bg-white/90 dark:bg-slate-950/80"></span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endforeach

    <div class="mt-auto space-y-3">
        <button type="button" @click="toggleTheme()" class="flex w-full items-center justify-between rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 text-left text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:bg-slate-100 dark:border-slate-800 dark:bg-slate-900/70 dark:text-slate-200 dark:hover:border-slate-700 dark:hover:bg-slate-900/50">
            <div class="flex items-center gap-3 text-sm">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-600 transition group-hover:translate-y-px dark:bg-slate-800 dark:text-slate-200">
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
            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-rose-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-400 focus-visible:ring-offset-2">
                <svg class="h-4 w-4" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                </svg>
                Logout
            </button>
        </form>
    </div>
</div>
