document.addEventListener('DOMContentLoaded', function () {
    let addButton = document.querySelector('.add-photo-button');
    let photoCollection = document.querySelector('.photo-items');
    let photoPrototype = document.querySelector('.photo-prototype').dataset.prototype;

    let index = photoCollection.children.length;

    addButton.addEventListener('click', function () {
        let newForm = photoPrototype.replace(/__name__/g, index);
        let div = document.createElement('div');
        div.classList.add('photo-item');
        div.innerHTML = newForm;
        photoCollection.appendChild(div);
        index++;
    });
});
