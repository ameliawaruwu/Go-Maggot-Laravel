
<!-- {{-- Menerima variabel $item dari loop --}} -->

<div class="faq {{ $item['active'] ? 'active' : '' }}">
    <h3 class="fas-tittle">{{ $item['question'] }}</h3>
    <p class="faq-text">
        {{ $item['answer'] }}
    </p>

    <button class="faq-toggle">
        <i class="fas fa-chevron-down"></i>
        <i class="fas fa-times"></i>
    </button>
</div>