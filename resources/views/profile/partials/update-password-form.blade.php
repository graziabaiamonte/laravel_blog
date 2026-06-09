<section>
    <div class="mb-6">
        <h2 class="mb-1 text-section font-semibold text-ink">Aggiorna password</h2>
        <p class="text-meta text-muted">Assicurati di usare una password lunga e casuale per maggiore sicurezza.</p>
    </div>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <x-form-field
            name="current_password"
            label="Password attuale"
            type="password"
            autocomplete="current-password"
            errorBag="updatePassword"
        />

        <x-form-field
            name="password"
            label="Nuova password"
            type="password"
            autocomplete="new-password"
            errorBag="updatePassword"
        />

        <x-form-field
            name="password_confirmation"
            label="Conferma password"
            type="password"
            autocomplete="new-password"
            errorBag="updatePassword"
        />

        <div class="mt-6 flex items-center gap-3">
            <x-button variant="primary">Salva</x-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-success"
                >Salvato.</p>
            @endif
        </div>
    </form>
</section>
