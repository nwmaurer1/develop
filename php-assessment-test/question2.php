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
 * Sorts Data Structures by a key or keys regardless of the items level.
 *
 * @param    array  $data An Array of Objects
 * @param    array  $sortingKeys An Array of keys to sort $data. It can be a string instead
 * @return      array $data An Array of objects that is sorted
 *
 */
function sortDataStructureByKey($data, $sortingKeys)
{

    if(!is_array($sortingKeys)) {
        $sortingKeys = array($sortingKeys);
    }
    //Takes the DataStructure and compares each array and it's nested key values
    //with $a and $b as each individual array within the DataStructure
    usort($data, function($a, $b) use($sortingKeys) {
        $returnVal = 0;
        foreach($sortingKeys as $sortingKey) {
            if (!array_key_exists($sortingKey, $a)) {
                $keys = array_keys($a);
                foreach ($keys as $key) {
                    if (is_array($a[$key])) {
                        foreach ($a[$key] as $k =>$child) {
                            if (array_key_exists($sortingKey, $child)) {
                                if($returnVal == 0) {
                                    $returnVal = strnatcmp($a[$key][$k][$sortingKey],$b[$key][$k][$sortingKey]);
                                }
                            }
                        }
                    }
                }
            } else {
                if($returnVal == 0) {
                    $returnVal = strnatcmp($a[$sortingKey],$b[$sortingKey]);
                }
            }

        }
        return $returnVal;
    });
    return $data;
}

//Test Bench.

$data = sortDataStructureByKey($example, ['room_no','account_id']);
$data1 = sortDataStructureByKey($example, 'room_no');
echo '<pre>';
print_r($data);

echo "\n\n";
print_r($data1);