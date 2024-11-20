/**
 * Selects the navbar element from the DOM.
 * 
 * @type {HTMLElement}
 */
const navbar = document.querySelector('body > nav > ul');

/**
 * Clears the content of the navbar element.
 */
export function clearNavbar(){
    navbar.innerHTML = '';
}

/**
 * Adds a new item to the navbar.
 *
 * @param {string} name - The name of the navbar item.
 * @param {Function} callback - The callback function to be executed when the item is clicked.
 */
export function addNavbarItem(name, callback){
    const li = document.createElement('li');
    li.textContent = name;
    li.onclick = () => {
        updateSelectedNavbarItem(li);
        callback();
    }
    navbar.appendChild(li);
}

/**
 * Updates the selected navbar item by removing the 'active' class from all
 * navbar items and adding it to the specified element.
 *
 * @param {HTMLElement} elm - The navbar item element to be marked as active.
 */
function updateSelectedNavbarItem(elm){
    for (const li of navbar.children){
        li.classList.remove('active');
    }
    elm.classList.add('active');
}


/**
 * Selects and clicks the first item in the navbar.
 */
export function selectFirstNavbarItem(){
    if (navbar.children.length > 0) 
        navbar.children[0].click();
}