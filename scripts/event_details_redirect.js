const eventDivs = document.querySelectorAll('.event');

// Ajouter un gestionnaire de clic à chaque div
eventDivs.forEach(div => {
    div.addEventListener('click', () => {
        const eventId = div.getAttribute('event-id'); // Récupère l'ID de l'événement
        if (eventId) {
            window.location.href = `event_details.php?id=${eventId}`; // Redirige vers la page de détails
        }
    });
});
