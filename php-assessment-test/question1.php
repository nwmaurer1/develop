<?php

$example = array (
    array (
        'guest_id' => 177,
        'guest_type' => 'crew',
        'first_name' => 'Marco',
        'middle_name' => NULL,
        'last_name' => 'Burns',
        'gender' => 'M',
        'guest_booking' => array (
            array (
                'booking_number' => 20008683,
                'ship_code' => 'OST',
                'room_no' => 'A0073',
                'start_time' => 1438214400,
                'end_time' => 1483142400,
                'is_checked_in' => true,
            ),
        ),
        'guest_account' => array (
            array (
                'account_id' => 20009503,
                'status_id' => 2,
                'account_limit' => 0,
                'allow_charges' => true,
            ),
        ),
    ),
    array (
        'guest_id' => 10000113,
        'guest_type' => 'crew',
        'first_name' => 'Bob Jr ',
        'middle_name' => 'Charles',
        'last_name' => 'Hemingway',
        'gender' => 'M',
        'guest_booking' => array (
            array (
                'booking_number' => 10000013,
                'room_no' => 'B0092',
                'is_checked_in' => true,
            ),
        ),
        'guest_account' => array (
            array (
                'account_id' => 10000522,
                'account_limit' => 300,
                'allow_charges' => true,
            ),
        ),
    ),
    array (
        'guest_id' => 10000114,
        'guest_type' => 'crew',
        'first_name' => 'Al ',
        'middle_name' => 'Bert',
        'last_name' => 'Santiago',
        'gender' => 'M',
        'guest_booking' => array (
            array (
                'booking_number' => 10000014,
                'room_no' => 'A0018',
                'is_checked_in' => true,
            ),
        ),
        'guest_account' => array (
            array (
                'account_id' => 10000013,
                'account_limit' => 300,
                'allow_charges' => true,
            ),
        ),
    ),
    array (
        'guest_id' => 10000115,
        'guest_type' => 'crew',
        'first_name' => 'Red ',
        'middle_name' => 'Ruby',
        'last_name' => 'Flowers ',
        'gender' => 'F',
        'guest_booking' => array (
            array (
                'booking_number' => 10000015,
                'room_no' => 'A0051',
                'is_checked_in' => true,
            ),
        ),
        'guest_account' => array (
            array (
                'account_id' => 10000519,
                'account_limit' => 300,
                'allow_charges' => true,
            ),
        ),
    ),
    array (
        'guest_id' => 10000116,
        'guest_type' => 'crew',
        'first_name' => 'Ismael ',
        'middle_name' => 'Jean-Vital',
        'last_name' => 'Jammes',
        'gender' => 'M',
        'guest_booking' => array (
            array (
                'booking_number' => 10000016,
                'room_no' => 'A0023',
                'is_checked_in' => true,
            ),
        ),
        'guest_account' => array (
            array (
                'account_id' => 10000015,
                'account_limit' => 300,
                'allow_charges' => true,
            ),
        ),
    ),
);


/**
 *
 * Prints the Data Structure regardless of level in a easy display.
 *
 * @param    array  $data An Array of Objects
 * @return   void
 *
 */
function printDataStructure($data)
{
    foreach($data as $key => $value) {
         if (is_array($value)) {
             if (is_string($key)) {
                 print_r("The array key is " . $key . "<br>");
             }
             printDataStructure($value);
         } else {
             print_r( $key . "'s value is " . $value . ", <br>");
         }
    }

    return;
}

//Test Bench

print '<pre>';
printDataStructure($example);