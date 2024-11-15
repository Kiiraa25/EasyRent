let searchContainer = document.querySelector('.search-container');
let searchInput = document.querySelector('[data-action=search-input]');

let noResult = document.createElement('li');
noResult.className = "noResult";
noResult.innerText = "Aucun résultat";


function handleSearchInput(value) {

    let dropdownContainer = document.querySelector('.search-container #dropdownMenu');
    let dropdownResults = dropdownContainer.querySelector('#dropdownResults');

    fetch(`https://api-adresse.data.gouv.fr/search/?q=${value}&limit=10`, {
        method: 'GET'
    }).then((response) => {
        if (response.status !== 200) {
            // alert("Une erreur liée à l'API est survenue. Veuillez réessayer.");
            location.reload();

        }
        return response.json();
    }).then((body) => {
        dropdownContainer.classList.remove('hidden');
        dropdownResults.innerHTML = '';  // Clear previous results

        let features = body.features;

        if (features && features.length > 0) {
            features.forEach((feature) => {
                let li = document.createElement('li');
                li.innerText = feature.properties.label;  // Adjusted to show relevant property

                li.addEventListener('click', () => {
                    searchInput.value = feature.properties.label;  // Set selected search result
                    dropdownContainer.classList.add('hidden');
                    dropdownResults.innerHTML = '';
                });

                dropdownResults.appendChild(li);
            });
        } else {
            dropdownResults.appendChild(noResult);  // Show "no result" if nothing found
        }
    });

    // Cacher les résultats si l'utilisateur clic en dehors du conteneur
    document.addEventListener('click', (e) => {
        if (!searchContainer.contains(e.target)) {
            dropdownContainer.classList.add('hidden');
            dropdownResults.innerHTML = '';

        
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
        }, delay);
    };
}

// Attach event listener to the search input
searchInput.addEventListener('keyup', debounce(function (e) {
    handleSearchInput(e.target.value);  // Trigger search with input value
}, 450));
