<section>
    <div class="section-header">
        <h2>Informazioni profilo</h2>
        <p>Aggiorna le informazioni del tuo account e l'indirizzo email.</p>
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
            <p class="muted" style="margin-bottom: 16px;">
                La tua email non è verificata.
                <button form="send-verification" class="text-link" style="background:none; border:none; cursor:pointer; padding:0;">
                    Clicca qui per reinviare la mail di verifica.
                </button>
            </p>

            @if (session('status') === 'verification-link-sent')
                <x-alert type="success">
                    Un nuovo link di verifica è stato inviato alla tua email.
                </x-alert>
            @endif
        @endif

        <div class="form-actions" style="justify-content: flex-start;">
            <x-button variant="primary">Salva</x-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="form-status"
                >Salvato.</p>
            @endif
        </div>
    </form>
</section>
