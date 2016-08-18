<?php
    foreach ($data as $data) {
        echo "<option data-address='".$data['adress']."' value='".$data['id']."'>".$data['name']." (".$data['adress'].")</option>";
    }
?>


