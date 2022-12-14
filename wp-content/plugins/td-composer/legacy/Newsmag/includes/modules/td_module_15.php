<?php

/**
 * this module is similar to single
 * Class td_module_15
 */

class td_module_15 extends td_module_single {

    function __construct($post) {
        //run the parrent constructor
        parent::__construct($post);
    }

    function render() {
        ob_start();
        
        $modified_date = $this->get_shortcode_att('show_modified_date');
        
        ?>

        <div class="<?php echo $this->get_module_classes(array_merge(get_post_class(), array("td-post-content"))); ?>">
            <div class="item-details">
                <?php echo $this->get_title();?>
                <div class="meta-info">
	                <?php if (td_util::get_option('tds_category_module_15') == 'yes') { echo $this->get_category(); }?>
                    <?php echo $this->get_author();?>
                    <?php echo $this->get_date($modified_date);?>
                    <?php echo $this->get_comments();?>
                </div>

                <?php echo $this->get_image('td_640x0');?>

	            <div class="td-post-text-content">
		            <?php echo $this->get_content();?>
	            </div>
            </div>

        </div>

        <?php return ob_get_clean();
    }
}

?>