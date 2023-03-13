<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function sanitize_regex_input($data) {
    // Strips whitespace on both sides of string, slashes and converts special characters to HTML format
    $data = sanitize_input($data);

    // Regular Expression that only allow accepts alphanumeric and whitespace characters
    if ($data == " ") {
        $data = "";
    } elseif (preg_match('/[^A-Za-z0-9 ]/', $data)) {
        echo preg_match('/[^A-Za-z0-9 ]/', $data);
        return "Unidentified Character";
    } else {
        echo preg_match('/[^A-Za-z0-9 ]/', $data);
        return "No Issues!";
    }
}

?>