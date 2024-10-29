<?php
// Add scripts in site
add_action( 'wp_enqueue_scripts', array( 'as_pinterest_fucntion' , 'as_add_script' ));

// Action for show more button pin get 
add_action( 'wp_ajax_as_get_more_pins',array( 'as_pinterest_fucntion' , 'as_get_more_pins' ));
add_action( 'wp_ajax_nopriv_as_get_more_pins',array( 'as_pinterest_fucntion' , 'as_get_more_pins' ));

// Class as_pinterest_fucntion
class as_pinterest_fucntion
{
	// This function use for load js on page 
	public static function as_add_script() {
		wp_enqueue_style( 'ac-style', plugin_dir_url( __FILE__ ) . 'asset/css/as_style_mini.css', array(), '1.0.0', false );
		wp_enqueue_script( 'ac-isotope', plugin_dir_url( __FILE__ ) . 'asset/js/isotope.min.js', array( 'jquery' ), '1.0.0', true );
		wp_enqueue_script( 'ac-imagesloaded', plugin_dir_url( __FILE__ ) . 'asset/js/imagesloaded.pkgd.min.js', array( 'jquery' ), '1.0.0', true );
	}
	
	// Find link in content and create a tag link
	public static function as_create_link_in_content($content) {
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
		$urls = array();
		$urlsToReplace = array();
		if(preg_match_all($reg_exUrl, $content, $urls)) {
			$numOfMatches = count($urls[0]);
			$numOfUrlsToReplace = 0;
			for($i=0; $i<$numOfMatches; $i++) {
				$alreadyAdded = false;
				$numOfUrlsToReplace = count($urlsToReplace);
				for($j=0; $j<$numOfUrlsToReplace; $j++) {
					if($urlsToReplace[$j] == $urls[0][$i]) {
						$alreadyAdded = true;
					}
				}
				if(!$alreadyAdded) {
					array_push($urlsToReplace, $urls[0][$i]);
				}
			}
			$numOfUrlsToReplace = count($urlsToReplace);
			for($i=0; $i<$numOfUrlsToReplace; $i++) {
				$sourceUrl = parse_url($urlsToReplace[$i]);
				$content = str_replace($urlsToReplace[$i], "<a href=\"".$urlsToReplace[$i]."\">".$sourceUrl['host']."</a> ", $content);
			}
			return $content;
		} else {
			return $content;
		}
	}
	
	// This function fetched pin from pintrest
	public static function fetched_pin_fromm_pinterest($username,$boradname,$page_number,$page_pin_limit,$column_number,$show_more_button_text,$class_number){

		if(!empty($boradname)){

			// Get pins using boradname
			$output = file_get_contents("https://api.pinterest.com/v3/pidgets/boards/" . $username . "/" . $boradname . "/pins/");
		}else{

			// Get pins using username
			$output = file_get_contents("https://api.pinterest.com/v3/pidgets/users/" . $username . "/pins/");
		}
		
		if ( !empty( $output ) ) {
			$pins_data=json_decode($output);		
			$pin_have=count($pins_data->data->pins);
			if($pin_have!=0){
				$data_have=true;
			}
		}		

		// Curren page number
		$old_number=$page_number-1;
		
		// Column numbers
		$column_number=$column_number;
		
		// New Pin number
		$pin_start=$page_pin_limit*$old_number;

		// New Page last pin number
		$total_item=$page_pin_limit*$page_number;
		
										
		$count=1;
		$as_pins="";
		$as_load_more_pins=[];
		$load_button="";
		$total_get_pins= count($pins_data->data->pins);			
		$pins=$pins_data->data->pins;
		
		if( $total_get_pins < $total_item ):
			$total_item = $total_get_pins;
		endif;	
		
		// Loop of pins for display on page
		for($i=$pin_start;$i < $total_item; $i++){	
			$image_url="";	

			// Get image URL	
			foreach($pins[$i]->images as $images ){
				$image_url=$images->url;
			}

			// Check image URL
			if(!empty($image_url) && @getimagesize($image_url)){														
					$as_pins.= '<div '.$count.' class="as_pin_box as-element-item as_column_'.$column_number.'">';
						$as_pins.= '<div class="as_pin_box_details">';
							$as_pins.= '<a href="https://pinterest.com/pin/'.$pins[$i]->id.'" target="_blank">';
								$as_pins.= '<img src="'.$image_url.'" class="as_pin_img" />';
							$as_pins.= '</a>';	
								$as_pins.= '<div class="as_pin_detail" >';
									$as_pins.= '<div class="as_pin_description">'.as_pinterest_fucntion::as_create_link_in_content($pins[$i]->description).'</div>';																																
								$as_pins.= '</div>';
								$as_pins.= '<div class="pinner_details">';
									$as_pins.= '<a href="'.$pins[$i]->pinner->profile_url.'" target="_blank">';
										$as_pins.= '<img src="'.$pins[$i]->pinner->image_small_url.'" class="as_pinner_img" />';
										$as_pins.= '<div class="as_pinner_name">'.$pins[$i]->pinner->full_name.'</div>';														
									$as_pins.= '</a>';
								$as_pins.= '</div>';
								$as_pins.= '<div class="clear_div"></div>';		
							$as_pins.= '</div>';
						$as_pins.= '</div>';
				$count++;
			}
		} 		
		
		$as_pins .='<script>';

		if($total_get_pins <= $page_pin_limit){
			$as_pins .="

			jQuery(document).ready(function($){";
			$as_pins .= "	$('.main_pins_load_button_".$class_number."').remove();";	
			$as_pins .="});

			";
		}

		$as_pins .='var total_get_pins_'.$class_number.'="'.$total_get_pins.'"';
		$as_pins .='</script>';

		return $as_pins;	
	}

	// This function use for get show more pin
	public static function as_get_more_pins() {
		$username=$_REQUEST['username'];
		$boradname=$_REQUEST['boardname'];
		$page_item=$_REQUEST['page_item'];
		$page_number=$_REQUEST['page_number'];
		$column_number=$_REQUEST['column_number'];
		$class_number=$_REQUEST['class_number'];
		
		// Call as_pinterest_fucntion::fetched_pin_fromm_pinterest for fetched pins
		echo as_pinterest_fucntion::fetched_pin_fromm_pinterest($username,$boradname,$page_number,$page_item,$column_number,$show_more_button_text,$class_number);
		wp_die();
	}
}

?>
