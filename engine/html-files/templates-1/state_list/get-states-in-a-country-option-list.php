<?php
    $key = 'states';
    if( isset($data[$key]) && is_array( $data[$key] ) ){
        foreach( $data[$key] as $id => $val ){
        ?>
        <option value="<?php echo $id; ?>"><?php echo $val['state']; ?></option>
        <?php
        }
    }else{
    ?>
        <option value="all">All States / Provinces</option>
    <?php
    }
?>