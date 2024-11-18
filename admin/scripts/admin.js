const listItems = document.querySelectorAll("#main nav ul li");
const content = document.getElementById('content');

listItems.forEach(item => {
    item.addEventListener('click', () => {
        // Supprime la classe 'selected' de tous les éléments
        listItems.forEach(li => li.classList.remove('selected'));
        
        // Ajoute la classe 'selected' à l'élément cliqué
        item.classList.add('selected');
        content.src = './panels/' + item.getAttribute('perm') + '.html';
        
    });
});


/* Select first permissions */
listItems[1].click();
