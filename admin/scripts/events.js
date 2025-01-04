import { refreshNavbar } from "./navbar.js";
import { requestGET, requestPUT, requestDELETE, requestPATCH, requestPOST } from './ajax.js';
import { showLoader, hideLoader } from "./loader.js";
import { toast } from "./toaster.js";
import { showPropertieSkeleton, hidePropertieSkeleton } from "./propertieskeleton.js";
import { getFullFilepath } from "./files.js";

// Show skeleton
showPropertieSkeleton();

// Get inputs
const prop_image = document.getElementById('prop_image');
const prop_name = document.getElementById('prop_name');
const prop_lieu = document.getElementById('prop_lieu');
const prop_places = document.getElementById('prop_places');
const prop_price = document.getElementById('prop_price');
const prop_reductions = document.getElementById('prop_reductions');
const save_btn = document.getElementById('save_btn');
const delete_btn = document.getElementById('delete_btn');
const new_btn = document.getElementById('new_btn');

/**
 * Reloads the navigation bar with event items.
 */
async function fetchData() {

    // Fetch data
    let events = [];
    try{
        events = await requestGET('/event.php');
    } catch (error) {
        toast('Erreur lors du chargement des grades.', true);
    }

    // Transform data to navbar items
    return events.map(event => ({label: event.name, id: event.id_event}));

}

/**
 * Saves the event information.
 *
 * @param {number} id_event - The ID of the event to be saved.
 * @returns {Promise<void>} A promise that resolves when the event is successfully saved.
 */
async function saveEvent(id_event){

    // Show loader
    showLoader();

    // Create data
    const data = {
        name: prop_name.value,
        description: prop_lieu.value,
        price: prop_places.value,
        reduction: prop_reductions.value
    };

    // Send data
    try {
        await requestPUT('/event.php?id=' + id_event.toString(), data);
        toast('Grade mis à jour avec succès.');
        selectGrade(id_event);
    } catch (error) {
        toast(error.message, true);
    }

    // Stop loader
    hideLoader();

}

/**
 * Deletes the event from the DB.
*/
async function deleteEvent(id_event){

    // Show loader
    showLoader();

    // Send request
    await requestDELETE(`/evennt.php?id=${id_event}`);
    
    /// Update navbar
    refreshNavbar(fetchData, selectGrade);

    // Deleted message
    toast('Grade supprimé avec succès.');

}

/**
 * Loads and displays event information based on the provided grade ID.
 *
 * @param {number} id_event - The ID of the event to be selected.
 * @returns {Promise<void>} A promise that resolves when the event information has been fetched and displayed.
 */
async function selectGrade(id_event, li){

    // Show skeleton
    showPropertieSkeleton();

    // Show loader
    showLoader();

    // Fetch grade information
    const event = await requestGET(`/event.php?id=${id_event}`);

    // Update displayed information
    prop_image.src = await getFullFilepath(event.image_grade, '../ressources/default_images/event.jpg');
    prop_name.value = event.nom_grade;
    prop_lieu.value = event.description_grade;
    prop_places.value = event.prix_grade;
    prop_reductions.value = event.reduction_grade;

    // Update save button
    save_btn.onclick = ()=>{
        saveEvent(id_event);
    };

    // Delete button
    delete_btn.onclick = ()=>{
        swal({
            title: "Êtes vous sûr ?",
            text: "Cette action est définitive",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
                deleteEvent(id_event);
            }
          });
    };

    // Update name
    prop_name.onkeyup = ()=>{
        li.textContent = prop_name.value;
    };

    // Hide loader
    hideLoader();

    // Hide skeleton
    hidePropertieSkeleton();
    
}

// Handle new event
new_btn.onclick = async ()=>{

    // Show loader
    showLoader();

    // Create new grade
    try {
        const id = await requestPOST('/event.php');
        refreshNavbar(fetchData, selectGrade, id);
    } catch (error) {
        toast("Erreur lors de la création de l'évenement", true);
        hideLoader();
    }

};

// Load navbar
refreshNavbar(fetchData, selectGrade);