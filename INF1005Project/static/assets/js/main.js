/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/ClientSide/javascript.js to edit this template
 */

function CreditCardFormat(){
    var cardnum = document.getElementById("cardnumber");
    var index = cardnum.value.lastIndexOf('-');
    var x = cardnum.value.substr(index+1);
    if (cardnum.value.length === 19)
        ;
    else if (x.length === 4)
        cardnum.value = cardnum.value + '-';
}
