import { refreshNavbar } from "./navbar.js";
import { requestGET, requestPUT, requestDELETE, requestPATCH, requestPOST } from './ajax.js';
import { showLoader, hideLoader } from "./loader.js";
import { toast } from "./toaster.js";
import { showPropertieSkeleton, hidePropertieSkeleton } from "./propertieskeleton.js";

// Show skeleton
showPropertieSkeleton();

// Get inputs
const delete_btn = document.getElementById('delete_btn');
const new_btn = document.getElementById('new_btn');

/**
 * Reloads the navigation bar with grade items.
 */
async function fetchData() {

    // Fetch data
    let users = [];
    try{
        users = await requestGET('/users.php');
    } catch (error) {
        toast('Erreur lors du chargement des utilisateurs.', true);
    }

    // Transform data to navbar items
    return users.map(user => ({label: user.prenom_membre + ' ' + user.nom_membre.toUpperCase(), id: user.id_membre}));

}

/**
 * Saves the grade information.
 *
 * @param {number} id_user - The ID of the grade to be saved.
 * @returns {Promise<void>} A promise that resolves when the grade is successfully saved.
 * @throws Will alert an error message if the request fails.
 */
async function saveUser(id_user){

    // Show loader
    showLoader();

    // Create data
    const data = {
        name: prop_nom_grade.value,
        description: prop_description_grade_grade.value,
        price: prop_prix_grade.value,
        reduction: prop_reduction_grade.value
    };

    // Send data
    try {
        await requestPUT('/users.php?id=' + id_user.toString(), data);
        toast('Grade mis à jour avec succès.');
        selectUser(id_user);
    } catch (error) {
        toast('Erreur lors de la mise à jour du grade.', true);
    }

    // Stop loader
    hideLoader();

}

/**
 * Deletes the grade from the DB.
*/
async function deleteUser(id_user){

    // Show loader
    showLoader();

    // Send request
    await requestDELETE(`/users.php?id=${id_user}`);
    
    /// Update navbar
    reloadNavbar(); // Will hide loader

    // Deleted message
    toast('Utilisateur supprimé avec succès.');

}

/**
 * Loads and displays grade information based on the provided grade ID.
 *
 * @param {number} id_member - The ID of the grade to be selected.
 * @returns {Promise<void>} A promise that resolves when the grade information has been fetched and displayed.
 */
async function selectUser(id_member, li){

    // Show skeleton
    showPropertieSkeleton();

    // Show loader
    showLoader();

    // Fetch grade information
    const user = await requestGET(`/users.php?id=${id_member}`);

    // Update displayed information
    prop_image_grade.src = user.image_grade ? user.image_grade : '../ressources/default_images/member.webp';
    prop_nom_grade.value = user.nom_grade;
    prop_description_grade_grade.value = user.description_grade;
    prop_prix_grade.value = user.prix_grade;
    prop_reduction_grade.value = user.reduction_grade;

    // Update save button
    save_btn.onclick = ()=>{
        saveUser(id_member);
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
                deleteUser(id_member);
            }
          });
    };

    // Update name
    prop_nom_grade.onkeyup = ()=>{
        li.textContent = prop_nom_grade.value;
    };

    // Hide loader
    hideLoader();

    // Hide skeleton
    hidePropertieSkeleton();
    
}

// Handle new user
new_btn.onclick = async ()=>{

};

// Load navbar
refreshNavbar(fetchData, selectUser);