import { clearNavbar, addNavbarItem, selectFirstNavbarItem  } from "./navbar.js";
import { requestGET } from './ajax.js';

// Get inputs
const prop_image_grade = document.getElementById('prop_image_grade');
const prop_nom_grade = document.getElementById('prop_nom_grade');
const prop_description_grade_grade = document.getElementById('prop_description_grade_grade');
const prop_prix_grade = document.getElementById('prop_prix_grade');
const prop_reduction_grade = document.getElementById('prop_reduction_grade');
const save_btn = document.getElementById('save_btn');

// Add navbar items for each grade
clearNavbar()
const grades = await requestGET('/grade.php');
for (let i = 0; i < grades.length; i++) {
    const grade = grades[i];
    addNavbarItem(grade.nom_grade, ()=>{
        selectGrade(grade.id_grade)
    });
}
selectFirstNavbarItem();

/**
 * Saves the grade information.
 *
 * @param {number} id_grade - The ID of the grade to be saved.
 * @returns {Promise<void>} A promise that resolves when the grade is successfully saved.
 * @throws Will alert an error message if the request fails.
 */
async function saveGrade(id_grade){

    // Create data
    const data = {
        id: id_grade,
        name: prop_nom_grade.value,
        description: prop_description_grade_grade.value,
        price: prop_prix_grade.value,
        reduction: prop_reduction_grade.value
    };
    // Send data
    try {
        await requestPUT('/grade.php', data);
        alert('Grade mis à jour avec succès.');
        selectGrade(id_grade);
    } catch (error) {
        alert('Erreur lors de la mise à jour du grade.');
    }

}

/**
 * Loads and displays grade information based on the provided grade ID.
 *
 * @param {number} id_grade - The ID of the grade to be selected.
 * @returns {Promise<void>} A promise that resolves when the grade information has been fetched and displayed.
 */
async function selectGrade(id_grade){

    // Fetch grade information
    const grade = await requestGET(`/grade.php?id=${id_grade}`);

    // Update displayed information
    prop_image_grade.src = grade.image_grade;
    prop_nom_grade.value = grade.nom_grade;
    prop_description_grade_grade.value = grade.description_grade;
    prop_prix_grade.value = grade.prix_grade;
    prop_reduction_grade.value = grade.reduction_grade;

    // Update save button
    save_btn.addEventListener('click', ()=>{
        saveGrade(id_grade);
    });
    
}