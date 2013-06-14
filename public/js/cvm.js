jQuery(function($) {
    $("#create").on('click', function(event){
        event.preventDefault();
        var $cv = $(this);
        $.post("cvmanagment/add", null,
            function(data){
                if(data.response == true){
					alert(data.new_cv_id);
                    $cv.before("<div class=\"sticky-note\"><textarea id=\"cv-"+data.new_cv_id+"\"></textarea><a href=\"#\" id=\"remove-"+data.new_cv_id+"\"class=\"delete-sticky\">X</a></div>");
                // print success message
                } else {
                    // print error message
                    console.log('could not add');
                }
            }, 'json');
    });

    $('#cvmanagment').on('click', 'a.delete-cv',function(event){
        event.preventDefault();
        var $cv = $(this);
        var remove_id = $(this).attr('id');
        remove_id = remove_id.replace("remove-","");

        $.post("cvmanagment/remove", {
            id: remove_id
        },
        function(data){
            if(data.response == true)
                $cv.parent().remove();
            else{
                // print error message
                console.log('could not remove ');
            }
        }, 'json');
    });
	
	 $('#cvmanagment').on('click', 'a.save-cv', function(event){
        var cv = $(this);
        update_id = cv.attr('id'),

		description_content = $(".cv-descr#cv-" + update_id).val();
		technologies_content = $(".cv-tech#cv-" + update_id).val();
		employer_id = $("#sel-" + update_id + " option:selected").attr('id');
		data_from = $("#calendarfrom-" + update_id).val();
		data_to = $("#calendarto-" + update_id).val();

        $.post("cvmanagment/update", {
            id: update_id,
            employerid: employer_id,
            description: description_content,
            technologies: technologies_content,
            datafrom: data_from,
            datato: data_to
        },function(data){
            if(data.response == false){
                // print error message
                cv.html('Save This Experience: Could not update!');
            }
			else
			{
				cv.html('Save This Experience: Saved!');
			}
        }, 'json');

    });
});