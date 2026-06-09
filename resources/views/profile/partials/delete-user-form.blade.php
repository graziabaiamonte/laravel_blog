<section>
    <div class="mb-6">
        <h2 class="mb-1 text-section font-semibold text-ink">Elimina account</h2>
        <p class="text-meta text-muted">Una volta eliminato l'account, tutti i dati saranno cancellati definitivamente. Prima di procedere, scarica eventuali dati che vuoi conservare.</p>
    </div>

    <x-button
        variant="danger"
        type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >Elimina account</x-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('admin.profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="mb-2 text-lg font-semibold text-ink">
                Sei sicuro di voler eliminare il tuo account?
            </h2>

            <p class="mb-4 text-meta text-muted">
                Tutti i dati saranno cancellati definitivamente. Inserisci la password per confermare.
            </p>

            <x-form-field
                name="password"
                type="password"
                placeholder="Password"
                errorBag="userDeletion"
            />

            <div class="mt-6 flex justify-end gap-3">
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
