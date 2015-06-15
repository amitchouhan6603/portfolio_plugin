<?php
/**
* Plugin Name: ideabox+ CRM
* Plugin URI: #
* Version: 1.0
* Author: Amit
* Author URI: http://code.tutsplus.com
* Description: A simple CRM system for WordPress
* License: GPL2
*/

class ideabox_CRM {
	function register_custom_post_type()
	{
		register_post_type( 'portfolio', 
							array(
    	    						'labels' => array(
         							'name'               => _x( 'portfolio', 'post type general name', 'ideabox-crm' ),
          							'singular_name'      => _x( 'portfolio', 'post type singular name', 'ideabox-crm' ),
           							'menu_name'          => _x( 'portfolio', 'admin menu', 'ideabox-crm' ),
            						'name_admin_bar'     => _x( 'portfolio', 'add new on admin bar', 'ideabox-crm' ),
             						'add_new'            => _x( 'Add New', 'portfolio', 'ideabox-crm' ),
              						'add_new_item'       => __( 'Add New portfolio', 'ideabox-crm' ),
               						'new_item'           => __( 'New portfolio', 'ideabox-crm' ),
              						'edit_item'          => __( 'Edit portfolio', 'ideabox-crm' ),
             						'view_item'          => __( 'View portfolio', 'ideabox-crm' ),
            						'all_items'          => __( 'All portfolio', 'ideabox-crm' ),
           							'search_items'       => __( 'Search portfolio', 'ideabox-crm' ),
          							'parent_item_colon'  => __( 'Parent portfolio:', 'ideabox-crm' ),
         							'not_found'          => __( 'No portfolio found.', 'ideabox-crm' ),
        							'not_found_in_trash' => __( 'No portfolio found in Trash.', 'ideabox-crm' ),
       							),
      							// Frontend
     							'has_archive'        => false,
      							'public'             => false,
       							'publicly_queryable' => false,       
        						// Admin
         						'capability_type' => 'post',
          						'menu_icon'     => 'dashicons-businessman',
           						'menu_position' => 10,
          						'query_var'     => true,
         						'show_in_menu'  => true,
        						'show_ui'       => true,
       							'supports'      => array(
      							'title',
     							'author',
								'comments',
								'thumbnail' 
   							),
					)
 			);    
	}
	function register_meta_boxes() 
	{
		add_meta_box( 'Contact-details', 'Contact Details', array( $this, 'output_meta_box' ), 'portfolio', 'normal', 'high' );  
    }
    function output_meta_box( $post ) 
	{
		$name = get_post_meta( $post->ID, '_portfolio_name', true );
		$company = get_post_meta( $post->ID, '_company', true );
		$designation = get_post_meta( $post->ID, '_designation', true );
        wp_nonce_field( 'save_portfolio', 'portfolio_nonce' );
		echo ( '<label for="portfolio_name">' . __( 'Name', 'ideabox-crm' ) . '</label>' );
    	echo ( '&nbsp;<input type="text" name="portfolio_name" id="portfolio_name" value="' . esc_attr( $name ) . '" /> &nbsp;&nbsp;&nbsp;' );
        echo ( '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label for="company">' . __( 'Company', 'ideabox-crm' ) . '</label>' );
    	echo ( '&nbsp;<input type="text" name="company" id="company" value="' . esc_attr( $company ) . '" /> &nbsp;&nbsp;&nbsp;' );
		echo ( '<label for="designation">' . __( 'Designation', 'ideabox-crm' ) . '</label>' );
    	echo ( '&nbsp;<input type="text" name="designation" id="designation" value="' . esc_attr( $designation ) . '" /><br><br>' );
	    
	}
	function save_meta_boxes( $post_id ) 
	{
    	if ( ! isset( $_POST['portfolio_nonce'] ) ) {
        return $post_id;    
    }
    if ( ! wp_verify_nonce( $_POST['portfolio_nonce'], 'save_portfolio' ) ) 
	{
    	return $post_id;
    }
    if ( 'portfolio' != $_POST['post_type'] ) 
	{
		return $post_id;
	}
 	if ( ! current_user_can( 'edit_post', $post_id ) ) 
	{
   		return $post_id;
    }
    $company = sanitize_text_field( $_POST['company'] );
    update_post_meta( $post_id, '_company', $company );
	
	$designation = sanitize_text_field( $_POST['designation'] );
    update_post_meta( $post_id, '_designation', $designation);
	
    $name = sanitize_text_field( $_POST['portfolio_name'] );
    update_post_meta( $post_id, '_portfolio_name', $name ); 
	}
    function __construct() 
	{
    	add_action( 'init', array( $this, 'register_custom_post_type' ) );
    	add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) ); 
    	add_action( 'save_post', array( $this, 'save_meta_boxes' ) );
    } 
}
	$ideabox_CRM = new ideabox_CRM;

?>