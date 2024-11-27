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


// Récupérer l'image et le formulaire
const imageLabel = document.querySelector('#open-gallery label');
const form2 = document.getElementById('open-gallery');

// Ajouter un événement de clic sur l'image
imageLabel.addEventListener('click', () => {
    form2.submit();
});
