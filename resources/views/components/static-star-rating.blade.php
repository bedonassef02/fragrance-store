@props(['rating' => 0])

<div class="flex text-accent text-sm mb-1">
    @for($i = 1; $i <= 5; $i++)
        <span class="{{ $i <= $rating ? 'text-accent' : 'text-neutral-300' }}">★</span>
    @endfor
</div>
