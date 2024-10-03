let currentStep = 1;
const totalSteps = 5;

function showStep(step) {
    document.querySelectorAll('.step').forEach(function (el) {
        el.classList.remove('active');
    });
    document.getElementById('step' + step).classList.add('active');
}

function nextStep() {
    if (currentStep < totalSteps) {
        currentStep++;
        showStep(currentStep);
    }
}

function prevStep() {
    if (currentStep > 1) {
        currentStep--;
        showStep(currentStep);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    showStep(currentStep);
});

export { nextStep, prevStep };
