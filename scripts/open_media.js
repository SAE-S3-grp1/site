document.querySelectorAll('.my-medias img, .general-medias img').forEach(image => {
    image.addEventListener('click', (event) => {
        // Vérifier si l'image appartient à un parent autre que #add-media
        if (!image.closest('#add-media')) {
            // Ouvrir l'image dans un nouvel onglet
            window.open(image.src, '_blank');
        }
    });
});


const filePicker = document.getElementById('file-picker');
const form = document.getElementById('add-media');

filePicker.addEventListener('change', (event) => {
    const file = event.target.files[0];

    if (file) {
        if (file.type === "image/png" || file.type === "image/jpeg") {
            const fileURL = URL.createObjectURL(file);
            
            // Soumet automatiquement le formulaire après sélection
            form.submit();
        } else {
            alert("Veuillez sélectionner une image au format PNG ou JPEG.");
        }
    }
});
