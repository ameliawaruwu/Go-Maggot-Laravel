@props([
    'label' => 'Item', // Menambahkan default untuk menghindari error
    'value' => 'N/A'
])

<div class="spec-item p-1">
    <span class="spec-label fw-bold">{{ $label }}:</span>
    <span class="spec-value">{{ $value }}</span>
</div>