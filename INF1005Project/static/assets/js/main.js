/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/ClientSide/javascript.js to edit this template
 */

$(document).ready(function(){
    backendCatalogueToggles();
    CreditCardFormat();
});

function CreditCardFormat(){
    var cardnum = document.getElementById("cardnumber");
    var index = cardnum.value.lastIndexOf('-');
    var x = cardnum.value.substr(index+1);
    if (cardnum.value.length === 19)
        ;
    else if (x.length === 4)
        cardnum.value = cardnum.value + '-';
}

function backendCatalogueToggles() {
    // Defining variables for the buttons found within the form
    const add_btn = document.getElementsByClassName("backend-catalogue-add-header")[0].children[1].children[0];
    const close_btn = document.getElementsByClassName("backend-catalogue-add-header")[0].children[1].children[1];
    const add_form = document.getElementsByClassName("backend-catalogue-add-form")[0];
    
    // Event Listeners for "Add" and "Close" buttons under the Add Product section
    add_btn.addEventListener("click", () => { displayHideToggle(add_btn, close_btn, add_form); });
    close_btn.addEventListener("click", () => { displayHideToggle(add_btn, close_btn, add_form); });
}

function displayHideToggle(add_btn, close_btn, add_form) {
    
    // Checks if "Add" button is displayed
    if (add_btn.classList.contains("d-none")) {
        // "Add" button NOT displayed
        
        // Clears all populated fields that weren't submitted
        const inputDIVs = add_form.children[0].children[0].children[0].children[0].children;
        for (let i = 1; i < inputDIVs.length - 1; i++) {
            let input = inputDIVs[i].children[1];
            input.value = "";
        }
        
        // Hides "Close" button & Form and displays "Add" button
        close_btn.classList.add("d-none");
        add_form.classList.add("d-none");
        add_btn.classList.remove("d-none");

    } else {
        // "Add" button NOT displayed
        
        // Hides "Add" button and displays "Close" button & Form
        close_btn.classList.remove("d-none");
        add_form.classList.remove("d-none");
        add_btn.classList.add("d-none");
    }
        
}