<?php
/*
Plugin Name: Fast Words Count
Description: 
Author: Jaap Marcus
Author URI: https://eris.nu
Version: 0.0.1
Text Domain: fast-post-words-count
License: GPLv2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/

function dfipl_show_thumb_add_pic_column($columns) {
    $columns['img'] = 'Featured';
    return $columns;
}

function dfipl_show_thumb_manage_pic_column($column_name, $post_id) {
    if($column_name != 'img'){ return $column_nane;}
    if( has_post_thumbnail($post_id) ){
        echo '<div aria-hidden="true" title="Present" class="has-feature-image good"></div>'; 
    }else{
        echo '<div aria-hidden="true" title="Missing" class="has-feature-image na"></div>';
    }
    return $column_name;
}
function dfipl_show_thumb_manage_wordcount_column($column_name, $post_id) {
    if($column_name != 'wordcount'){ return $column_nane;}
    echo get_post_meta( $post_id, 'pwc_wordn', true );
    return $column_name;
}

function dfipl_show_thumb_add_wordcount_column($columns) {
    $columns['wordcount'] = 'Wordcount';
    return $columns;
}

function my_admin_column_width() {
    echo '<style type="text/css">
        .column-img { text-align: left; width:100px !important; overflow:hidden }
	.has-feature-image{ display: inline-block!important;
width: 12px!important;
height: 12px!important;
border-radius: 50%!important;
margin: 3px 10px 0 3px;
background: #888;
vertical-align: top;
}
.has-feature-image.good{background-color: #7ad03a;}
  </style>';
}

function pwc_update_wordcount_on_post_save($post_id){
        $p       = get_post($post_id);
        $content = $p->post_content;
        $wordn   = str_word_count( strip_tags( $content ) );
        update_post_meta( $p->ID, 'pwc_wordn', $wordn );
    }

add_action('save_post', 'pwc_update_wordcount_on_post_save');

if(is_admin()){
    add_filter('manage_posts_custom_column', 'dfipl_show_thumb_manage_pic_column', 10, 2);    
    add_filter('manage_posts_columns', 'dfipl_show_thumb_add_wordcount_column');
    add_filter('manage_posts_columns', 'dfipl_show_thumb_add_pic_column');
    add_filter('manage_posts_custom_column', 'dfipl_show_thumb_manage_wordcount_column', 10, 2);
    add_action('admin_head', 'my_admin_column_width');
}
