const toggleButton = document.querySelector('.toggle-owner-menu');
const circle = document.querySelector('.circle-toggle-owner-menu');
const renterMenu = document.querySelector('.renterMode');
const ownerMenu = document.querySelector('.ownerMode');

let ownerMode = false;

document.addEventListener('DOMContentLoaded', () => {

    toggleButton.addEventListener('click', () => {
        if (toggleButton.style.justifyContent == 'end' && toggleButton.style.backgroundColor ==
            'rgb(255, 174, 158)') {
            toggleButton.style.justifyContent = 'start';
            toggleButton.style.backgroundColor = '#D9D9D9'
            ownerMode = false;
            renterMenu.classList.remove('hidden');
            ownerMenu.classList.add('hidden');
        } else {
            toggleButton.style.justifyContent = 'end';
            toggleButton.style.backgroundColor = 'rgb(255, 174, 158)'
            ownerMode = true;
            ownerMenu.classList.remove('hidden')
            renterMenu.classList.add('hidden')

        }

        console.log(ownerMode)

    })

});