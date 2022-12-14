<?php
/**
 * Created by PhpStorm.
 * User: tagdiv
 * Date: 03.02.2015
 * Time: 10:05
 */

class td_module_mx8 extends td_module {

    function __construct($post, $module_atts = array()) {
        parent::__construct($post, $module_atts);
    }

    function render($order_no) {
        ob_start();

        $title_length = $this->get_shortcode_att('mx8_tl');
        $modified_date = $this->get_shortcode_att('show_modified_date');
        ?>

        <div class="<?php echo $this->get_module_classes(array("td-big-grid-post-$order_no", "td-big-grid-post", 'td-medium-thumb')); ?>">
            <?php
                echo $this->get_image('td_341x400');
            ?>
            <div class="td-meta-info-container">
                <div class="td-meta-align">
                    <div class="td-big-grid-meta">
                        <?php if (td_util::get_option('tds_category_module_mx8') == 'yes') { echo $this->get_category(); }?>
                        <?php echo $this->get_title($title_length);?>

                        <div class="td-module-meta-info">
                            <?php echo $this->get_author();?>
                            <?php echo $this->get_date($modified_date);?>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo $this->get_comments();?>

        </div>

        <?php return ob_get_clean();
    }
}