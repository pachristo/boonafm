<?php

/**
 * uses module 11
 * Class td_block_12
 */
class td_block_12 extends td_block {

    static function cssMedia( $res_ctx ) {

        // fonts
        $res_ctx->load_font_settings( 'f_header' );
        $res_ctx->load_font_settings( 'f_ajax' );
        $res_ctx->load_font_settings( 'f_more' );

        // module 11 fonts
        $res_ctx->load_font_settings( 'm11f_title' );
        $res_ctx->load_font_settings( 'm11f_cat' );
        $res_ctx->load_font_settings( 'm11f_meta' );
        $res_ctx->load_font_settings( 'm11f_ex' );
        $res_ctx->load_font_settings( 'm11f_btn' );

    }

    public function get_custom_css() {
        // $unique_block_class - the unique class that is on the block. use this to target the specific instance via css
        $unique_block_class = $this->block_uid;

        $compiled_css = '';

        $raw_css =
            "<style>

				/* @f_header */
				.$unique_block_class .block-title a,
				.$unique_block_class .block-title span {
					@f_header
				}
				/* @f_ajax */
				.$unique_block_class .td-pulldown-filter-link {
					@f_ajax
				}
				/* @f_more */
				.$unique_block_class .td-load-more-wrap a {
					@f_more
				}
				/* @m11f_title */
				.$unique_block_class .td_module_11 .entry-title {
					@m11f_title
				}
				/* @m11f_cat */
				.$unique_block_class .td_module_11 .td-post-category {
					@m11f_cat
				}
				/* @m11f_meta */
				.$unique_block_class .td_module_11 .meta-info,
				.$unique_block_class .td_module_11 .td-module-comments a {
					@m11f_meta
				}
				/* @m11f_ex */
				.$unique_block_class .td_module_11 .td-excerpt {
					@m11f_ex
				}
				/* @m11f_btn */
				.$unique_block_class .td_module_11 .td-read-more a {
					@m11f_btn
				}
			</style>";


        $td_css_res_compiler = new td_css_res_compiler( $raw_css );
        $td_css_res_compiler->load_settings( __CLASS__ . '::cssMedia', $this->get_all_atts() );

        $compiled_css .= $td_css_res_compiler->compile_css();

        return $compiled_css;
    }


    function render($atts, $content = null){
        parent::render($atts); // sets the live atts, $this->atts, $this->block_uid, $this->td_query (it runs the query)

        if (empty($td_column_number)) {
            $td_column_number = td_global::vc_get_column_number(); // get the column width of the block from the page builder API
        }

        $buffy = ''; //output buffer


        $buffy .= '<div class="' . $this->get_block_classes() . '" ' . $this->get_block_html_atts() . '>';



		    //get the block js
		    $buffy .= $this->get_block_css();

		    //get the js for this block
		    $buffy .= $this->get_block_js();


            //get the block title
            $buffy .= $this->get_block_title();

            //get the sub category filter for this block
            $buffy .= $this->get_pull_down_filter();

            $buffy .= '<div id=' . $this->block_uid . ' class="td_block_inner td-column-' . $td_column_number . '">';
                $buffy .= $this->inner($this->td_query->posts); //inner content of the block
            $buffy .= '</div>';

            //get the ajax pagination for this block
            $buffy .= $this->get_block_pagination();
        $buffy .= '</div> <!-- ./block -->';
        return $buffy;
    }

    function inner($posts, $td_column_number = '') {

        $buffy = '';

        $td_block_layout = new td_block_layout();
        if (empty($td_column_number)) {
            $td_column_number = td_global::vc_get_column_number(); // get the column width of the block from the page builder API
        }

        if (!empty($posts)) {
            foreach ($posts as $post) {
                $td_module_11 = new td_module_11($post, $this->get_all_atts());

                $buffy .= $td_block_layout->open12(); //added in 010 theme - span 12 doesn't use rows
                    $buffy .= $td_module_11->render($post);
                $buffy .= $td_block_layout->close12();
            }
        }
        $buffy .= $td_block_layout->close_all_tags();
        return $buffy;
    }
}
