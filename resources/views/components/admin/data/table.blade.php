@props([
    'headers' => [],
])

<div class="bg-moon-dark/40 backdrop-blur-md rounded-2xl border border-white/5 overflow-hidden ring-1 ring-white/5 shadow-2xl">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-white/5 bg-white/5">
                    @foreach($headers as $header)
                        <th class="px-6 py-5 text-xs font-bold text-moon-gray-400 uppercase tracking-widest {{ $loop->first ? 'pl-8' : '' }} {{ $loop->last ? 'text-right pr-8' : '' }}">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5 text-sm">
                {{ $slot }}
            </tbody>
        </table>
    </div>
    
    @if(isset($pagination))
        <div class="px-6 py-4 border-t border-white/5 bg-white/[0.02]">
            {{ $pagination }}
        </div>
    @endif
</div>
