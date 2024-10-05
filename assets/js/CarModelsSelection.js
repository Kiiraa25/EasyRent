document.querySelector('select[name="make"]').addEventListener('change', function() {
    const make = this.value;
    
    fetch(`/api/carmodels?make=${make}`)
        .then(response => response.json())
        .then(models => {
            const modelSelect = document.querySelector('select[name="model"]');
            modelSelect.innerHTML = ''; // Clear existing options

            models.forEach(model => {
                const option = document.createElement('option');
                option.value = model;
                option.text = model;
                modelSelect.appendChild(option);
            });
        });
});
