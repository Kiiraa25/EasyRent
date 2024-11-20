	// Execute le code JS une fois tout le DOM chargé
	document.addEventListener("DOMContentLoaded", function () {
	    const prevButton = document.querySelector('.prev');
	    const nextButton = document.querySelector('.next');
	    const submitButton = document.querySelector('.submit-button');
	    const submitButton1 = document.querySelector('.submit-button1');
	    const stepIndicators = document.querySelectorAll('.step');

	    // toutes les sections du formulaire correspondant à chaque étape
	    const sections = document.querySelectorAll('[class^="form-step"]');

	    // variable qui permettra de garder une trace de l'étape en cours.
	    let currentStep = 0;


	    // Fonction qui affiche l'étape du formulaire ou index est égal à l'étape actuelle et cache les autres.
	    function showStep(index) {
	        sections.forEach((section, i) => {
	            section.classList.toggle('hidden', i !== index);
	        });

	        stepIndicators.forEach((indicator, i) => {
	            indicator.classList.toggle('active', i <= index);
	        });

	        // affiche les boutons précédent,suivant et envoyer quand necessaire.
	        prevButton.classList.toggle('hidden', index === 0);
	        nextButton.classList.toggle('hidden', index === sections.length - 1);
	        submitButton1.classList.toggle('hidden', index !== sections.length - 1);
	    }


	    // vérifie si les champs sont validé avant de pouvoir passer à l'étape suivante.
	    function validateCurrentStep() {

	        //récupères tous les champs de formulaire de l'étape en cours
	        const inputs = sections[currentStep].querySelectorAll('input, select, textarea');
	        let isValid = true;

	        // vérifie si un message d'erreur existe et le supprime si tel est le cas
	        inputs.forEach(input => {
	            let errorMessage = input.parentElement.querySelector('.error-message');
	            if (errorMessage) {
	                errorMessage.remove();
	            }

	            // crée une div qui contiendra le message d'erreur
	            errorMessage = document.createElement('div');
	            errorMessage.className = 'error-message';
	            errorMessage.style.color = 'red';
	            errorMessage.style.fontSize = '14px';
	            input.parentElement.appendChild(errorMessage);


	            // validations front pour chaque champ
	            if (input.name.includes('color') && !/^[a-zA-Z\s]+$/.test(input.value)) {
	                errorMessage.textContent = 'Veuillez entrer une couleur valide (texte uniquement).';
	                isValid = false;

	            } else if (input.name.includes('countryOfIssue') && !/^[a-zA-Z\s]+$/.test(input.value)) {
	                errorMessage.textContent = 'Veuillez entrer un pays valide.';
	                isValid = false;

	            } else if (input.name.includes('brand') || input.name.includes('model') || input.name.includes('address') || input.name.includes('city')) {
	                if (!/^[a-zA-ZÀ-ÿ0-9\s\-.']+$/.test(input.value)) {
	                    errorMessage.textContent = 'Veuillez entrer une valeur valide.';
	                    isValid = false;
	                }

	            } else if (input.name.includes('mileage')) {
	                const mileage = parseInt(input.value, 10);
	                if (isNaN(mileage) || mileage < 1) {
	                    errorMessage.textContent = 'Veuillez indiquer un kilométrage valide.';
	                    isValid = false;
	                } else if (mileage > 200000) {
	                    errorMessage.textContent = 'Le kilométragene peux pas être suppérieur à 200 000 km.';
	                    isValid = false;
	                }

	            } else if (input.name.includes('postalCode') && !/^\d+$/.test(input.value)) {
	                errorMessage.textContent = 'Veuillez indiquer un code postal valide.';
	                isValid = false;
	            } else if (input.name.includes('issueDate')) {
	                const issueDate = new Date(input.value);
	                const now = new Date();
	                const fifteenYearsAgo = new Date();
	                fifteenYearsAgo.setFullYear(now.getFullYear() - 15);

	                if (issueDate > now) {
	                    errorMessage.textContent = "La date d'immatriculation ne peut pas être ultérieure à aujourd'hui.";
	                    isValid = false;
	                } else if (issueDate < fifteenYearsAgo) {
	                    errorMessage.textContent = 'Vous ne pouvez pas ajouter un véhicule de plus de 15 ans.';
	                    isValid = false;
	                }
                    
	            } else if ((input.name.includes('pricePerDay') || input.name.includes('extraMileageRate'))) {
	                const value = parseFloat(input.value);
	                if (isNaN(value)) {
	                    errorMessage.textContent = 'Veuillez entrer un nombre valide pour le tarif.';
	                    isValid = false;
	                } else if (value < 0) {
	                    errorMessage.textContent = 'Le tarif ne peut pas être négatif.';
	                    isValid = false;
	                }
	            } else if (input.type === 'file') {
	                const file = input.files[0];
	                if (file && !file.type.startsWith('image/')) {
	                    errorMessage.textContent = 'Veuillez télécharger un fichier image.';
	                    isValid = false;
	                }
	            } else if (input.name.includes('seats')) {
	                const seats = parseInt(input.value, 10);
	                if (isNaN(seats) || seats < 1) {
	                    errorMessage.textContent = 'Le nombre de sièges doit être au moins de 1.';
	                    isValid = false;
	                } else if (seats > 7) {
	                    errorMessage.textContent = 'Le nombre de sièges doit être au maximum de 7.';
	                    isValid = false;
	                }
	            } else if (input.name.includes('mileage')) {
	                const mileage = parseInt(input.value, 10);
	                if (isNaN(mileage) || mileage < 0) {
	                    errorMessage.textContent = 'Le kilométrage doit être au minimum de 0 km.';
	                    isValid = false;
	                } else if (mileage > 200000) {
	                    errorMessage.textContent = 'Le kilométrage doit être au maximum de 200 000 km.';
	                    isValid = false;
	                }
	            } else if (input.name.includes('doors')) {
	                const doors = parseInt(input.value, 10);
	                if (isNaN(doors) || doors < 1) {
	                    errorMessage.textContent = 'Le nombre de portes doit être au moins de 1.';
	                    isValid = false;
	                } else if (doors > 5) {
	                    errorMessage.textContent = 'Le nombre de portes doit être au maximum de 5.';
	                    isValid = false;
	                }
	            } else if (!input.checkValidity()) {
	                errorMessage.textContent = input.validationMessage || 'Ce champ est obligatoire.';
	                isValid = false;
	            }

	        });

	        return isValid;
	    }

	    // suivi de l'etape en cours en cas de clic sur bouton "précédent"
	    prevButton.addEventListener('click', function () {
	        if (currentStep > 0)
	            currentStep--;
	        showStep(currentStep);
	    });

	    // affiche l'étape suivante si les champs de l'étape actuelle sont valides
	    nextButton.addEventListener('click', function () {
	        if (validateCurrentStep() && currentStep < sections.length - 1) {
	            currentStep++;
	            showStep(currentStep);
	        }
	    });

	    showStep(currentStep);
	});