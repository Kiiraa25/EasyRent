let brandInput = document.querySelector('[data-action=brand-input]');
let modelInput = document.querySelector('[data-action=model-input]');
let vehicleContainer = document.querySelector('.vehicle-container');
let noVehicleResult = document.createElement('li');
noVehicleResult.className = "noResultbrand";
noVehicleResult.innerText = "Aucun résultat";

let brandSelected = false;

function handleInput(containerName, key, value = '') {
    brandSelected = false;

    let dropdownContainer = document.querySelector(`.${containerName} #dropdownMenu`);
    let dropdownResults = dropdownContainer.querySelector('#dropdownResults');

    if (key === 'make') {
        // Fetch car makes from CarQuery API
        $.ajax({
            url: `https://www.carqueryapi.com/api/0.3/?callback=?&cmd=getMakes`,
            dataType: 'jsonp',
            success: function(data) {
                let makes = data.Makes;

                // Sort makes alphabetically
                makes.sort((a, b) => a.make_display.localeCompare(b.make_display));

                dropdownContainer.classList.remove('hidden');
                dropdownResults.innerHTML = '';

                if (makes && makes.length > 0) {
                    makes.forEach((make) => {
                        if (make.make_display.toLowerCase().includes(value.toLowerCase()) || value === '') {
                            let li = document.createElement('li');
                            li.innerText = make.make_display;

                            li.addEventListener('click', () => {
                                brandInput.value = make.make_display;
                                dropdownContainer.classList.add('hidden');
                                dropdownResults.innerHTML = '';
                                brandSelected = true;
                            });

                            dropdownResults.appendChild(li);
                        }
                    });
                } else {
                    dropdownResults.appendChild(noVehicleResult);
                }
            }
        });
    }

    if (key === 'model') {
        let selectedMake = brandInput.value;

        if (selectedMake.length > 0) {
            $.ajax({
                url: `https://www.carqueryapi.com/api/0.3/?callback=?&cmd=getModels&make=${selectedMake}`,
                dataType: 'jsonp',
                success: function(data) {
                    let models = data.Models;

                    // Sort models alphabetically
                    models.sort((a, b) => a.model_name.localeCompare(b.model_name));

                    dropdownContainer.classList.remove('hidden');
                    dropdownResults.innerHTML = '';

                    if (models && models.length > 0) {
                        models.forEach((model) => {
                            if (model.model_name.toLowerCase().includes(value.toLowerCase()) || value === '') {
                                let li = document.createElement('li');
                                li.innerText = model.model_name;

                                li.addEventListener('click', () => {
                                    modelInput.value = model.model_name;
                                    dropdownContainer.classList.add('hidden');
                                    dropdownResults.innerHTML = '';
                                    brandSelected = true;
                                });

                                dropdownResults.appendChild(li);
                            }
                        });
                    } else {
                        dropdownResults.appendChild(noVehicleResult);
                    }
                }
            });
        }
    }

    document.addEventListener('click', (e) => {
        if (!vehicleContainer.contains(e.target)) {
            dropdownContainer.classList.add('hidden');
            dropdownResults.innerHTML = '';

            // if (!brandSelected) {
            //     if (containerName === 'model-container' || containerName === 'brand-container') {
            //         modelInput.value = '';
            //         brandInput.value = '';
            //     }
            // }
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

// Trigger API request when brand input is focused, showing all makes
brandInput.addEventListener('focus', function () {
    handleInput('brand-container', 'make', ''); // Empty value to fetch all makes
});

// Trigger API request when model input is focused, showing all models for the selected brand
modelInput.addEventListener('focus', function () {
    handleInput('model-container', 'model', ''); // Empty value to fetch all models
});

// Allow dynamic searching as well
brandInput.addEventListener('keyup', debounce(function (e) {
    handleInput('brand-container', 'make', e.target.value);
}, 350));

modelInput.addEventListener('keyup', debounce(function (e) {
    handleInput('model-container', 'model', e.target.value);
}, 250));
