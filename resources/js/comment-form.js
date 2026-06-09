// Invio AJAX di un commento

document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("[data-comment-form]");

    // Se il form non c'è (articolo in bozza, visitatore non loggato, ecc.)
    // non agganciamo nulla.
    if (!form) {
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const textarea = form.querySelector('textarea[name="body"]');
    const errorBox = form.querySelector("[data-comment-error]");
    const feedback = document.querySelector("[data-comment-feedback]");
    const list = document.querySelector("[data-comments-list]");

    form.addEventListener("submit", async (event) => {
        event.preventDefault();
        errorBox.textContent = ""; // pulisco eventuali errori precedenti

        try {
            const risposta = await fetch(form.action, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json", // attiva wantsJson() lato server
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({ body: textarea.value }),
            });

            const dati = await risposta.json();

            // 422 = errore di validazione (commento vuoto, troppo lungo, parolacce...)
            if (risposta.status === 422) {
                errorBox.textContent =
                    dati.errors?.body?.[0] ?? "Controlla il commento.";
                return;
            }

            if (!risposta.ok) {
                errorBox.textContent = dati.message ?? "Qualcosa è andato storto.";
                return;
            }

            // Successo: svuoto il campo, mostro il messaggio di esito
            // e inserisco il nuovo commento in cima alla lista (è "in attesa").
            textarea.value = "";

            if (feedback) {
                feedback.textContent = dati.message;
                feedback.classList.remove("hidden");
            }

            rimuoviStatoVuoto();
            list.prepend(costruisciCard(dati.comment));
        } catch (errore) {
            console.error(errore);
            errorBox.textContent = "Errore di rete: riprova.";
        }
    });

    // Toglie la riga "Ancora nessun commento..." al primo commento inserito.
    function rimuoviStatoVuoto() {
        document.querySelector("[data-comments-empty]")?.remove();
    }

    // Ricostruisce la card di un commento "in attesa"
    function costruisciCard(comment) {
        const card = document.createElement("div");
        card.className =
            "mb-4 rounded-card border border-line bg-surface p-4 border-dashed";

        const testa = document.createElement("div");
        testa.className = "mb-1 flex flex-wrap items-center gap-2";

        const autore = document.createElement("span");
        autore.className = "text-sm font-semibold text-ink";
        autore.textContent = comment.author;

        const data = document.createElement("span");
        data.className = "text-meta text-muted";
        data.textContent = "· " + comment.created_at;

        const badge = document.createElement("span");
        badge.className =
            "inline-block rounded-full bg-yellow-100 px-2.5 py-0.5 text-[0.7rem] font-semibold uppercase tracking-wide text-yellow-800";
        badge.textContent = "In attesa";

        testa.append(autore, data, badge);

        const corpo = document.createElement("p");
        corpo.className = "whitespace-pre-line text-body text-ink";
        corpo.textContent = comment.body;

        card.append(testa, corpo);
        return card;
    }
});
