$=jQuery;
$(document).ready(function(){
    $('.as-color-field').wpColorPicker();
});

$(document).on('click','.edit_page',function(){
	var data_id=$(this).attr('data-id');
	$(".iedit").css('display','none');
	$(".ilist").css('display','table-row');
	$("#post-"+data_id).css('display','none');
	$("#edit-"+data_id).css('display','table-row');
});
$(document).on('click','.pin_save',function(){
	var data_id=$(this).attr('data-id');
	var pinterest_username=$(".pinterest_username-"+data_id).val();
	var pinterest_boardname=$(".pinterest_boardname-"+data_id).val();
	var per_page_pin_limite=$(".per_page_pin_limite-"+data_id).val();
	var show_more_button_text=$(".show-more-button-text-"+data_id).val();
	var show_more_button_text_color=$(".as-color-field-"+data_id).val();
	var show_more_button_backgound_color=$(".as-backgound-field-"+data_id).val();
	var as_pin_box_color_field=$(".as-pin-box-color-field-"+data_id).val();
	var as_color_text_field=$(".as-color-text-field-"+data_id).val();
	var as_pin_box_style_field=$(".as-pin-box-style-field-"+data_id).val();
	var column_number=$(".as-column-field-"+data_id).val();
	var post_title=$(".ptitle-"+data_id).val();
	var data = {
		'action': 'as_edit_pins_page',
		'pinterest_username':pinterest_username,
		'pinterest_boardname':pinterest_boardname,
		'per_page_pin_limite':per_page_pin_limite,
		'show_more_button_text':show_more_button_text,
		'post_title':post_title,
		'show_more_button_text_color':show_more_button_text_color,
		'show_more_button_backgound_color':show_more_button_backgound_color,
		'column_number':column_number,
		'as_pin_box_color_field':as_pin_box_color_field,
		'as_color_text_field':as_color_text_field,
		'as_pin_box_style_field':as_pin_box_style_field,
		'post_id':data_id 
	};
	jQuery.post(as_ajaxurl, data, function(response) {			
		data_count=response;
		$(".short-code-"+data_id+" strong").html('[as_pinterest_page id="'+data_id+'" page_name="'+post_title+'"]');
		$("#edit-"+data_id).css('display','none');
		$("#post-"+data_id).css('display','table-row');
	});	
});
$(document).on('click','.delete_page',function(){
	var data_id=$(this).attr('data-id');
	var data = {
		'action': 'as_delete_pins_page',
		'post_id':data_id 
	};
	jQuery.post(as_ajaxurl, data, function(response) {			
		data_count=response;		
		$("#edit-"+data_id).remove();
		$("#post-"+data_id).remove();
	});	
});
$(document).on('click','.pin_cancel',function(){
	var data_id=$(this).attr('data-id');
	$("#edit-"+data_id).css('display','none');
	$("#post-"+data_id).css('display','table-row');
});

