<?php
/**
 * @package Recipify
 * @subpackage Recipify_post
 * @version 0.1
 * @author Bosky Atlani
 * @link http://boskyatlani.me
 * @license http://www.gnu.org/licenses/gpl-2.0.html
 */

/*
 * Contains class for creating the custom post type 
 *
*/
class Recipify_post {
	
	function setup_post() {
		$this->create_post_type();	
		add_action('save_post',array($this,'save_post'))	;
	}
	
	function setup_scripts()
	{
		add_action('admin_enqueue_scripts', array($this,'load_scripts'));		
		
	}
	
	/* 
	 *  Load appropriate scripts for the admin view
	 */
	function load_scripts()
	{
		wp_enqueue_script('recipify_post', trailingslashit(get_template_directory_uri()) . 'library/js/recipify_post.js', array('jquery','media-upload','thickbox'));		
		wp_enqueue_style('thickbox');
	}
	
	
	/*
	* Add support for custom post type of type 'Recipe'
	*/
	function create_post_type() {
		$labels = array(
	    	'name' => __('Recipes', 'Recipify'),
			'singular_name' => __('Recipe', 'Recipify'),
			'add_new' => __( 'Add New Recipe', 'Recipify' ),
	    	'add_new_item' => __( 'Add New Recipe', 'Recipify' ),
	    	'edit_item' => __( 'Edit Recipe', 'Recipify' ),
	    	'new_item' => __( 'Add New Recipe', 'Recipify' ),
	    	'view_item' => __( 'View Recipe', 'Recipify' ),
	    	'search_items' => __( 'Search Recipe', 'Recipify' ),
	    	'not_found' => __( 'No recipes found', 'Recipify' ),
	    	'not_found_in_trash' => __( 'No recipes found in trash', 'Recipify' )
			);
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
		    'show_ui' => true, 
		    'query_var' => true,
		    'rewrite' => true,
		    'capability_type' => 'post',
			'hierarchical' => false,
			'taxonomies' => array('category','post_tag',),
			'supports' => array(
			            'title','trackbacks','comments','author'),
			'register_meta_box_cb' => array($this,'recipe_add_meta_boxes')
	   
			); 
			register_post_type('recipe',$args);		

		}
		
	
	
		/*
			add fields for recipe post type
		*/
		function recipe_meta_boxes_setup() {
			 add_action('add_meta_boxes', array($this,'recipe_add_meta_boxes'));
		}
	
		/*
		*/
		function recipe_add_meta_boxes()
		{
			add_meta_box('0recipe_description',
						 'Description',
						  array($this,'display_description'),
						 'recipe',
						 'normal',
						 'high');
						
			add_meta_box('1recipe_final_image',
						  'Recipe Image',
						  array($this,'display_recipe_final_image'),
						  'recipe',
						  'normal',
						   'high');
			
			add_meta_box('2recipe_ingredients', 
						'Recipe Ingredients',
						array($this,'display_ingredients'),
						'recipe',
						'normal',
						'high');
					
			add_meta_box('3recipe_directions',
						  'Recipe Directions',
						  array($this,'display_directions'),
						  'recipe',
						  'normal',
						  'high');
						
		}
		
		function display_description($object, $box)
		{
			global $post;
			?>
			<?php wp_nonce_field(basename(__FILE__),'recipe_description_nonce');
			$meta = get_post_meta($post->ID, "recipe_description", true); ?>
			<textarea name="recipe_description" rows="3" cols="100"><?php echo $meta ?></textarea>
			<?php
		}
		
		
		function display_ingredients($object, $box)
		{
			global $post;
			?>
			<?php wp_nonce_field(basename (__FILE__), 'recipe_ingredients_nonce'); ?>
			<table id="recipe-ingredients-table">
			<input type="hidden" name="recipe-ingredients-count" id="recipe-ingredients-count" class="recipe-table-count" value=0/>
			 <tr>
			   <th><label for="recipe-ingredient-name"><?php _e("Ingredient Name", 'Paneer Tikka'); ?></label></th>
			   <th><label for="recipe-ingredient-quantity"><?php _e("Ingredient Quantity", '1');?></label></th>
			   <th><label for="recipe-ingredient-measure"><?php _e("Ingredient Measure", 'tablespoon');?></label></th>
			   <th><label for="recipe-ingredient-notes"><?php _e("Ingredient Notes", 'finely chopped');?></label></th>
			
			   <th></th>
			   <th></th>
			 </tr>
			 <?php $meta_arr = json_decode(get_post_meta($post->ID, "recipe-ingredients", true), true);
			  if ($meta_arr)
			  { 
				foreach($meta_arr as $ingredient)
				{ 
					$this->recipe_ingredients_table($ingredient['name'],$ingredient['quantity'],$ingredient['measure'], $ingredient['notes']);
				} // end for loop for ingredients
			  } // end if there was meta value
			  else 
			  {
				 $this->recipe_ingredients_table('','','','', '');
			  } // end else
			 ?>  
				
			</table>
			<?php 			
			
		}
		
		function display_directions($object, $box)
		{
				global $post;
				?>
				<?php wp_nonce_field(basename (__FILE__), 'recipe_directions_nonce'); ?>
				<table id="recipe-directions">
				<input type="hidden" name="recipe-directions-count" id="recipe-directions-count" class="recipe-table-count" value=0/>				
				
				 <tr>
				   <th><label for="direction-step"><?php _e("Direction step", ''); ?></label></th>
				   <th><label for="direction-picture"><?php _e("Direction Photo", '');?></label></th>
				   <th></th>
				   <th></th>
				</tr>
				<?php 
				$meta = json_decode(get_post_meta($post->ID, "recipe-directions-table", true), true);
			    if ($meta) {  
			        foreach($meta as $directions_row) {   
			        	$this->directions_table($directions_row);
			        }  
			     } 
				 else 
				 { 
							$this->directions_table('');
			     }
				?>
				</table>
				<?php
		}
		
		
		function display_directions_gallery($object, $box)
		{
			global $post;
			
			wp_nonce_field(basename (__FILE__), 'recipe_directions_gallery_nonce'); 
			
			// get value of this field if it exists for this post
			$meta = get_post_meta($post->ID, 'recipe_directions_gallery', true);
			echo '<ol id="directions-gallery">';
						
			if ($meta) 
			{  
				foreach($meta as $row) 
				{   
		        	$this->directions_gallery_list($row);
		        }
			}
			else
			{
				$this->directions_gallery_list(null);
			} 
			 
		
			echo '</ol>';
		}
		
		function display_recipe_final_image($object, $box)
		{
			global $post;

			wp_nonce_field(basename (__FILE__), 'recipe_final_image_nonce');		
			$meta = get_post_meta($post->ID, 'recipe_final_image', true);
			$image = get_template_directory_uri().'/img/image.png';  
			if ($meta)
			{
			 	$image =  wp_get_attachment_image_src($meta, 'medium'); 
				$image = $image[0]; 
			}
				
			echo  '<input name="recipe-final-image" type="hidden" class="custom_upload_image" value="'.$meta.'" /> 
			           <img src="'.$image.'" class="custom_preview_image" alt="" /><br /> 
			              <input class="custom_upload_image_button button" type="button" value="Choose Image" />
						  <input class="custom_clear_image_button button" type="button" value="Clear Image" /> '; 
		}
		
		
		function directions_table($directions_row)
		{
				$image = get_template_directory_uri().'/img/image.png';  
				$direction_step = "";
				$direction_photo = "";
				
				if (!empty($directions_row))
				{
					$direction_step = $directions_row["step"];
					$direction_photo = $directions_row["photo"];

					if ($direction_photo)
					{
				 		$image =  wp_get_attachment_image_src($direction_photo, 'medium'); 
						$image = $image[0]; 
					}
					
				}
				$direction_photo_src =  $image;
				
				?>
					<tr class="recipe-row" style="width:100%">
			  			<td style="width:80%">		
				     		   <textarea name="recipe-directions-step_0" id="recipe-directions-step_0" rows="4" style="width:100%"><?php echo $direction_step ?></textarea> 
			  			</td>
			  			<td style="width:20%">
							<input name="recipe-directions-photo_0" id="recipe-directions-photo_0" type="hidden" class="custom_upload_image" value="<?php echo $direction_photo ?>" /> 
							   <img src="<?php echo $direction_photo_src ?>" class="custom_preview_image" alt="" /><br /> 
						   		<input class="custom_upload_image_button button recipe_row_button" type="button" value="Choose Image" /> 
							     <input type="button"  class="custom_clear_image_button recipe_row_button button" value="Clear Image"/>
						</td>
			  			<td>
							<input type="button" class="recipe_row_add recipe_row_button"  value="Add"/>
			  			</td>
			  			<td>
							<input type="button" class="recipe_row_remove recipe_row_button" value="Remove" />
			  			</td>		  
		  	  		</tr>
				 <?php
			?>
			<?php
		}

		function directions_gallery_list($row)
		{
			$image = get_template_directory_uri().'/img/image.png';  
			if ($row)
			{
			 	$image =  wp_get_attachment_image_src($row, 'medium'); 
				$image = $image[0]; 
			}	
				echo '<li>'	;
			    echo  '<input name="recipe-directions-gallery[]" type="hidden" class="custom_upload_image" value="'.$row.'" /> 
			                <img src="'.$image.'" class="custom_preview_image" alt="" /><br /> 
			                    <input class="custom_upload_image_button button recipe_directions_button" type="button" value="Choose Image" /> 
			                       <input type=button  class="recipe_directions_add recipe_directions_button" value="Add"/>    
						          <input type=button  class="recipe_directions_remove recipe_directions_button" value="Remove"/>';
				echo '</li>';
		}


	function recipe_ingredients_table($name, $quantity, $measure, $notes)
	{
		?>
			<tr class="recipe-row">
	  			<td>		
	     			<input class="widefat" type="text" name="recipe-ingredient-name_0" id="recipe-ingredient-name_0" 
	 					value="<?php echo $name ?>"
						size ="30" />
	  			</td>
	  			<td>
	  				<input class="widefat" type="text" name="recipe-ingredient-quantity_0" id="recipe-ingredient-quantity_0" 
						value="<?php echo $quantity ?>"
						size ="5" />
	   			</td>
	  			<td>
	  				<input class="widefat" type="text" name="recipe-ingredient-measure_0" id="recipe-ingredient-measure_0" 
						value="<?php echo $measure ?>"
						size ="20" />
	   			</td>
	
				<td>
					<input class="widefat type="text" name="recipe-ingredient-notes_0" id="recipe-ingredient-notes_0" value="<?php echo $notes ?>" size="40" />
				</td>
	  			<td>
					<input type="button" class="recipe_row_add recipe_row_button"  value="Add"/>
	  			</td>
	  			<td>
					<input type="button" class="recipe_row_remove recipe_row_button" value="Remove" />
	  			</td>		  
  	  		</tr>
		 <?php
	}
	
	function save_post($post_id)
	{
	    if ( empty($post_id) )  
	        return;
	
		if(!$this->verify_nonce($post_id))
			return $post_id;

	    // check autosave  
	    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)  
	        return $post_id;  

	    // check permissions  
	    if ('recipe' == $_POST['post_type']) {  
	        if (!current_user_can('edit_page', $post_id))  
	            return $post_id;  
	        } elseif (!current_user_can('edit_post', $post_id)) {  
	            return $post_id;  
	    }
				
		$this->save_description($post_id);
		$this->save_ingredients($post_id);
		$this->save_directions($post_id);
		$this->save_recipe_final_image($post_id);
	   
	
	}
	
	function verify_nonce($post_id)
	{
	 	if (!isset($_POST['recipe_ingredients_nonce']) || !wp_verify_nonce($_POST['recipe_ingredients_nonce'], basename(__FILE__)))  
		     return false;
		
		if (!wp_verify_nonce($_POST['recipe_final_image_nonce'], basename(__FILE__)))
				return false;				

		if (!wp_verify_nonce($_POST['recipe_directions_nonce'], basename(__FILE__)))
			return false;
		
		if (!wp_verify_nonce($_POST['recipe_description_nonce'], basename(__FILE__)))
			return false;
			
		return true;
	}
	
	function save_ingredients($post_id)
	{
		// get me the count
		$ingredients_count = $_POST['recipe-ingredients-count'];
		$old_meta= get_post_meta($post_id, "recipe-ingredients", true);

		$arr = array();
		for ($i=0; $i < $ingredients_count; $i++)
		{
		   	// get me the recipe ingredients
			$ingredient_name = $_POST['recipe-ingredient-name_' . $i];
			$ingredient_quantity = $_POST['recipe-ingredient-quantity_' . $i];
			$ingredient_notes =  $_POST['recipe-ingredient-notes_'. $i];
			$ingredient_measure = $_POST['recipe-ingredient-measure_'. $i];
			$ingredient_item = array();
			$ingredient_item["name"] = $ingredient_name;
			$ingredient_item["quantity"] = $ingredient_quantity;
			$ingredient_item["notes"] = $ingredient_notes;
			$ingredient_item["measure"] = $ingredient_measure;
			$arr[$i] = $ingredient_item;
		 }		
		 $recipe_ingredient_json = json_encode($arr);
	  	 update_post_meta($post_id,'recipe-ingredients',$recipe_ingredient_json);
	}
	
	function save_directions($post_id)
	{
			// get me the count
			$directions_count = $_POST['recipe-directions-count'];
			$old_meta= get_post_meta($post_id, "recipe-directions-table", true);

			$arr = array();
			for ($i=0; $i < $directions_count; $i++)
			{
			   	// get me the recipe directions
				$direction_step = $_POST['recipe-directions-step_' . $i];
				$direction_photo = $_POST['recipe-directions-photo_' . $i];
				$direction_item = array();
				$direction_item["step"] = $direction_step;
				$direction_item["photo"] = $direction_photo;
				$arr[$i] = $direction_item;
			 }		
			 $directions_json = json_encode($arr);
		  	 update_post_meta($post_id,'recipe-directions-table',$directions_json);
	}
	
	
	function save_description($post_id)
	{				
		$recipe_description = $_POST['recipe_description'];
		update_post_meta($post_id,'recipe_description', $recipe_description);
	}
	
	function save_recipe_final_image($post_id)
	{
	 	$recipe_final_image = $_POST['recipe-final-image'];
		update_post_meta($post_id,'recipe_final_image',$recipe_final_image);
	}
	
	
}