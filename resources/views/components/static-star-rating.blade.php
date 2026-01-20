@props(['rating' => 0])

<div class="flex text-moon-gold text-sm mb-1">
    @for($i = 1; $i <= 5; $i++)
        <span class="{{ $i <= $rating ? 'text-moon-gold' : 'text-gray-600' }}">★</span>
    @endfor
</div>
