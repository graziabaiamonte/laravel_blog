<section>
    <div class="section-header">
        <h2>Elimina account</h2>
        <p>Una volta eliminato l'account, tutti i dati saranno cancellati definitivamente. Prima di procedere, scarica eventuali dati che vuoi conservare.</p>
    </div>

    <x-button
        variant="danger"
        type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >Elimina account</x-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('admin.profile.destroy') }}" style="padding: 24px;">
            @csrf
            @method('delete')

            <h2 style="font-size: 1.125rem; font-weight: 600; margin: 0 0 8px 0;">
                Sei sicuro di voler eliminare il tuo account?
            </h2>

            <p class="muted" style="margin-bottom: 16px;">
                Tutti i dati saranno cancellati definitivamente. Inserisci la password per confermare.
            </p>

            <x-form-field
                name="password"
                type="password"
                placeholder="Password"
                errorBag="userDeletion"
            />

            <div class="form-actions" style="justify-content: flex-end;">
                <x-button variant="cancel" type="button" x-on:click="$dispatch('close')">
                    Annulla
                </x-button>

                <x-button variant="danger">
                    Elimina account
                </x-button>
            </div>
        </form>
    </x-modal>
</section>
