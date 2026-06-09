<section>
    <div class="mb-6">
        <h2 class="mb-1 text-section font-semibold text-ink">Informazioni profilo</h2>
        <p class="text-meta text-muted">Aggiorna le informazioni del tuo account e l'indirizzo email.</p>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('admin.profile.update') }}">
        @csrf
        @method('patch')

        <x-form-field
            name="name"
            label="Nome"
            type="text"
            :value="$user->name"
            required
            autofocus
            autocomplete="name"
        />

        <x-form-field
            name="email"
            label="Email"
            type="email"
            :value="$user->email"
            required
            autocomplete="username"
        />

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <p class="mb-4 text-meta text-muted">
                La tua email non è verificata.
                <button form="send-verification" class="cursor-pointer border-0 bg-transparent p-0 text-sm text-muted underline">
                    Clicca qui per reinviare la mail di verifica.
                </button>
            </p>

            @if (session('status') === 'verification-link-sent')
                <x-alert type="success">
                    Un nuovo link di verifica è stato inviato alla tua email.
                </x-alert>
            @endif
        @endif

        <div class="mt-6 flex items-center gap-3">
            <x-button variant="primary">Salva</x-button>

            @if (session('status') === 'profile-updated')
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
