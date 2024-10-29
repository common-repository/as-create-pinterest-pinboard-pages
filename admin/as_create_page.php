<?php
// Add stylsheet and script file in admin side
add_action( 'admin_init', array( 'as_admin_ajax' , 'as_custom_script_and_style' ));

// Create As - pinterest page setting page in admin side
add_action('admin_menu', 'as_pinterest_page_settings_menu');

// This function created As - pinterest page setting 
function as_pinterest_page_settings_menu() {
	add_menu_page('AS Pinterest Page Settings', 'AS Pinterest Page Settings', 'administrator', __FILE__, 'as_pinterest_page_settings' , plugins_url('../images/icon.png', __FILE__) );
}

// This function is show As - pinterest page setting data on page
function as_pinterest_page_settings() {
?>
// var of ajaxurl for data save and edit Jquery script
<script>
var as_ajaxurl= '<?php echo admin_url('admin-ajax.php'); ?>';	
</script>

<div class="wrap">	
<h2>Pinterest Pages Settings <a  class="button button-primary button-large create_new_page">Create New Pinterest Page</a></h2>
<table class="widefat as_table fixed striped posts">
	<thead>
		<tr>
			<th scope="col" id="title" class="manage-column">
				<span>Short Code</span>
			</th>
			<th scope="col" id="title" class="manage-column">
				<span>Action</span>
			</th>
		</tr>
	</thead>
		<tbody id="the-list">
			<?php
			$args=array('post_type'=>'pinterest_page','orderby' => 'ID','order'=> 'ASC','posts_per_page'=>-1);
			$pinterest_pages = new WP_Query( $args );
			if($pinterest_pages->have_posts()):
				while($pinterest_pages->have_posts()):
					$pinterest_pages->the_post();
					$pins_page_title=get_the_title();
					$pinterest_username=get_post_meta(get_the_ID(),'pinterest_username',true);
					$pinterest_boardname=get_post_meta(get_the_ID(),'pinterest_boardname',true);
					$per_page_pin_limite=get_post_meta(get_the_ID(),'per_page_pin_limite',true);
					$show_more_button_text=get_post_meta(get_the_ID(),'show_more_button_text',true);
					$show_more_button_text_color=get_post_meta(get_the_ID(),'show_more_button_text_color',true);
					$show_more_button_backgound_color=get_post_meta(get_the_ID(),'show_more_button_backgound_color',true);
					$as_pin_box_color_field=get_post_meta(get_the_ID(),'as_pin_box_color_field',true);
					$column_number=get_post_meta(get_the_ID(),'column_number',true);
					$as_color_text_field=get_post_meta(get_the_ID(),'as_color_text_field',true);
					$as_pin_box_style_field=get_post_meta(get_the_ID(),'as_pin_box_style_field',true);
					if(empty($show_more_button_text)):
						$show_more_button_text="Show more";
					endif;	
					if(empty($show_more_button_text_color)):
						$show_more_button_text_color="#ffffff";
					endif;	
					if(empty($show_more_button_backgound_color)):
						$show_more_button_backgound_color="#000000";
					endif;
			?>
			<tr id="post-<?php echo get_the_ID(); ?>" class="ilist">		
				<td class="has-row-actions column-primary short-code-<?php echo get_the_ID(); ?>" >
					<strong>[as_pinterest_page id="<?php echo get_the_ID(); ?>" page_name="<?php echo $pins_page_title; ?>"]</strong>		
				</td>
				<td class="has-row-actions column-primary" >
					<a  class="button button-primary button-large edit_page" data-id="<?php echo get_the_ID(); ?>">Edit</a>
					<a  class="button button-primary button-large delete_page" data-id="<?php echo get_the_ID(); ?>">Delete</a>
				</td>
			</tr>
			<tr id="edit-<?php echo get_the_ID(); ?>" class="iedit inline-edit-row inline-edit-row-post inline-edit-post quick-edit-row quick-edit-row-post inline-edit-post inline-editor">
				<td class="has-row-actions column-primary edit-setting" colspan="2">
					<fieldset class="edit_pinterest_page">
						<legend class="inline-edit-legend">Edit Pinterest Detail</legend>
						<table>
							<tr>
								<td>
									<span class="label">Page Title</span>
								</td>
								<td class="padding_right">
									<span class="input-text-wrap"><input type="text" name="page_title" class="ptitle-<?php echo get_the_ID(); ?>" value="<?php echo $pins_page_title; ?>"></span>
									<div class="help-tip">
										<p class="help_msg">Enter unique title of pinterest setting.</p>
									</div>
								</td>
							
								<td>
									<span class="label pinterest_username">Pinterest Username</span>
								</td>
								<td class="padding_right">
									<span class="input-text-wrap"><input type="text" name="pinterest_username" class="pinterest_username-<?php echo get_the_ID(); ?>" value="<?php echo $pinterest_username; ?>"></span>
									<div class="help-tip">
										<p class="help_msg">Enter your pinterest username here.</p>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<span class="label pinterest_boardname">Pinterest boardname</span>
								</td>
								<td class="padding_right">
									<span class="input-text-wrap"><input type="text" name="pinterest_boardname" class="pinterest_boardname-<?php echo get_the_ID(); ?>" value="<?php echo $pinterest_boardname; ?>"></span>
									<div class="help-tip">
										<p class="help_msg">Enter your pinterest boardname here.</p>
									</div>
								</td>
							
								<td>
									<span class="label per_page_pin_limite">Per Page Pins Limite</span>
								</td>
								<td class="padding_right">
									<span class="input-text-wrap"><input type="text" name="per_page_pin_limite" class="per_page_pin_limite-<?php echo get_the_ID(); ?>" value="<?php echo $per_page_pin_limite; ?>"></span>
									<div class="help-tip">
										<p class="help_msg">Enter limit of pins you want to show on page.</p>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<span class="label pinterest_boardname">Show more button text</span>
								</td>
								<td class="padding_right">
									<span class="input-text-wrap"><input type="text" name="show-more-button-text" class="show-more-button-text-<?php echo get_the_ID(); ?>" value="<?php echo $show_more_button_text; ?>"></span>
									<div class="help-tip">
										<p class="help_msg">Enter show more button text here.</p>
									</div>
								</td>
								<td>
									<span class="label pinterest_boardname">Select column number</span>
								</td>
								<td class="padding_right">
									<span class="input-text-wrap">
										<select class="as-column-field-<?php echo get_the_ID(); ?>" >
											<option value="1" <?php if($column_number==1): echo "selected"; endif; ?> >1</option>
											<option value="2" <?php if($column_number==2): echo "selected"; endif; ?> >2</option>
											<option value="3" <?php if($column_number==3): echo "selected"; endif; ?> >3</option>
											<option value="4" <?php if($column_number==4): echo "selected"; endif; ?> >4</option>
										</select>
									</span>
									<div class="help-tip">
										<p class="help_msg">Set column number you want to show in row.</p>
									</div>
								</td>								
							</tr>
							<tr>
								<td>
									<span class="label pinterest_boardname">Show more button backgound color</span>
								</td>
								<td class="padding_right">
									<span class="input-text-wrap"><input type="text" value="<?php echo $show_more_button_backgound_color; ?>" class="as-color-field as-backgound-field-<?php echo get_the_ID(); ?>" /></span>
									<div class="help-tip">
										<p class="help_msg">Set show more button backgound color.</p>
									</div>
								</td>							
								
								<td>
									<span class="label pinterest_boardname">Show more button text color</span>
								</td>
								<td class="padding_right">
									<span class="input-text-wrap"><input type="text" value="<?php echo $show_more_button_text_color; ?>" class="as-color-field as-color-field-<?php echo get_the_ID(); ?>" /></span>
									<div class="help-tip">
										<p class="help_msg">Set show more button text color.</p>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<span class="label pinterest_boardname">Pin box background color</span>
								</td>
								<td class="padding_right">
									<span class="input-text-wrap"><input type="text" value="<?php echo $as_pin_box_color_field; ?>" class="as-color-field as-pin-box-color-field-<?php echo get_the_ID(); ?>" /></span>
									<div class="help-tip">
										<p class="help_msg">This color apply in pin box background.</p>
									</div>
								</td>
								<td>
									<span class="label pinterest_boardname">Pin box text color</span>
								</td>
								<td class="padding_right">
									<span class="input-text-wrap"><input type="text" value="<?php echo $as_color_text_field; ?>" class="as-color-field as-color-text-field-<?php echo get_the_ID(); ?>" /></span>
									<div class="help-tip">
										<p class="help_msg">This color apply in pin box text.</p>
									</div>
								</td>								
							</tr>
							<tr>
								<td>
									<span class="label pinterest_boardname">Select pin box style</span>
								</td>
								<td class="padding_right">
									<span class="input-text-wrap">
										<select class="as-pin-box-style-field-<?php echo get_the_ID(); ?>" >
											<option value="1" <?php if($as_pin_box_style_field==1): echo "selected"; endif; ?> >Style 1</option>
											<option value="2" <?php if($as_pin_box_style_field==2): echo "selected"; endif; ?> >Style 2</option>
											<option value="3" <?php if($as_pin_box_style_field==3): echo "selected"; endif; ?> >Style 3</option>
											<option value="4" <?php if($as_pin_box_style_field==4): echo "selected"; endif; ?> >Style 4</option>
											<option value="5" <?php if($as_pin_box_style_field==5): echo "selected"; endif; ?> >Style 5</option>
											<option value="6" <?php if($as_pin_box_style_field==6): echo "selected"; endif; ?> >Style 6</option>
											<option value="7" <?php if($as_pin_box_style_field==7): echo "selected"; endif; ?> >Style 7</option>
											<option value="8" <?php if($as_pin_box_style_field==8): echo "selected"; endif; ?> >Style 8</option>
											<option value="9" <?php if($as_pin_box_style_field==9): echo "selected"; endif; ?> >Style 9</option>
											<option value="10" <?php if($as_pin_box_style_field==10): echo "selected"; endif; ?> >Style 10</option>
										</select>
									</span>
									<div class="help-tip">
										<p class="help_msg">Set pin box style here.</p>
									</div>
								</td>								
							</tr>
						</table>					
					</fieldset>
					<p class="submit inline-edit-save">
						<button type="button" class="button-secondary pin_cancel alignleft" data-id="<?php echo get_the_ID(); ?>">Cancel</button>
						<button type="button" class="button-primary pin_save alignleft" data-id="<?php echo get_the_ID(); ?>">Save</button>		
					</p>
				</td>
			</tr>
		<?php
			endwhile;
		endif;	
		wp_reset_postdata();	
		?>
		</tbody>
</table>
</div>
<?php 
}

