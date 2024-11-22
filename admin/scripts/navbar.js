/**
 * Import the loader
 */
import { showLoader, hideLoader } from "./loader.js";

/**
 * Selects the navbar element from the DOM.
 */
const navbar = document.getElementById('content_navbar');
const skeleton = document.getElementById('skeleton_navbar');
const empty_navbar = document.getElementById('empty_navbar');

/**
 * @param {Function} fetchData - An asynchronous function that fetches the data for the navbar.
 * @param {Function(): Promise<{label: string, id: number}[]>} fetchData - An asynchronous function that fetches the data for the navbar.
 * @param {Function(number, HTMLElement): void} selectItem - A callback function that handles the selection of an item in the navbar.
*/
export async function refreshNavbar(fetchData, selectItem, defaultSelectedItem = null) {

    // Show loader
    showLoader();

    // Clear navbar
    navbar.innerHTML = '';

    // Hide empty navbar
    empty_navbar.hidden = true;

    // Show fetching skeleton
    skeleton.hidden = false;

    // Fetch data
    const items = await fetchData();

    // Set default select item to the first of items if not defined
    if (defaultSelectedItem === null && items.length > 0)
        defaultSelectedItem = items[0].id;

    // Hide fetching skeleton
    skeleton.hidden = true;

    // Add items to the navbar
    let needToBeSelectedLi = null;
    for (const item of items) {
        addNavbarItem(item.label, li => selectItem(item.id, li));
        if (item.id === defaultSelectedItem)
            needToBeSelectedLi = navbar.lastChild;
    }

    // Shows empty navbar if no items
    if (items.length === 0)
        empty_navbar.hidden = false;

    // Select default item
    if (needToBeSelectedLi !== null)
        needToBeSelectedLi.click();

    // Hide loader
    hideLoader();

}

/**
 * Adds an item to the navbar.
 *
 * @param {string} label - The label of the item.
 * @param {Function(HTMLElement): void} onClick - The callback function to be called when the item is clicked.
 */
function addNavbarItem(label, onClick){

    // Create item
    let li = document.createElement('li');
    li.textContent = label;

    // Add event listener
    li.onclick = () => {

        // Remove active class from all items
        for (const item of navbar.children)
            item.classList.remove('active');

        // Add active class to the clicked item
        li.classList.add('active');

        // Call the callback function
        onClick(li);
    
    }

    // Add item to the navbar
    navbar.appendChild(li);

}