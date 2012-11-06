/*
 Admin helper functions for recipe_post
*/
jQuery(function(jQuery) { 
	
	/*
	* On ready make sure indexes are lined up
	*/
	jQuery(document).ready(function() {
		resetIndexes("#recipe-ingredients-table tr.recipe-ingredient-row");
		toggleDirectionsRemove();
	} );
	
	/*
	*  On add row of recipe_ingredient
	*/
    jQuery('.recipe_ingredient_add').click(function() {  
		var rows = jQuery("#recipe-ingredients-table tr.recipe-ingredient-row");
		var insertLocation = jQuery(this).closest("tr");
		var clonedRow = jQuery(insertLocation).clone(true);
		clonedRow.find("input").not("input.recipe_ingredient_button").each(function() {
		    jQuery(this).attr({
		      'value': ''               
		    });
		  }).end();
		clonedRow.insertAfter(jQuery(insertLocation));
		resetIndexes("#recipe-ingredients-table tr.recipe-ingredient-row");
         }); //add function
 
    /*
	 * On remove row of recipe_ingredient
	*/
    jQuery('.recipe_ingredient_remove').click(function() {
		var removeLocation = jQuery(this).closest("tr");
		removeLocation.remove();
		resetIndexes("#recipe-ingredients-table tr.recipe-ingredient-row");
	});
	
	/*
	 * On add of directions
	*/
	jQuery('.recipe_directions_add').click(function() {
		var insertLocation = jQuery(this).closest("li");
		var clonedItem = jQuery(insertLocation).clone(true);
		clonedItem.find("input").not("input.recipe_directions_button").each(function() {
			jQuery(this).attr({
				'value': ''
			});
		});
		clonedItem.insertAfter(jQuery(insertLocation));		
		toggleDirectionsRemove();
		
	});
	
	jQuery('.recipe_directions_remove').click(function()
	{	
		jQuery(this).closest("li").remove();				
		toggleDirectionsRemove();
	});
	
	
	function toggleDirectionsRemove()
	{
		jQuery("#recipe_directions li input.recipe_directions_remove").show();
		jQuery("#recipe_directions li:first input.recipe_directions_remove").hide();
	}
	
	/*
	* reset the id's and names of the elements on each row and update the total counts
	*/
	function resetIndexes(rows){
		  jQuery(rows).each(function(index, rowElement) {   // And all inner elements.
			jQuery(rowElement).find("input.recipe_ingredient_remove").show();			
			jQuery(rowElement).find("input").each(function(colIndex, colElement)
			 {
				if(colElement.id)
				{
			        var matches = colElement.id.match(/(.+)_\d+/);
			        if(matches && matches.length >= 2)            // Captures start at [1].
					{
						var newname = matches[1] + "_" + index;
			            colElement.id = newname;
						colElement.name = newname;
					}
					
				}
				
			 }); //colElements
			}); //rowElements
			
			if (jQuery(rows).length == 1)
			{
				// hide the remove button
				jQuery(rows).first().find("input.recipe_ingredient_remove").hide();
			}
			jQuery("input#recipe-ingredients-count").attr({'value' : jQuery(rows).length });
			
		}
		
		/*
		* handler to allow image upload for each of the directions
		*/
	    jQuery('.custom_upload_image_button').click(function() {  
		        formfield = jQuery(this).siblings('.custom_upload_image');  
		        preview = jQuery(this).siblings('.custom_preview_image');  
		        tb_show('', 'media-upload.php?type=image&TB_iframe=true');  
		        window.send_to_editor = function(html) {  
		            imgurl = jQuery('img',html).attr('src');  
		            classes = jQuery('img', html).attr('class');  
		            id = classes.replace(/(.*?)wp-image-/, '');  
		            formfield.val(id);  
		            preview.attr('src', imgurl);  
		            tb_remove();  
		        }  
		        return false;  
		    });  

		/*
		* handler to clear image for each of the directions photo
		*/
		
   		jQuery('.custom_clear_image_button').click(function() {  
	        var defaultImage = jQuery(this).parent().siblings('.custom_default_image').text();  
	        jQuery(this).parent().siblings('.custom_upload_image').val('');  
	        jQuery(this).parent().siblings('.custom_preview_image').attr('src', defaultImage);  
	        return false;  
	    });  
});