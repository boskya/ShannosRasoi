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
	
	function setup() {
	  if (is_admin())
	 {
		$this->create_post_type();	
		add_action('save_post',array($this,'save_post'))	;
	}
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
		wp_enqueue_script('recipify_post', trailingslashit(get_template_directory_uri()) . 'library/js/recipify_post.js');		
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
			'taxonomies' => array('category','post-tag',),
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
			add_meta_box('recipe_ingredients', 
						'Recipe Ingredients',
						array($this,'display_ingredients'),
						'recipe',
						'normal',
						'default');
					
			add_meta_box('recipe_directions',
						  'Recipe Directions',
						  array($this,'display_directions'),
						  'recipe',
						  'normal',
						  'default');
		}
		
		function display_ingredients($object, $box)
		{
			global $post;
			?>
			<?php wp_nonce_field(basename (__FILE__), 'recipe_ingredients_nonce'); ?>
			<input type="hidden" name="recipe-ingredients-count" id="recipe-ingredients-count" value=0/>
			<table id="recipe-ingredients-table">
			 <tr>
			   <th><label for="recipe-ingredient-name"><?php _e("Ingredient Name", 'Paneer Tikka'); ?></label></th>
			   <th><label for="recipe-ingredient-quantity"><?php _e("Ingredient Quantity", '1 tablespoon');?></label></th>
			   <th><label for="recipe-ingredient-quantity"><?php _e("Ingredient Notes", 'finely chopped');?></label></th>
			
			   <th></th>
			   <th></th>
			 </th>
			 <?php $meta_arr = json_decode(get_post_meta($post->ID, "recipe-ingredients", true), true);
			  if ($meta_arr)
			  { 
				foreach($meta_arr as $ingredient)
				{ 
					$this->recipe_ingredients_table($ingredient['name'],$ingredient['quantity'],$ingredient['notes']);
				} // end for loop for ingredients
			  } // end if there was meta value
			  else 
			  {
				 $this->recipe_ingredients_table('','','','');
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
				<input type="hidden" name="recipe-directions-count" id="recipe-directions-count" value=0/>
				<ol id="recipe-directions">
				<?php 
				$meta = get_post_meta($post->ID, "recipe_directions", true);
				$i = 0;
			    if ($meta) {  
			        foreach($meta as $row) {   
			        	$this->directions_list($i, $row);
			    		$i++;  
			        }  
			     } 
				 else 
				 { 
					$this->directions_list(0,'');
			     }
		}
		
		function directions_list($i, $row)
		{
			?>
		       <li>
		         <textarea name="recipe-directions-step[]" id="recipe-directions-step_<?php echo $i ?>" rows="3" cols="100"><?php echo $row ?></textarea> 
		      	  <input type=button  class="recipe_directions_add recipe_directions_button" value="Add"/>    
		          <input type=button  class="recipe_directions_remove recipe_directions_button" value="Remove"/>
				</li>  
			<?php
		}

	function recipe_ingredients_table($name, $quantity, $notes)
	{
		?>
			<tr class="recipe-ingredient-row">
	  			<td>		
	     			<input class="widefat" type="text" name="recipe-ingredient-name_0" id="recipe-ingredient-name_0" 
	 					value="<?php echo $name ?>"
						size ="30" />
	  			</td>
	  			<td>
	  				<input class="widefat" type="text" name="recipe-ingredient-quantity_0" id="recipe-ingredient-quantity_0" 
						value="<?php echo $quantity ?>"
						size ="20" />
	   			</td>
				<td>
					<input class="widefat type="text" name="recipe-ingredient-notes_0" id="recipe-ingredient-notes_0" value="<?php echo $notes ?>" size="40" />
	  			<td>
					<input type="button" class="recipe_ingredient_add recipe_ingredient_button"  value="Add"/>
	  			</td>
	  			<td>
					<input type="button" class="recipe_ingredient_remove recipe_ingredient_button" value="Remove" />
	  			</td>		  
  	  		</tr>
		 <?php
	}
	
	function save_post($post_id)
	{
		if (!wp_verify_nonce($_POST['recipe_ingredients_nonce'], basename(__FILE__)))  
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
	
		$this->save_directions($post_id);
	   
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
			$ingredient_item = array();
			$ingredient_item["name"] = $ingredient_name;
			$ingredient_item["quantity"] = $ingredient_quantity;
			$ingredient_item["notes"] = $ingredient_notes;
			$arr[$i] = $ingredient_item;
		 }		
		  $recipe_ingredient_json = json_encode($arr);
  		  update_post_meta($post_id,'recipe-ingredients',$recipe_ingredient_json);
	}
	
	function save_directions($post_id)
	{
		if (!wp_verify_nonce($_POST['recipe_directions_nonce'], basename(__FILE__)))
			return $post_id;
		$recipe_directions = $_POST['recipe-directions-step'];	
		update_post_meta($post_id, 'recipe_directions', $recipe_directions);
		
	}
}