// Create new pin page shortcode
add_action( 'wp_ajax_as_create_pins_page', array( 'as_admin_ajax' , 'as_create_pins_page' ));

// Edit new pin page shortcode
add_action( 'wp_ajax_as_edit_pins_page',  array( 'as_admin_ajax' , 'as_edit_pins_page' ));

// Delete pin page shortcode
add_action( 'wp_ajax_as_delete_pins_page',  array( 'as_admin_ajax' , 'as_delete_pins_page' ));


class as_admin_ajax
{	
	// This function add script and style in admin side
	public static function as_custom_script_and_style(){
		 wp_enqueue_style( 'wp-color-picker' );	
		 wp_enqueue_style( 'ac-style', plugin_dir_url( __FILE__ ) . 'asset/css/as_admin_mini.css', array(), '1.0.0', false );
		 wp_enqueue_script( 'my-script-handle',  plugin_dir_url( __FILE__ ) . 'asset/js/as_admin.js', array( 'wp-color-picker' ), false, true );
	}

	// Create new pin page shortcode function
	public static function as_create_pins_page(){	
		$my_post = array(
			'post_title'    => 'Pinterest Page',
			'post_status'   => 'publish',
			'post_author'   => 1,
			'post_type'		=> 'pinterest_page'
		);
		$post_id = wp_insert_post( $my_post );
		echo $post_id;
		die();
	}
	
