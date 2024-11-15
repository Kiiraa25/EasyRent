// fonction d'activation de la géolocalisation.

function setLocationInSearch() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const latitude = position.coords.latitude;

                const longitude = position.coords.longitude;
                
                // Appelez la fonction pour obtenir l'adresse
                getAddressFromCoordinates(latitude, longitude);
                console.log(latitude, longitude);
                
            },
            (error) => {
                console.error("Géolocalisation non autorisée ou indisponible.");
            }
        );
    } else {
        alert("La géolocalisation n'est pas supportée par ce navigateur.");
    }
    
}


// si la geolocalisation est activée : afficher la localisation dans l'input search.

function getAddressFromCoordinates(lat, lon) {
    const url = `https://api-adresse.data.gouv.fr/reverse/?lon=${lon}&lat=${lat}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const properties = data.features[0]?.properties;
            if (properties) {
                const city = properties.city;
                const postcode = properties.postcode;

                // Extrait les deux premiers chiffres du code postal pour identifier le département
                const departmentCode = postcode.substring(0, 2);

                // Correspondance entre département et grande ville
                const majorCities = {
                    '75': 'Paris',
                    '13': 'Marseille',
                    '69': 'Lyon',
                    '31': 'Toulouse',
                    '06': 'Nice',
                    '44': 'Nantes',
                    '34': 'Montpellier',
                    '67': 'Strasbourg',
                    '33': 'Bordeaux',
                    '59': 'Lille',
                    '35': 'Rennes',
                    '51': 'Reims',
                    '76': 'Le Havre',
                    '42': 'Saint-Étienne',
                    '83': 'Toulon',
                    '38': 'Grenoble',
                    '21': 'Dijon',
                    '49': 'Angers',
                    '30': 'Nîmes',
                    '63': 'Clermont-Ferrand',
                    '72': 'Le Mans',
                    '29': 'Brest'
                };
                
                // Affiche la grande ville si une correspondance est trouvée. sinon affiche la ville locale
                const majorCity = majorCities[departmentCode] || city;

                let input1 = document.querySelector('input[name="search"]');
                let input2 = document.querySelector('input[name="search[search]"]');

                console.log(input1.value, input2.value)
                if(input1)
                {input1.value = majorCity}
                
                else if(input2)
                {input2.value = majorCity}
            } else {
                console.log("Adresse non trouvée.");
            }
        })
        .catch(error => console.error("Erreur lors de la récupération de l'adresse:", error));
}


document.addEventListener("DOMContentLoaded", function() {
    setLocationInSearch();
});

