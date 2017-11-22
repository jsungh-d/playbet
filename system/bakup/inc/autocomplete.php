<?php

$result = array();
for($i=0; $i < 1001; $i++){
    $result[] = array(
        'number' => $i,
        'name' => 'test'.$i
    );
}

echo json_encode($result);
?>