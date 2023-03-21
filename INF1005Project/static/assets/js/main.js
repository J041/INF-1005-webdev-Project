/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/ClientSide/javascript.js to edit this template
 */

$(document).ready(function () {
    backendCatalogueToggles();
    CreditCardFormat();
});

function CreditCardFormat() {
    var cardnum = document.getElementById("cardnumber");
    var index = cardnum.value.lastIndexOf('-');
    var x = cardnum.value.substr(index + 1);
    if (cardnum.value.length === 19)
        ;
    else if (x.length === 4)
        cardnum.value = cardnum.value + '-';
}

function backendCatalogueToggles() {
    // Defining variables for the buttons under the Add Product section
    const add_btn = document.getElementsByClassName("backend-catalogue-add-header")[0].children[1].children[0];
    const close_btn = document.getElementsByClassName("backend-catalogue-add-header")[0].children[1].children[1];
    const save_btn = document.getElementsByClassName("backend-catalogue-add-form-save")[0].children[0];
    const add_form = document.getElementsByClassName("backend-catalogue-add-form")[0];

    // Defining variables for the buttons under the Update Product section
    const edit_btns = document.getElementsByClassName("backend-catalogue-details-edit");
    const close_edit_btns = document.getElementsByClassName("backend-catalogue-details-close");
    
    // Event Listeners for "Add" and "Close" buttons under the Add Product section
    add_btn.addEventListener("click", () => {
        displayHideToggleAdd(add_btn, close_btn, add_form);
    });
    close_btn.addEventListener("click", () => {
        displayHideToggleAdd(add_btn, close_btn, add_form);
    });
    save_btn.addEventListener("click", () => {
        reloadPage(add_btn, close_btn, save_btn, add_form);
    });

    // Event Listeners for "Edit" buttons under the Edit Product section
    Array.prototype.forEach.call(edit_btns, function (element) {
        element.addEventListener('click', () => {
            displayHideToggleEdit(element);
        });
    });

    // Event Listeners for "Close" buttons under the Edit Product section
    Array.prototype.forEach.call(close_edit_btns, function (element) {
        element.addEventListener('click', () => {
            displayHideToggleEdit(element);
        });
    });

}

function displayHideToggleAdd(add_btn, close_btn, add_form) {

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
        // "Add" button displayed
        // Hides "Add" button and displays "Close" button & Form
        close_btn.classList.remove("d-none");
        add_form.classList.remove("d-none");
        add_btn.classList.add("d-none");
    }

}

function displayHideToggleEdit(element) {
    let edit_btn = "";
    let close_edit_btn = "";
    let display_edit_form = "";
    let edit_form = "";
    
    // Identifies if either the "Edit" or "Close" button was clicked.
    if (element === element.parentNode.children[0]) {
        edit_btn = element;
        close_edit_btn = element.parentNode.children[1];
        display_edit_form = element.parentNode.parentNode.parentNode.children[2];
        edit_form = element.parentNode.parentNode.parentNode.children[3];

        // "Edit" button NOT displayed
        // Hides "Edit" button & 'Display Update' Form. Displays "Close" button & 'Edit' Form.
        edit_btn.classList.add("d-none");
        display_edit_form.classList.add("d-none");
        close_edit_btn.classList.remove("d-none");
        edit_form.classList.remove("d-none");


    } else {
        edit_btn = element.parentNode.children[0];
        close_edit_btn = element;
        display_edit_form = element.parentNode.parentNode.parentNode.children[2];
        edit_form = element.parentNode.parentNode.parentNode.children[3];

        // "Edit" button displayed
        // Hides "Close" button & 'Edit' Form. Displays "Edit" button & 'Display Update' Form. 
        edit_btn.classList.remove("d-none");
        display_edit_form.classList.remove("d-none");
        close_edit_btn.classList.add("d-none");
        edit_form.classList.add("d-none");

    }
}

// Work in Progress 
function reloadPage(add_btn, close_btn, save_btn, add_form) {
    console.log(php_error_msg[0]);
}