	// Edit new pin page shortcode function
	public static function as_edit_pins_page(){
		$pinterest_username=$_POST['pinterest_username'];
		$pinterest_boardname=$_POST['pinterest_boardname'];
		$per_page_pin_limite=$_POST['per_page_pin_limite'];
		$show_more_button_text=$_POST['show_more_button_text'];
		$show_more_button_text_color=$_POST['show_more_button_text_color'];
		$show_more_button_backgound_color=$_POST['show_more_button_backgound_color'];
		$as_pin_box_color_field=$_POST['as_pin_box_color_field'];
		$column_number=$_POST['column_number'];
		$as_color_text_field=$_POST['as_color_text_field'];
		$as_pin_box_style_field=$_POST['as_pin_box_style_field'];
		$post_title=$_POST['post_title'];
		$post_id=$_POST['post_id'];
		$pin_post = array(
			  'ID'           => $post_id,
			  'post_title'   => $post_title
		);
		wp_update_post( $pin_post );
		update_post_meta($post_id,'pinterest_username',$pinterest_username);
		update_post_meta($post_id,'pinterest_boardname',$pinterest_boardname);
		update_post_meta($post_id,'per_page_pin_limite',$per_page_pin_limite);
		update_post_meta($post_id,'show_more_button_text',$show_more_button_text);
		update_post_meta($post_id,'show_more_button_text_color',$show_more_button_text_color);
		update_post_meta($post_id,'show_more_button_backgound_color',$show_more_button_backgound_color);
		update_post_meta($post_id,'as_pin_box_color_field',$as_pin_box_color_field);
		update_post_meta($post_id,'column_number',$column_number);
		update_post_meta($post_id,'as_color_text_field',$as_color_text_field);
		update_post_meta($post_id,'as_pin_box_style_field',$as_pin_box_style_field);
		echo $post_id;
		die();
	}
	
	// Delete new pin page shortcode function
	public static function as_delete_pins_page(){
		$post_id=$_POST['post_id'];	
		wp_delete_post($post_id); 
		echo $post_id;
		die();
	}
}


?>
