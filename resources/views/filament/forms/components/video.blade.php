@php
use Illuminate\Support\Facades\Storage;
    
@endphp
<div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}'), url: '{{ Storage::disk('remoto')->url('') }}' }" >
    <video controls x-bind:src="url+state"></video>
</div>