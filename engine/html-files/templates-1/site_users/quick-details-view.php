<?php
    if( isset( $data['user_details'] ) && is_array( $data['user_details'] ) && ! empty( $data['user_details'] ) ){
?>
<div class="alert alert-block alert-error quick-details-view">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <?php $key = 'user_details'; ?>
    <h4><?php $field = 'id'; echo $data[$key][$field]; ?></h4>
    <?php foreach( $data[$key] as $dk => $dv ){ 
        if( ! is_array($dv) ){
            
            switch( $dk ){
            case 'id':
            break;
            case 'email':
            case 'firstname':
            case 'lastname':
            case 'phonenumber':
    ?>
    <p>
        <strong><?php echo ucwords( str_replace('_',' ',$dk) ); ?>: </strong>
        <?php echo $dv; ?>
    </p>
        <?php 
            break;
            }
        } ?>
    <?php } ?>
    
    <?php if( isset($data['merchant_accounts']['active']) && $data['merchant_accounts']['active'] && isset( $data['merchant_accounts']['accounts'][ $data['merchant_accounts']['active'] ] ) ){ ?>
        <hr />
        <p><strong>Active Bank Account</strong></p>
        <?php foreach( $data['merchant_accounts']['accounts'][ $data['merchant_accounts']['active'] ] as $ak => $av ){ ?>
            <?php
                switch( $ak ){
                case 'account_name':
                case 'account_number':
                case 'bank_name':
                    ?>
                    <p>
                        <strong><?php echo ucwords( str_replace('_',' ',$ak) ); ?>: </strong>
                        <?php echo $av; ?>
                    </p>
                    <?php
                break;
                case 'branch_country':
                    ?>
                    <p>
                        <strong><?php echo ucwords( str_replace('_',' ',$ak) ); ?>: </strong>
                        <?php echo get_select_option_value( array( 'id' => $av , 'function_name' => 'get_countries' ) ); ?>
                    </p>
                    <?php
                break;
                }
         } ?>
    <?php } ?>
    <p>&nbsp;</p>
    
    
    <div class="accordion-group">
    <div class="accordion-heading">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#side-nav" href="#uaddress"> Address<i class="icon-chevron-down pull-right"></i></a>
    </div>
    <div id="uaddress" class="accordion-body collapse">
        <div class="accordion-inner">   
            <?php $field = 'address_html'; echo $data[$key][$field]; ?>
        </div>
    </div>
    </div>
            
    <div class="accordion-group">
    <div class="accordion-heading">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#side-nav" href="#pdetails"> More Details<i class="icon-chevron-down pull-right"></i></a>
    </div>
    <div id="pdetails" class="accordion-body collapse">
        <div class="accordion-inner">   
            
            <div class="table-responsive">
            <table class="table table-striped">
            <?php foreach( $data[$key] as $dk => $dv ){
                if( ! is_array($dv) ){
                    switch( $dk ){
                    case 'id':
                    case 'email':
                    case 'firstname':
                    case 'lastname':
                    case 'password':
                    case 'confirmpassword':
                    case 'address_status':
                    break;
                    default:
            ?>
            <tr>
                <th><span><?php echo ucwords( str_replace('_',' ',$dk) ); ?></span></th>
                <td><span><?php echo $dv; ?></span></td>
            </tr>
                <?php 
                    break;
                    }
                } ?>
            <?php } ?>
            </table>
            </div>
        </div>
    </div>
    </div>
    
    <?php if( isset($data['merchant_accounts']['accounts']) && is_array($data['merchant_accounts']['accounts']) && !empty( $data['merchant_accounts']['accounts'] ) ){ ?>
    <div class="accordion-group">
    <div class="accordion-heading">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#side-nav" href="#uacc"> Bank Accounts<i class="icon-chevron-down pull-right"></i></a>
    </div>
    <div id="uacc" class="accordion-body collapse">
        <div class="accordion-inner">   
        <ol>
        <?php
        foreach( $data['merchant_accounts']['accounts'] as $item_id => $d ){
            ?>
            <li><?php echo $d['account_name']; ?>
            <div class="table-responsive">
            <table class="table table-striped">
            <?php foreach( $d as $dk => $dv ){ 
                if( ! is_array($dv) ){
            ?>
            <tr>
                <th><span><?php echo ucwords( str_replace('_',' ',$dk) ); ?></span></th>
                <td><span><?php 
                    switch( $dk ){
                    case 'branch_country':
                        echo get_select_option_value( array( 'id' => $dv , 'function_name' => 'get_countries' ) );
                    break;
                    default:
                        echo $dv; 
                    break;
                    }
                ?></span></td>
            </tr>
                <?php } ?>
            <?php } ?>
            </table>
            </div>
            </li>
            <?php
        }
        ?>
        </ol>
    </div>
    </div>
    </div>
    <?php } ?>
    
</div>
<?php
    }else{
 ?>
<div class="alert alert-block alert-warning quick-details-view">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <h4 class="alert-heading">Missing Information!!!</h4>
    <p>Could not retrieve details of the selected item.</p>
</div>
<?php 
    }
?>