/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/ClientSide/javascript.js to edit this template
 */

// Intitializing JS Functions
$(document).ready(function () {
    catalogueToggles();
});

$(document).ready(function () {
    backendCatalogueToggles();
});

$(document).ready(function () {
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
        displayHideToggle(add_btn, add_btn, close_btn, "", add_form);
    });
    close_btn.addEventListener("click", () => {
        displayHideToggle(close_btn, close_btn, add_btn, add_form, "");
    });
    save_btn.addEventListener("click", () => {
        reloadPage(add_btn, close_btn, save_btn, add_form);
    });

    // Event Listeners for "Edit" buttons under the Edit Product section
    Array.prototype.forEach.call(edit_btns, function (element, main_btn, secondary_btn, main_display, secondary_display) {
        element.addEventListener('click', () => {
            main_btn = element;
            secondary_btn = element.parentNode.children[1];
            main_display = element.parentNode.parentNode.parentNode.children[2];
            secondary_display = element.parentNode.parentNode.parentNode.children[3];

            displayHideToggle(element, main_btn, secondary_btn, main_display, secondary_display);
        });
    });
    
    // Event Listeners for "Close" buttons under the Edit Product section
    Array.prototype.forEach.call(close_edit_btns, function (element, main_btn, secondary_btn, main_display, secondary_display) {
        element.addEventListener('click', () => {
            main_btn = element;
            secondary_btn = element.parentNode.children[0];
            main_display = element.parentNode.parentNode.parentNode.children[3];
            secondary_display = element.parentNode.parentNode.parentNode.children[2];

            displayHideToggle(element, main_btn, secondary_btn, main_display, secondary_display);
        });
    });

}

function catalogueToggles() {
    // Defining variables for the buttons under the Catalogue - "Add" Review section
    const new_review_add_btns = document.getElementsByClassName("new-review-add");
    const new_review_add_close_btns = document.getElementsByClassName("new-review-add-close");

    // Event Listeners for "Add" buttons under the Add Review section
    Array.prototype.forEach.call(new_review_add_btns, function (element, main_btn, secondary_btn, main_display, secondary_display) {
        element.addEventListener('click', () => {
            // Variables to various buttons/containers
            main_btn = element;
            secondary_btn = element.parentNode.children[1];
            main_display = "";
            secondary_display = element.parentNode.parentNode.parentNode.children[8];

            displayHideToggle(element, main_btn, secondary_btn, main_display, secondary_display);
        });
    });

    // Event Listeners for "Close" buttons under the Add Review section
    Array.prototype.forEach.call(new_review_add_close_btns, function (element, main_btn, secondary_btn, main_display, secondary_display) {
        element.addEventListener('click', () => {
            // Variables to various buttons/containers
            main_btn = element;
            secondary_btn = element.parentNode.children[0];
            main_display = element.parentNode.parentNode.parentNode.children[8];
            secondary_display = "";

            displayHideToggle(element, main_btn, secondary_btn, main_display, secondary_display);
        });
    });

}

function displayHideToggle(element, main_btn, secondary_btn, main_display, secondary_display) {
    console.log("inside function");
    if (element.classList.contains("disabled")) {
        console.log("Button is disabled.");
    } else {
        if (element === main_btn) {
            if (main_btn.classList.contains("d-none")) {
                // Hides Secondary Button & Display, reveals Main Button & Display

                main_btn.classList.remove("d-none");
                // Checks is Main Display Exists
                if (main_display) {
                    main_display.classList.remove("d-none");
                }

                secondary_btn.classList.add("d-none");
                if (secondary_display) {
                    secondary_display.classList.add("d-none");
                }
            } else {
                // Reveals Secondary Button & Display, hides Main Button & Display

                main_btn.classList.add("d-none");
                // Checks is Main Display Exists
                if (main_display) {
                    main_display.classList.add("d-none");
                }

                secondary_btn.classList.remove("d-none");
                if (secondary_display) {
                    secondary_display.classList.remove("d-none");
                }
            }
        } else {
            console.log('Unknown');
        }
    }

}

// Work in Progress 
function reloadPage(add_btn, close_btn, save_btn, add_form) {
    console.log(php_error_msg[0]);
}