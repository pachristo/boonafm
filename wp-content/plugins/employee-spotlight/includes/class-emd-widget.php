<?php
/**
 * Class for frontend widgets
 *
 * @package     EMD
 * @copyright   Copyright (c) 2014, Emarket Design
 * @since       1.0
 */
if (!defined('ABSPATH')) exit;
/**
 * Emd_Widget Class
 *
 * Widget class to setup and display frontend widgets.
 *
 * @since WPAS 4.0
 */
class Emd_Widget extends WP_Widget {
	/**
	 * Instantiate widget class
	 * @since WPAS 4.0
	 *
	 * @param string $title
	 * @param string $class_label
	 * @param string $description
	 *
	 */
	public function __construct($id, $title, $class_label, $description) {
		$this->title = $title;
		$this->class_label = $class_label;
		parent::__construct($id, $this->title, array(
			'description' => $description,
		));
	}
	/**
	 * Widget display on frontend
	 * @since WPAS 4.0
	 *
	 * @param array $args
	 * @param array $instance
	 *
	 */
	public function widget($args, $instance) {
		if (!isset($args['id'])) {
			$args['id'] = $this->id;
		}
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$count = $instance['count'];
		echo $before_widget;
		$pids = Array();
		$app = str_replace('-', '_', $this->text_domain);
		$front_ents = emd_find_limitby('frontend', $app);
		if(!empty($front_ents) && in_array($this->class,$front_ents) && $this->type != 'integration'){
			$pids = apply_filters('emd_limit_by', $pids, $app, $this->class,'frontend');
		}
		if ($this->type == 'entity') {
			$args['filter'] = $this->filter;
			$args['has_pages'] = $instance['pagination'];
			$args['posts_per_page'] = $instance['count_per_page'];
			$args['pagination_size'] = $instance['pagination_size'];
			$args['class'] = $this->class;
			$args['cname'] = get_class($this);
			$args['app'] = str_replace("-","_",$this->text_domain);
			$args['query_args'] = $this->query_args;
			$widg_layout = self::get_ent_widget_layout($count, $pids,$args);
		} elseif ($this->type == 'comment') {
			$widg_layout = $this->get_comm_widget_layout($count, $pids);
		}
		elseif($this->type == 'integration') {
			$widg_layout = $this->layout();
		}
		if ($widg_layout) {
			if($this->type != 'integration'){
				$this->get_header_footer();
			}
			echo "<div class='emd-container'>";
			if ($title) {
				echo $before_title . $title . $after_title;
			}
			echo "<div class='emd-widg-results' id='wres-" . $this->id . "'>";
			echo '<input type="hidden" id="emd_app" value="' . $app . '">';
			if($this->type == 'comment'){
				echo "<ul class='" . esc_attr($this->css_label) . "-list emd-widget'>";
			}
			elseif($this->type != 'integration'){
				echo $this->header;
			}
			echo $widg_layout;
			if($this->type == 'comment'){
				echo "</ul>";
			}
			elseif($this->type != 'integration'){
				echo $this->footer;
			}
			echo "</div>";
			echo "</div>";
		}
		echo $after_widget;
		$this->enqueue_scripts();
	}
	/**
	 * Widget update from admin
	 * @since WPAS 4.0
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array $instance
	 */
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = ( int )$new_instance['count'];
		$instance['pagination'] = ( int )$new_instance['pagination'];
		if($new_instance['pagination']){
			$instance['count_per_page'] = ( int )$new_instance['count_per_page'];
			$instance['pagination_size'] = $new_instance['pagination_size'];
		}
		else {
			$instance['count_per_page'] = '';
			$instance['pagination_size'] = '';
		}
		return $instance;
	}
	/**
	 * Widget form in admin
	 * @since WPAS 4.0
	 *
	 * @param array $instance
	 *
	 */
	public function form($instance) {
		$defaults = array(
			'title' => $this->title,
			'count' => 5,
			'count_per_page' => 5,
			'pagination' => 0,
			'pagination_size' => '',
		);
		$instance = wp_parse_args(( array )$instance, $defaults);
		if (( int )$instance['count'] < 1) {
			( int )$instance['count'] = 5;
		}
		if ($instance['pagination'] == 1){
			if((int)$instance['count_per_page'] < 1) {
				( int )$instance['count_per_page'] = 5;
			}
		}
		elseif (empty($instance['pagination'])){
			$paginate_style = ' style="display:none;"';
		}
?>
			<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'emd-plugins'); ?></label><br />
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
			</p>
		<?php if($this->type != 'integration'){			?>
			<p>
			<label for="<?php echo $this->get_field_id('count'); ?>"><?php printf(__('Max number of %s to show', 'emd-plugins') , $this->class_label); ?></label>
			<input type="text" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" value="<?php echo $instance['count']; ?>" size="3" maxlength="4" /> <br>
			</p>
			<p>
			<input type="checkbox" class="emd-enable-pagination" id="<?php echo $this->get_field_id('pagination'); ?>" name="<?php echo $this->get_field_name('pagination'); ?>" value="1" 
			<?php if($instance['pagination']) { echo 'checked'; } ?>/> 
			<label for="<?php echo $this->get_field_id('pagination'); ?>"><?php printf(__('Enable pagination', 'emd-plugins') , $this->class_label); ?></label>
			</p>
			<p class="emd-paginate-show" <?php echo $paginate_style; ?>>
			<label for="<?php echo $this->get_field_id('pagination_size'); ?>"><?php echo __('Pagination size', 'emd-plugins'); ?></label>
			<select id="<?php echo $this->get_field_id('pagination_size'); ?>" name="<?php echo $this->get_field_name('pagination_size'); ?>">
			<option value="small" <?php if($instance['pagination_size'] == 'small') { echo 'selected'; } ?>><?php echo __('Small','emd-plugins'); ?></option>
			<option value="medium" <?php if($instance['pagination_size'] == 'medium') { echo 'selected'; } ?>><?php echo __('Medium','emd-plugins'); ?></option>
			<option value="large" <?php if($instance['pagination_size'] == 'large') { echo 'selected'; } ?>><?php echo __('Large','emd-plugins'); ?></option>
			</select>
			</p>
			<p class="emd-paginate-show" <?php echo $paginate_style; ?>>
			<label for="<?php echo $this->get_field_id('count_per_page'); ?>"><?php printf(__('Number of %s to show per pagination', 'emd-plugins') , $this->class_label); ?></label>
			<input type="text" id="<?php echo $this->get_field_id('count_per_page'); ?>" name="<?php echo $this->get_field_name('count_per_page'); ?>" value="<?php echo $instance['count_per_page']; ?>" size="2" maxlength="2" /> <br>
			</p>
			<?php
		}
	}
	/**
	 * Runs wp query and creates layout for entity widgets
	 * @since WPAS 4.0
	 *
	 * @param string $posts_per_page
	 * @param array $pids
	 * @param array $args
	 *
	 * @return string $layout
	 */
	public static function get_ent_widget_layout($posts_per_page, $pids, $args = Array()) {
		$paged = 1;
		$layout = "";
		if(!empty($args['filter'])){
			$emd_query = new Emd_Query($args['class'],$args['app'],$args['query_args']['context']);
        		$emd_query->args_filter($args['filter']);
        		$args['query_args'] = array_merge($args['query_args'],$emd_query->args);
		}
	
		if($args['query_args']['paged']){
			$paged = $args['query_args']['paged'];
		}	
		if ($args['has_pages']) {
			$args['query_args']['posts_per_page'] = $args['posts_per_page'];
			$total_pages = round($posts_per_page / $args['posts_per_page']);
			if (get_query_var('paged')) $paged = get_query_var('paged');
		}
		else {
			$total_pages = 1;
			$args['query_args']['posts_per_page'] = $posts_per_page;
		}
		$args['query_args']['paged'] = $paged;
		$args['query_args']['post__in'] = $pids;
		if($paged <= $total_pages){
			$mywidget = new WP_Query($args['query_args']);
			if($mywidget->max_num_pages < $total_pages){
				$total_pages = $mywidget->max_num_pages;
			}
			$record_count = ($paged - 1) * $args['posts_per_page'] + 1;
			while ($mywidget->have_posts()) {
				$mywidget->the_post();
				if(!$args['has_pages'] || ($args['has_pages'] && $record_count <= $posts_per_page)){
					if (isset($args['fname'])) {
						$layout.= $args['fname']();
					} else {
						$layout .= call_user_func(array($args['cname'],"layout"));
					}
					$record_count ++;
				}
			}
			wp_reset_postdata();
		}
		if(!empty($args['filter'])){
			$emd_query->remove_filters();
		}
		if ($args['has_pages']) {
			$paging_text = paginate_links(array(
				'total' => $total_pages,
				'current' => $paged,
				//'base' => get_pagenum_link() . '&%_%',
				'base' => '&%_%',
				'format' => 'paged=%#%',
				'type' => 'array',
				'add_args' => true,
			));
			if(!empty($paging_text)){
				$paging_html = "<ul class='emd-pagination"; 
				switch($args['pagination_size']){
					case 'medium':
						break;
					case 'large':
						$paging_html .= " emd-pagination-lg";
						break;
					case 'small':
					default:
						$paging_html .= " emd-pagination-sm";
						break;
				}
				$paging_html .= "'>";
                        	foreach ($paging_text as $key_paging => $my_paging) {
                                	$paging_html .= "<li";
					if(strpos($my_paging,'page-numbers current') !== false){
						$paging_html .= " class='active'";
					}
					$paging_html .= ">" . $my_paging . "</li>";
				}
                        	$paging_html .= "</ul>";
				$layout .= '<div class="widg-pagination">' . $paging_html . '</div>';
			}
		}
		return $layout;
	}
	/**
	 * Runs wp query and creates layout for comment widgets
	 * @since WPAS 4.0
	 *
	 * @param string $posts_per_page
	 * @param array $pids
	 *
	 * @return string $output
	 */
	public function get_comm_widget_layout($posts_per_page, $pids) {
		$ccount = 0;
		$output = "";
		$this->query_args['number'] = $posts_per_page;
		if (empty($pids)) {
			$comments = get_comments(apply_filters('widget_comments_args', $this->query_args));
			if ($comments) {
				foreach ((array)$comments as $comment) {
					$output.= '<li class="recentcomments">' . sprintf(__('%1$s on %2$s', 'emd-plugins') , get_comment_author_link($comment->comment_ID) , '<a href="' . esc_url(get_comment_link($comment->comment_ID)) . '">' . get_the_title($comment->comment_post_ID) . '</a>') . '</li>';
				}
			}
			return $output;
		}
		foreach ($pids as $cpid) {
			if ($ccount < $posts_per_page && $cpid != 0) {
				$this->query_args['post_id'] = $cpid;
				$comments = get_comments(apply_filters('widget_comments_args', $this->query_args));
				if ($comments) {
					foreach ((array)$comments as $comment) {
						if ($ccount < $posts_per_page) {
							$ccount++;
							$output.= '<li class="recentcomments">' . sprintf(__('%1$s on %2$s', 'emd-plugins') , get_comment_author_link($comment->comment_ID) , '<a href="' . esc_url(get_comment_link($comment->comment_ID)) . '">' . get_the_title($comment->comment_post_ID) . '</a>') . '</li>';
						}
					}
				}
			}
		}
		return $output;
	}
}
