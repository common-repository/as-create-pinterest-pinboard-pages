<?php
/*
Plugin Name: AS - Create Pinterest Pinboard Pages
Plugin URI: http://akashsoni.com
Description: Easy to create pintrest pinboard pages on your site.
Version: 1.0
Author: Akash soni
Author URI: http://akashsoni.com
Text Domain: pinterest page
License: later
*/

if ( ! defined( 'WPINC' ) ) {
	die;
}

// Include function.php file
include 'function.php'; 

// Include as_create_page.php file
include 'admin/as_create_page.php'; 


// This function create pinterest page 
function as_get_pinterest_page( $pinterest_option ) {
	$pinterest_option_data = shortcode_atts( array(
		'id' => '',
		'page_name' => '',
	), $pinterest_option, 'as_pinterest_page' );

			$class_number=rand();	
			$pinterest_username=get_post_meta($pinterest_option_data['id'],'pinterest_username',true);
			$pinterest_boardname=get_post_meta($pinterest_option_data['id'],'pinterest_boardname',true);
			$per_page_pin_limite=get_post_meta($pinterest_option_data['id'],'per_page_pin_limite',true);
			$show_more_button_text=get_post_meta($pinterest_option_data['id'],'show_more_button_text',true);
			$show_more_button_text_color=get_post_meta($pinterest_option_data['id'],'show_more_button_text_color',true);
			$show_more_button_backgound_color=get_post_meta($pinterest_option_data['id'],'show_more_button_backgound_color',true);
			$as_pin_box_color_field=get_post_meta($pinterest_option_data['id'],'as_pin_box_color_field',true);
			$as_color_text_field=get_post_meta($pinterest_option_data['id'],'as_color_text_field',true);
			$column_number=get_post_meta($pinterest_option_data['id'],'column_number',true);
			$as_pin_box_style_field=get_post_meta($pinterest_option_data['id'],'as_pin_box_style_field',true);

			// Create object of as_pinterest_fucntion
			$as_pinterest_fucntion = new as_pinterest_fucntion;				
			
			if(empty($show_more_button_text)):
						$show_more_button_text="Show more";
			endif;	

			// Call as_pinterest_fucntion::fetched_pin_fromm_pinterest for fetched pins
			$as_pins = $as_pinterest_fucntion->fetched_pin_fromm_pinterest($pinterest_username,$pinterest_boardname,1,$per_page_pin_limite,$column_number,$show_more_button_text,$class_number);

				// Style in apply in pin page
				echo '
					
				<style>

					.main_pins_load_button .load_button_'.$class_number.'{
						color:'.$show_more_button_text_color.';
						background:'.$show_more_button_backgound_color.';
					}
					.main_pins_load_button .load_button_'.$class_number.':before{
						border:1px solid '.$show_more_button_text_color.';						
						border-top-color: transparent;
						border-bottom-color: transparent;
					}
					.grid_'.$class_number.' .as_pin_box_details{
						background:'.$as_pin_box_color_field.';		
						border-color:'.$as_pin_box_color_field.';				
					}					
					.grid_'.$class_number.' .as_pin_description,.grid_'.$class_number.' .as_pinner_name{
						color:'.$as_color_text_field.';
					}
					.grid_'.$class_number.'.as_style_seven_item .as_pin_box_details::after,.grid_'.$class_number.'.as_style_seven_item .as_pin_box_details::before,.grid_'.$class_number.'.as_style_eight_item .as_pin_box_details::after,.grid_'.$class_number.'.as_style_eight_item .as_pin_box_details::before  {
						border-top-color:'.$as_pin_box_color_field.';	
					}
					.grid_'.$class_number.'.as_style_eight_item .as_pin_detail::after,.grid_'.$class_number.'.as_style_eight_item .as_pin_detail::before{
						border-bottom-color:'.$as_pin_box_color_field.';	
					}

				</style>';

				// Script for apply isotope in pin page					 
				echo '					
					<script>
					
						jQuery(document).ready(function($){
							var $container= $(".grid_'.$class_number.' .as-element-item .as_pin_box_details");					
							$container.imagesLoaded(function(){
								var $grid = $(".grid_'.$class_number.'").isotope({
								  itemSelector: ".as-element-item",
								  layoutMode: "masonry",
								  percentPosition: true
								});		
							});
						});

					</script>
					<script>

						var page_item_'.$class_number.'='.$per_page_pin_limite.'
						var page_number_'.$class_number.'=1;
						var username_'.$class_number.'="'.$pinterest_username.'";
						var boardname_'.$class_number.'="'.$pinterest_boardname.'";					
						var column_number_'.$class_number.'="'.$column_number.'";
						var class_number_'.$class_number.'="'.$class_number.'";
						
						jQuery(document).ready(function($){
							$(document).on("click",".load_button_'.$class_number.'",function(){
							   
								page_number_'.$class_number.'++;
								var data = {
									"action": "as_get_more_pins",
									"page_item": page_item_'.$class_number.',
									"page_number": page_number_'.$class_number.',
									"boardname": boardname_'.$class_number.',
									"username": username_'.$class_number.',
									"column_number": column_number_'.$class_number.',
									"class_number": class_number_'.$class_number.',
									
								};

								jQuery(this).addClass(\'m-progress\'); 
								jQuery.post("'.admin_url( 'admin-ajax.php' ).'", data, function(response) {
								
									var $pin_box= $(".grid_'.$class_number.'");	
										
									var $newItems = $(response);							
									
									var $container= $(\'.grid_'.$class_number.' .as-element-item .as_pin_box_details\');	
										
									$(".grid_'.$class_number.'").isotope( \'insert\',$newItems );		
								
									$container.imagesLoaded(function(){
											$(".grid_'.$class_number.'").isotope(); 
									});
									
									var current_total_item=page_number_'.$class_number.'*page_item_'.$class_number.';							
									
									console.log(current_total_item+"/"+total_get_pins_'.$class_number.');							
									
									if(current_total_item >= total_get_pins_'.$class_number.'){
										$(".main_pins_load_button_'.$class_number.'").css("display","none");
									}
									
									jQuery(".load_button_'.$class_number.'").removeClass(\'m-progress\'); 
									setTimeout(function(){ $(".grid_'.$class_number.'").isotope();  }, 1000);
								});
							});
						});

					</script>';

					 $style_class="";
					 if($as_pin_box_style_field==1){
						  $style_class="as_style_one_item";						  
					 }elseif($as_pin_box_style_field==3){
						 $style_class="as_style_three_item";
					 }elseif($as_pin_box_style_field==4){
						 $style_class="as_style_four_item";						 
					 }elseif($as_pin_box_style_field==5){
						 $style_class="as_style_five_item";						 
					 }elseif($as_pin_box_style_field==6){
						 $style_class="as_style_six_item";						 
					 }elseif($as_pin_box_style_field==7){
						 $style_class="as_style_seven_item ";						 
					 }elseif($as_pin_box_style_field==8){
						 $style_class="as_style_eight_item ";						 
					 }elseif($as_pin_box_style_field==9){
						 $style_class="as_style_seven_item as_box_border";						 
					 }elseif($as_pin_box_style_field==10){
						 $style_class="as_style_ten_item";						 
					 }

					 echo '<div class="main_pins_box grid_'.$class_number.' '.$style_class.' ">';
						echo $as_pins;
					 echo '</div>';	
					 echo '<div class="main_pins_load_button main_pins_load_button_'.$class_number.'">';
					 	echo '<a class="load_button load_button_'.$class_number.'">'.$show_more_button_text.'</a>';
					 echo '</div>';				  
}

// shortcode of create pinterest page 
add_shortcode( 'as_pinterest_page', 'as_get_pinterest_page' );
?>
