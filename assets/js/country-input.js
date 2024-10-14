let countryInput = document.querySelector('[data-action=country-input]');
let countryContainer = document.querySelector('.country-container');
let noResult = document.createElement('li');
noResult.className = "noResultCity";
noResult.innerText = "Aucun résultat";

let dropdownContainer = countryContainer.querySelector('#dropdownMenu');
let dropdownResults = dropdownContainer.querySelector('#dropdownResults');

countryInput.addEventListener('keyup', debounce((e) => {
    let value = e.target.value;

    fetch(`https://api.thecompaniesapi.com/v2/locations/countries?search=${value}`, {
        method: 'GET',
        headers: {
            'Authorization': `Bearer 83yPG2BA`
        }
    })
    .then((response) => {
        if (response.status !== 200) {
            throw new Error("Une erreur liée à l'API est survenue. Veuillez réessayer");
        }
        return response.json();
    })
    .then((body) => {
        let countries = body.countries;


        dropdownContainer.classList.remove('hidden');
        dropdownResults.innerHTML = '';

        if (countries && countries.length > 0) {
            countries.forEach((country) => {
                let li = document.createElement('li');
                li.innerText = country.nameFr; // Propriété 'label' pour afficher les résultats

                li.addEventListener('click', () => {
                    // En fonction des données de l'API, remplis les champs
                    countryInput.value = country.nameFr || ''; 
                    dropdownContainer.classList.add('hidden');
                    dropdownResults.innerHTML = '';
                });

                dropdownResults.appendChild(li);
            });
        } else {
            dropdownResults.appendChild(noResult);
        }
    })
    .catch((error) => {
        alert(error.message);
    });

    document.addEventListener('click', (e) => {
        if (!countryContainer.contains(e.target)) {
            dropdownContainer.classList.add('hidden');
            dropdownResults.innerHTML = '';
        }
    });

}, 300)); // Délais de 300 ms pour debounce

function debounce(callback, delay) {
    let timer;
    return function () {
        let args = arguments;
        let context = this;
        clearTimeout(timer);
        timer = setTimeout(function () {
            callback.apply(context, args);
        }, delay);
    };
}

