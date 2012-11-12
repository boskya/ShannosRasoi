/*
 Admin helper functions for recipe_post
*/
jQuery(function(jQuery) { 
	
	var recipeRow = "tr.recipe-row";
	
	/*
	* On ready make sure indexes are lined up
	*/
	jQuery(document).ready(function() {
		resetIndexes("#recipe-ingredients-table");
		resetIndexes("#recipe-directions");
	} );
	
	/*
	*  On add row of recipe_ingredient
	*/
    jQuery('.recipe_row_add').click(function() {  
		var rows = jQuery(this).closest("table").find(recipeRow);
		var insertLocation = jQuery(this).closest("tr");
		var clonedRow = jQuery(insertLocation).clone(true);
		clonedRow.find("input, textarea").not("input.recipe_row_button").each(function() {
		    jQuery(this).attr({
		      'value': ''               
		    });
		  }).end();
		clonedRow.insertAfter(jQuery(insertLocation));
		resetIndexes(jQuery(this).closest("table"));
         }); //add function
 
    /*
	 * On remove row of recipe_ingredient
	*/
    jQuery('.recipe_row_remove').click(function() {
		var table = jQuery(this).closest("table");
		var removeLocation = jQuery(this).closest("tr");
		removeLocation.remove();
		resetIndexes(table);
	});
	
	
	/*
	* reset the id's and names of the elements on each row and update the total counts
	*/
	function resetIndexes(table){
		  var rows = jQuery(table).find(recipeRow); 
		  jQuery(rows).each(function(index, rowElement) {   // And all inner elements.
			jQuery(rowElement).find("input.recipe_row_remove").show();			
			jQuery(rowElement).find("input, textarea").each(function(colIndex, colElement)
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
				jQuery(rows).first().find("input.recipe_row_remove").hide();
			}
			jQuery(table).find("input.recipe-table-count").attr({'value' : jQuery(rows).length });
			
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
	        jQuery(this).siblings('.custom_upload_image').attr({ 'value':'' } );  
	        jQuery(this).siblings('.custom_preview_image').attr({'src':'img/image.png'});  
	        return false;  
	    });  
	
	
});