var data_count=1;
$(document).on('click','.create_new_page',function(){
	var data = {
		'action': 'as_create_pins_page'
	};
	jQuery.post(as_ajaxurl, data, function(response) {			
		data_count=response;
		$("#the-list").append('<tr id="post-'+data_count+'" class="ilist"><td class="has-row-actions column-primary short-code-'+data_count+'" >				<strong>[as_pinterest_page id="'+data_count+'"  page_name=""]</strong>					</td>			<td class="has-row-actions column-primary" >				<a  class="button button-primary button-large edit_page" data-id="'+data_count+'">Edit</a>				<a  class="button button-primary button-large delete_page" data-id="'+data_count+'">Delete</a>			</td>		</tr>		<tr id="edit-'+data_count+'" class="iedit inline-edit-row inline-edit-row-post inline-edit-post quick-edit-row quick-edit-row-post inline-edit-post inline-editor">			<td class="has-row-actions column-primary edit-setting" colspan="2">				<fieldset class="edit_pinterest_page">					<legend class="inline-edit-legend">Edit Pinterest Detail</legend>					<table><tr>	<td>		<span class="label">Page Title</span>	</td>	<td  class="padding_right">		<span class="input-text-wrap"><input type="text" name="page_title" class="ptitle ptitle-'+data_count+'" value=""></span>	<div class="help-tip">				<p class="help_msg">Enter unique title of pinterest setting.</p>			</div></td>	<td>		<span class="label pinterest_username">Pinterest Username</span>	</td>	<td  class="padding_right">		<span class="input-text-wrap"><input type="text" name="pinterest_username" class="pinterest_username-'+data_count+'" value=""></span>			<div class="help-tip">				<p class="help_msg">Enter your pinterest username here.</p>			</div>				</td></tr><tr>	<td>		<span class="label  ">Pinterest boardname</span>	</td>	<td  class="padding_right">		<span class="input-text-wrap"><input type="text" name="pinterest_boardname" class="pinterest_boardname-'+data_count+'" value=""></span>					<div class="help-tip">				<p class="help_msg">Enter your pinterest boardname here.</p>			</div>		</td>	<td>		<span class="label per_page_pin_limite">Per Page Pins Limit</span>	</td>	<td  class="padding_right">		<span class="input-text-wrap"><input type="text" name="per_page_pin_limite" class="per_page_pin_limite-'+data_count+'" value=""></span>				<div class="help-tip">				<p class="help_msg">Enter limit of pins you want to show on page.</p>			</div>			</td></tr>	<tr>		<td>			<span class="label pinterest_boardname">Show more button text</span>		</td>		<td class="padding_right">			<span class="input-text-wrap"><input type="text" name="show-more-button-text" class="show-more-button-text-'+data_count+'" value="Show more"></span>			<div class="help-tip">				<p class="help_msg">Enter show more button text here.</p>			</div>		</td><td><span class="label pinterest_boardname">Select column number</span></td><td class="padding_right"><span class="input-text-wrap"><select class="as-column-field-'+data_count+'" ><option value="1" >1</option><option value="2" >2</option><option value="3" >3</option><option value="4" >4</option></select></span><div class="help-tip"><p class="help_msg">Set column number you want to show in row.</p></div></td>	<tr>		<td>			<span class="label pinterest_boardname">Show more button backgound color</span>		</td>		<td class="padding_right">			<span class="input-text-wrap"><input type="text" value="#000000" class="as-color-field as-backgound-field-'+data_count+'" /></span>			<div class="help-tip">				<p class="help_msg">Set show more button backgound color.</p>			</div>		</td><td><span class="label pinterest_boardname">Show more button text color</span></td><td class="padding_right"><span class="input-text-wrap"><input type="text" value="#ffffff" class="as-color-field as-color-field-'+data_count+'" /></span><div class="help-tip"><p class="help_msg">Set show more button text color.</p>			</div>		</td>	</tr></tr>	<tr><td><span class="label pinterest_boardname">Pin box background color</span></td><td class="padding_right"><span class="input-text-wrap"><input type="text" value="" class="as-color-field as-pin-box-color-field-'+data_count+'" /></span><div class="help-tip"><p class="help_msg">This color apply in pin box background.</p></div></td><td><span class="label pinterest_boardname">Pin box text color</span></td><td class="padding_right"><span class="input-text-wrap"><input type="text" value="" class="as-color-field as-color-text-field-'+data_count+'" /></span><div class="help-tip"><p class="help_msg">This color apply in pin box text.</p></div></tr><tr><td><span class="label pinterest_boardname">Select pin box style</span></td><td class="padding_right"><span class="input-text-wrap"><select class="as-pin-box-style-field-'+data_count+'" ><option value="1" >Style 1</option>option value="2" >Style 2</option><option value="3" >Style 3</option><option value="4" >Style 4</option><option value="5" >Style 5</option><option value="6" >Style 6</option><option value="7" >Style 7</option><option value="8" >Style 8</option><option value="9" >Style 9</option><option value="10" >Style 10</option></select></span><div class="help-tip"><p class="help_msg">Set pin box style here.</p></div></td></tr>			</table>			</fieldset>				<p class="submit inline-edit-save">					<button type="button" class="button-secondary pin_cancel alignleft" data-id="'+data_count+'">Cancel</button>					<button type="button" class="button-primary pin_save alignleft" data-id="'+data_count+'">Save</button></p>		</td>		</tr>');
		$('.as-color-field').wpColorPicker();
		$(".iedit").css('display','none');
		$(".ilist").css('display','table-row');
		$("#post-"+data_count).css('display','none');
		$("#edit-"+data_count).css('display','table-row');
	});
	
});
