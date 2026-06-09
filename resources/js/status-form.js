// Gestione AJAX del cambio stato articolo (bozza <-> pubblicato).

document.addEventListener("DOMContentLoaded", () => {
    // Leggiamo UNA volta sola il token CSRF dal <meta> nel layout.
    // Laravel lo pretende su ogni richiesta POST/PUT/PATCH/DELETE per sicurezza.
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    const STATUS_BADGE_BASE =
        "inline-block rounded-full px-2.5 py-0.5 text-[0.7rem] font-semibold uppercase tracking-wide";

    // Aggancio i form tramite l'attributo data-article-id (hook stabile,
    // indipendente dalle classi di stile che ora sono utility Tailwind).
    document.querySelectorAll("form[data-article-id]").forEach((form) => {
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
                        STATUS_BADGE_BASE +
                        " " +
                        (dati.isPublished
                            ? "bg-green-100 text-green-800"
                            : "bg-yellow-100 text-yellow-800");
                }

                // console.log(dati.message);
            } catch (errore) {
                console.error(errore);
                alert("Errore di rete: riprova.");
            }
        });
    });
});
