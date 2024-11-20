import { clearNavbar, addNavbarItem, selectFirstNavbarItem  } from "./navbar.js";
import { requestGET } from './ajax.js';

// Get inputs
const prop_image_grade = document.getElementById('prop_image_grade');
const prop_nom_grade = document.getElementById('prop_nom_grade');
const description_grade_grade = document.getElementById('description_grade_grade');
const prop_prix_grade = document.getElementById('prop_prix_grade');
const prop_reduction_grade = document.getElementById('prop_reduction_grade');

// Add navbar items for each grade
clearNavbar()
const grades = await requestGET('/grades.php');
for (let i = 0; i < grades.length; i++) {
    const grade = grades[i];
    addNavbarItem(grade.nom_grade, ()=>{
        selectGrade(grade.id_grade)
    });
}
selectFirstNavbarItem();


async function selectGrade(id_grade){

    // Fetch grade information
    const grade = await requestGET(`/grades.php?id=${id_grade}`);

    // Update displayed information
    prop_image_grade.src = grade.image_grade;
    prop_nom_grade.value = grade.nom_grade;
    prop_prix_grade.value = grade.prix_grade;
    
}