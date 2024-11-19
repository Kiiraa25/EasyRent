document.addEventListener("DOMContentLoaded", () => {
    
    function changeMainImage(imageUrl, thumbnail) {
        const mainImage = document.getElementById('main-image');
        mainImage.src = imageUrl;

        // Réinitialiser la sélection des miniatures
        document.querySelectorAll('.thumbnail').forEach(thumbnail => {
            thumbnail.classList.remove('selected');
        });

        // Ajouter la classe sélectionnée à la miniature active
        thumbnail.classList.add('selected');
    }


})