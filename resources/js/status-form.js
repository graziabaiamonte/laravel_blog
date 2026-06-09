// Gestione AJAX del cambio stato articolo (bozza <-> pubblicato).

document.addEventListener("DOMContentLoaded", () => {
    // Leggiamo UNA volta sola il token CSRF dal <meta> nel layout.
    // Laravel lo pretende su ogni richiesta POST/PUT/PATCH/DELETE per sicurezza.
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    document.querySelectorAll(".status-form").forEach((form) => {
        form.addEventListener("submit", async (event) => {
            event.preventDefault();

            const articleId = form.dataset.articleId;
            const nuovoStato = form.querySelector(
                'select[name="status"]',
            ).value;

            try {
                const risposta = await fetch(form.action, {
                    method: "PATCH",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json", // diciamo: "rispondimi in JSON" -> attiva wantsJson()
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: JSON.stringify({ status: nuovoStato }),
                });

                const dati = await risposta.json();

                if (!risposta.ok) {
                    alert(dati.message ?? "Qualcosa è andato storto.");
                    return;
                }

                const badge = document.querySelector(
                    '[data-status-badge="' + articleId + '"]',
                );
                if (badge) {
                    badge.textContent = dati.label;
                    badge.className =
                        "status-badge " +
                        (dati.isPublished
                            ? "status-badge--published"
                            : "status-badge--draft");
                }

                // console.log(dati.message);
            } catch (errore) {
                console.error(errore);
                alert("Errore di rete: riprova.");
            }
        });
    });
});
