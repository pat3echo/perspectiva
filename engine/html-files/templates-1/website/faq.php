<div class="container">
<div class="row-fluid">
	<div class="span12">
        <fieldset class="special-format">
            <legend>Frequently Asked Questions</legend>
            
            <div class="row-fluid">
                <div class="span6">
                    <div class="row">
                        <div class="span2">
                            <div style="text-align:center; width:100%; vertical-align:middle; margin-top:10px;">
                                <img src="<?php echo $data['pagepointer']; ?>images/support/kb.png" />
                            </div>
                        </div>
                        <div class="span6">
                            <p class="muted1">
                            Find solutions to similar issues
                            <hr />
                            </p>
                            <p class="lead">
                            Expore Our Knowledgebase
                            </p>
                        </div>
                    </div>
                    <!-- ./row -->
                    <div class="row" style="padding:0 10px;">
                        <div class="input-append">
                            <form method="get" action="?action=support_enquiry&todo=search_faq" id="faq-search-form">
                            <input type="text" placeholder="Search Knowledgebase" id="appendedInputButton" class="form-gen-element span4" style="border-radius:0; border-right:none;" name="s" value="<?php if( isset($_GET['s']) && $_GET['s'] )echo $_GET['s']; ?>" />
                            <select class="form-gen-element" style="border-radius:0;" name="categories">
                                <option value="all">Categories</option>
                                <option value="">-------------</option>
                                <?php
                                    $cat = '';
                                    if( isset($_GET['categories']) && $_GET['categories'] )
                                        $cat = $_GET['categories'];
                                        
                                    if( isset( $data[ 'faq_categories' ] ) && is_array( $data[ 'faq_categories' ] ) ){
                                        foreach( $data[ 'faq_categories' ] as $k => $v ){
                                            $selected = '';
                                            if( $cat == $k )$selected = ' selected="selected" ';
                                        ?>
                                        <option value="<?php echo $k; ?>" <?php echo $selected; ?>><?php echo ucwords( $v ); ?></option>
                                        <?php
                                        }
                                    }
                                ?>
                            </select>
                            <input type="submit" class="btn btn-primary" value="Go" style="padding-bottom:9px;" />
                            </form>
                        </div>
                    </div>
                    <!-- ./row -->
                </div>
                <!-- ./span3 -->
                <div class="span6">
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row">
                                <div class="span3">
                                    <div style="text-align:center; width:100%; vertical-align:middle; margin-top:10px;">
                                        <img src="<?php echo $data['pagepointer']; ?>images/support/icon-buying.png" />
                                    </div>
                                </div>
                                <!-- ./span3 -->
                                <div class="span6">
                                    <p><strong><a href="#" class="faq-category-nav" value="1">Buying</a></strong><p>
                                    <p>
                                        Find solutions to similar issues
                                    </p>
                                </div>
                                <!-- ./span6 -->
                            </div>
                            <!-- ./row -->
                        </div>
                        <!-- ./span6 -->
                        <div class="span6">
                            
                            <div class="row">
                                <div class="span3">
                                    <div style="text-align:center; width:100%; vertical-align:middle; margin-top:10px;">
                                        <img src="<?php echo $data['pagepointer']; ?>images/support/icon-delivery.png" />
                                    </div>
                                </div>
                                <!-- ./span3 -->
                                <div class="span6">
                                    <p><strong><a href="#" class="faq-category-nav" value="4">Delivery</a></strong><p>
                                    <p>
                                        Find solutions to similar issues
                                    </p>
                                </div>
                                <!-- ./span6 -->
                            </div>
                            <!-- ./row -->
                            
                        </div>
                        <!-- ./span6 -->
                    </div>
                    <!-- ./row -->
                    <hr />
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row">
                                <div class="span3">
                                    <div style="text-align:center; width:100%; vertical-align:middle; margin-top:10px;">
                                        <img src="<?php echo $data['pagepointer']; ?>images/support/icon-selling.png" />
                                    </div>
                                </div>
                                <!-- ./span3 -->
                                <div class="span6">
                                    <p><strong><a href="#" class="faq-category-nav" value="2">Selling</a></strong><p>
                                    <p>
                                        Find solutions to similar issues
                                    </p>
                                </div>
                                <!-- ./span6 -->
                            </div>
                            <!-- ./row -->
                        </div>
                        <!-- ./span6 -->
                        <div class="span6">
                            
                            <div class="row">
                                <div class="span3">
                                    <div style="text-align:center; width:100%; vertical-align:middle; margin-top:10px;">
                                        <img src="<?php echo $data['pagepointer']; ?>images/support/icon-payment.png" />
                                    </div>
                                </div>
                                <!-- ./span3 -->
                                <div class="span6">
                                    <p><strong><a href="#" class="faq-category-nav" value="3">Payment</a></strong><p>
                                    <p>
                                        Find solutions to similar issues
                                    </p>
                                </div>
                                <!-- ./span6 -->
                            </div>
                            <!-- ./row -->
                            
                        </div>
                        <!-- ./span6 -->
                    </div>
                    <!-- ./row -->
                </div>
                <!-- ./span9 -->
            </div>
            <!-- ./row -->
            
        </fieldset>
	</div>
</div>
<!-- ./row -->

<div class="row-fluid">
	<div class="span6" id="faq-container">
        <?php include dirname( dirname( __FILE__ ) ) . "/globals/faq-list.php"; ?>
	</div>
	<!-- ./span3 -->
	<div class="span6 well" id="faq-display-box">
        <div style="text-align:center; width:100%; vertical-align:middle;">
            <img src="<?php echo $data['pagepointer']; ?>images/support/promo-image.png" />
        </div>
    </div>
    <!-- ./span9 -->
</div>
<!-- ./row -->

</div>
<!-- ./container -->