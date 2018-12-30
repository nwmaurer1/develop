<?php



print '<pre>';

function printDataStructure($data)
{
    foreach($data as $key => $value) {
         if (is_array($value)) {
             if (is_string($key)) {
                 print_r("key: " . $key . "<br>");
             }
             printDataStructure($value);
         } else {
             print_r("key: " . $key . ", value: " . $value . ", <br>");
         }
    }

    return;
}

printDataStructure($example);