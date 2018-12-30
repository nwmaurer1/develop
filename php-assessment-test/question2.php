<?php



function sortDataStructureByKey(&$data, $sortingkeys = array(), $first_pass = true)
{
    $temp_array = [];
    if (is_array($sortingkeys)) {
        foreach ($sortingkeys as $sortingkey) {
            echo "<pre>";
            print_r($temp_array);
            $temp_array1 = array_column($data, $sortingkey);
            if (empty($temp_array1)) {
                $temp_array2 = $temp_array;
                foreach ($data as $array) {
                    foreach ($array as $key => $value) {
                        if (is_array($value)) {
                            $temp_array[] = sortDataStructureByKey($value, $sortingkey, false);
                        }
                    }
                }
                echo "<pre>";
                print_r($temp_array1);
                $temp_array1 = array_column($temp_array, $sortingkey);
                echo "<pre>";
                print_r($temp_array1);
                $temp_array = array_merge_recursive($temp_array2, $temp_array1);
                echo "<pre>";
                print_r($temp_array);
                array_multisort($temp_array, SORT_ASC, $data);
            } else {
                $temp_array = array_merge_recursive($temp_array, $temp_array1);
                echo "<pre>";
                print_r($temp_array);
                array_multisort($temp_array, SORT_ASC, $data);
            }
        }
    } else {
        if (!$first_pass && array_key_exists($sortingkeys, $data)) {
            return $data;
        } else if ($first_pass) {
            $temp_array = array_column($data, $sortingkeys);
            if (empty($temp_array)) {
                foreach ($data as $array) {
                    foreach ($array as $key => $value) {
                        if (is_array($value)) {
                            $temp_array[] = sortDataStructureByKey($value, $sortingkeys, false);
                        }
                    }
                }
                $temp_array = array_column($temp_array, $sortingkeys);
                array_multisort($temp_array, SORT_ASC, $data);
            } else {
                array_multisort($temp_array, SORT_ASC, $data);
            }
        } else {
            foreach ($data as $child) {
                if (is_array($child)) {
                    $temp_array = sortDataStructureByKey($child, $sortingkeys, false);
                    if (isset($temp_array)) {
                        return $temp_array;
                    }
                }
            }
            return [];
        }
    }
    echo '<pre>';
    print_r($data);
}



$data = sortDataStructureByKey($example, ['room_no','account_id']);
$data1 = sortDataStructureByKey($example, 'room_no');
echo '<pre>';
print_r($data);

echo "\n\n";
print_r($data1);
//sortDataStructureByKey($example, ['last_name', 'account_id']);