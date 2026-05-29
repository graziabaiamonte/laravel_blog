@props([
    'action',
    'confirm' => 'Sei sicuro di voler eliminare questo elemento?',
    'label' => 'Elimina',
])

<form action="{{ $action }}" method="POST" onsubmit="return confirm('{{ $confirm }}');" style="display:inline;">
    @csrf
    @method('DELETE')
    <x-button variant="danger">{{ $label }}</x-button>
</form>
