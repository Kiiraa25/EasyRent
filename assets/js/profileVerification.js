document.addEventListener("DOMContentLoaded", function () {
    const prevButton = document.querySelector('.prev');
    const nextButton = document.querySelector('.next');
    const sendButton = document.querySelector('.sendProfile');
    const stepIndicators = document.querySelectorAll('.step');
    
    const personalInfoSection = document.querySelector('.form-step-1');
    const drivingLicenseInfoSection = document.querySelector('.form-step-2');
    const addressInfoSection = document.querySelector('.form-step-3');
    
    let currentSection = 0;
    const sections = [personalInfoSection, drivingLicenseInfoSection, addressInfoSection];

    function validateSection(section) {
        let isValid = true;
        const inputs = section.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (!input.checkValidity()) {
                input.reportValidity();
                isValid = false;
            }
        });
        return isValid;
    }

    function showSection(index) {
        sections.forEach((section, i) => {
            section.classList.toggle('hidden', i !== index);
        });
        
        // Mise à jour des indicateurs d'étape
        stepIndicators.forEach((step, i) => {
            step.classList.toggle('active', i <= index);
        });

        // Mise à jour de la visibilité des boutons
        prevButton.classList.toggle('hidden', index === 0);
        nextButton.classList.toggle('hidden', index === sections.length - 1);
        sendButton.classList.toggle('hidden', index !== sections.length - 1);
    }

    prevButton.addEventListener('click', function (event) {
        event.preventDefault();
        if (currentSection > 0) {
            currentSection--;
            showSection(currentSection);
        }
    });

    nextButton.addEventListener('click', function (event) {
        event.preventDefault();
        if (validateSection(sections[currentSection])) {
            if (currentSection < sections.length - 1) {
                currentSection++;
                showSection(currentSection);
            }
        }
    });

    // Initial setup
    showSection(currentSection);
});
