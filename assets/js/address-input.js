let cityInput = document.querySelector('[data-action=city-input]');
let postcodeInput = document.querySelector('[data-action=postcode-input]');
let addressInput = document.querySelector('[data-action=address-input]');
let addressContainer = document.querySelector('.address-container');
let noResult = document.createElement('li');
noResult.className = "noResultCity";
noResult.innerText = "Aucun résultat";

let citySelected = false;

function handleInput(containerName, key, value) {
    citySelected = false;

    let dropdownContainer = document.querySelector(`.${containerName} #dropdownMenu`);
    let dropdownResults = dropdownContainer.querySelector('#dropdownResults');

    fetch(`https://api-adresse.data.gouv.fr/search/?q=${value}&limit=10`, {
        method: 'GET'
    }).then((response) => {
        let status = response.status;
        if (status !== 200) {
            alert("Une erreur liée à l'API est survenue. Veuillez réessayer")
        }

        return response.json();
    }).then((body) => {
        
        dropdownContainer.classList.remove('hidden')
        dropdownResults.innerHTML = '';

        let features = body.features;

        if (features != null && features.length > 0) {

            features.map((feature) => {
                let li = document.createElement('li');
                li.innerText = feature.properties[key];


                li.addEventListener('click', () => {
                    if (containerName === 'street-container') {
                        addressInput.value = feature.properties.name;
                        cityInput.value = feature.properties.city;
                        postcodeInput.value = feature.properties.postcode;
                    } else if (containerName === 'postcode-container' || containerName === 'city-container') {
                        cityInput.value = feature.properties.city;
                        postcodeInput.value = feature.properties.postcode;
                    }

                    dropdownContainer.classList.add('hidden');
                    dropdownResults.innerHTML = '';
                    citySelected = true;
                })

                dropdownResults.appendChild(li)

            })
        } else {
            dropdownResults.appendChild(noResult);
        }
    })

    document.addEventListener('click', (e) => {
    if (!addressContainer.contains(e.target)) {
        dropdownContainer.classList.add('hidden');
        dropdownResults.innerHTML = '';

        if (!citySelected) {
            // Handle the different cases for each input separately
            if (containerName === 'street-container' && addressInput.value !== '') {
                addressInput.value = ''; // Only clear address input if modifying address
            } 
            else if (containerName === 'postcode-container' || containerName === 'city-container') {
                postcodeInput.value = ''; // Clear postcode and city only
                cityInput.value = '';
            }
        }
    }
});

}

function debounce(callback, delay) {
    let timer;
    return function () {
        let args = arguments;
        let context = this;
        clearTimeout(timer);
        timer = setTimeout(function () {
            callback.apply(context, args);
        }, delay)
    }
}

addressInput.addEventListener('keyup', debounce(function (e) {
    handleInput('street-container', 'label', e.target.value); // Search for addresses
}, 450));
cityInput.addEventListener('keyup', debounce(function (e) {
    handleInput('city-container', 'city', e.target.value); // Search for addresses
}, 450));
postcodeInput.addEventListener('keyup', debounce(function (e) {
    handleInput('postcode-container', 'postcode', e.target.value); // Search for addresses
}, 450));