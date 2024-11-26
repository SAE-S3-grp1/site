import { refreshNavbar } from "./navbar.js";
import { requestGET, requestPUT, requestDELETE, requestPATCH, requestPOST } from './ajax.js';
import { showLoader, hideLoader } from "./loader.js";
import { toast } from "./toaster.js";
import { showPropertieSkeleton, hidePropertieSkeleton } from "./propertieskeleton.js";

// Show skeleton
showPropertieSkeleton();

// Get inputs
const save_btn = document.getElementById('save_btn');
const delete_btn = document.getElementById('delete_btn');
const new_btn = document.getElementById('new_btn');
const prop_reunions = document.getElementById('prop_reunions');
const prop_nom_role = document.getElementById('prop_nom_role');
const prop_boutique = document.getElementById('prop_boutique');
const prop_users = document.getElementById('prop_users');
const prop_grades = document.getElementById('prop_grades');
const prop_roles = document.getElementById('prop_roles');
const prop_actualites = document.getElementById('prop_actualites');
const prop_events = document.getElementById('prop_events');
const prop_comptabilite = document.getElementById('prop_comptabilite');
const prop_historique = document.getElementById('prop_historique');
const prop_logs = document.getElementById('prop_logs');
const prop_moderation = document.getElementById('prop_moderation');

/**
 * Reloads the navigation bar with grade items.
 */
async function fetchData() {

    // Fetch data
    let roles = [];
    try{
        roles = await requestGET('/role.php');
    } catch (error) {
        toast(error.message, true);
    }

    // Transform data to navbar items
    return roles.map(role => ({label: role.nom_role, id: role.id_role}));

}

/**
 * Saves the grade information.
 *
 * @param {number} id_role - The ID of the role to be saved.
 * @returns {Promise<void>} A promise that resolves when the grade is successfully saved.
 * @throws Will alert an error message if the request fails.
 */
async function saveRole(id_role){

    // Show loader
    showLoader();

    // Create data
    const data = {
        name: prop_nom_role.value === '' ? 'N/A' : prop_nom_role.value,
        permissions: {
            p_log: prop_logs.classList.contains('active'),
            p_boutique: prop_boutique.classList.contains('active'),
            p_reunion: prop_reunions.classList.contains('active'),
            p_utilisateur: prop_users.classList.contains('active'),
            p_grade: prop_grades.classList.contains('active'),
            p_role: prop_roles.classList.contains('active'),
            p_actualite: prop_actualites.classList.contains('active'),
            p_evenement: prop_events.classList.contains('active'),
            p_comptabilite: prop_comptabilite.classList.contains('active'),
            p_achat: prop_historique.classList.contains('active'),
            p_moderation: prop_moderation.classList.contains('active')
        }
    };

    // Send data
    try {
        await requestPUT('/role.php?id=' + id_role.toString(), data);
        toast('Role mis à jour avec succès.');
        selectRole(id_role);
    } catch (error) {
        toast(error.message, true);
    }

    // Stop loader
    hideLoader();

}

/**
 * Deletes the grade from the DB.
*/
async function deleteRole(id_role){

    // Show loader
    showLoader();

    // Send request
    await requestDELETE(`/role.php?id=${id_role}`);
    
    /// Update navbar
    refreshNavbar(fetchData, selectRole);

    // Deleted message
    toast('Role supprimé avec succès.');

}

/**
 * Loads and displays grade information based on the provided grade ID.
 *
 * @param {number} id_role - The ID of the grade to be selected.
 * @returns {Promise<void>} A promise that resolves when the grade information has been fetched and displayed.
 */
async function selectRole(id_role, li){

    // Show skeleton
    showPropertieSkeleton();

    // Show loader
    showLoader();

    // Fetch grade information
    const role = await requestGET(`/role.php?id=${id_role}`);

    // Update displayed information
    prop_nom_role.value = role.nom_role;
    if (role.p_log) prop_logs.classList.add('active');
    if (role.p_boutique) prop_boutique.classList.add('active');
    if (role.p_utilisateur) prop_users.classList.add('active');
    if (role.p_grade) prop_grades.classList.add('active');
    if (role.p_role) prop_roles.classList.add('active');
    if (role.p_actualite) prop_actualites.classList.add('active');
    if (role.p_evenement) prop_events.classList.add('active');
    if (role.p_comptabilite) prop_comptabilite.classList.add('active');
    if (role.p_achat) prop_historique.classList.add('active');
    if (role.p_moderation) prop_moderation.classList.add('active');
    if (role.p_reunion) prop_reunions.classList.add('active');
    
    // Update save button
    save_btn.onclick = ()=>{
        saveRole(id_role);
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
                deleteRole(id_role);
            }
          });
    };

    // Update name
    function updateName(){
        li.textContent = prop_nom_role.value;
    }
    prop_nom_role.onkeyup = updateName;

    // Hide loader
    hideLoader();

    // Hide skeleton
    hidePropertieSkeleton();
    
}

// Handle new user
new_btn.onclick = async ()=>{

    // Show loader
    showLoader();

    // Create new grade
    try {
        const { id_role } = await requestPOST('/role.php');
        refreshNavbar(fetchData, selectRole, id_role);
    } catch (error) {
        toast(error.message, true);
        hideLoader();
    }

};

// Load navbar
refreshNavbar(fetchData, selectRole);