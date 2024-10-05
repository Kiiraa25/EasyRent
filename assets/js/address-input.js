let cityInput = document.querySelector('[data-action=city-input]');
let postcodeInput = document.querySelector('[data-action=postcode-input]');
let dropdownContainer = document.querySelector('#dropdownMenu');
let dropdownResults = document.querySelector('#dropdownResults');
let addressContainer = document.querySelector('.address-container');
let noResult = document.createElement('li');
noResult.className = "noResultCity";
noResult.innerText = "Aucun résultat";

let citySelected = false;

cityInput.addEventListener('keyup', debounce(function (e) {
    citySelected = false;

    let value = e.target.value;
    // if (value.length < 3 || value.length > 200) {
    //     alert('la ville doit comporter entre 3 et 200 caractères')
    // } else {
    fetch(`https://api-adresse.data.gouv.fr/search/?q=${value}&type=municipality&limit=10`, {
        method: 'GET'
    }).then((response) => {
        let status = response.status;
        if (status !== 200) {
            alert("Une erreur liée à l'API est survenue. Veuillez réessayer")
        }

        return response.json();
    }).then((body) => {
        let features = body.features;

        dropdownContainer.classList.remove('hidden')

        dropdownResults.innerHTML = '';

        if (features != null && features.length > 0) {


            features.map((feature) => {
                let li = document.createElement('li');
                li.innerText = `${feature.properties.name} - ${feature.properties.postcode}`;
                
                
                li.addEventListener('click', () => {
                    cityInput.value = li.innerText;
                    postcodeInput.value = feature.properties.postcode;
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
                postcodeInput.value = ''; 
                cityInput.value='';
            }
        }
    })
    // }
}, 450))

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