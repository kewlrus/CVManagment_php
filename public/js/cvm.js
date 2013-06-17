$(document).ready(function () 
{
	user_id = $("#seluser" + " option:selected").attr('id');
	$.get("/cvmanagment/getjobs/" + user_id, function(data) {
		$("#ajaxjobs").html(data);
	});
});

jQuery(function($) {

	$('#seluser').change(function() {
		user_id = $("#seluser" + " option:selected").attr('id');
		window.location.href = "/cvmanagment/index/" + user_id;
	});

    $("#create").on('click', function(event){
        event.preventDefault();
        var $cv = $(this);
		
		user_id = $("#seluser" + " option:selected").attr('id');

        $.post("/cvmanagment/add", {
				userid: user_id
			},
			function(data){
				if(data.response == true){
					jQuery.ajax({
						url:    "/cvmanagment/getjobs/" + user_id,
						success: function(result) {
							$("#ajaxjobs").html(result);	
						}
					});          

				} else {
					// print error message
					console.log('could not add');
				}
			}, 'json');
			
		$.get("/cvmanagment/getjobs/" + user_id, function(data) {
		$("#ajaxjobs").html(data);
	});
    });

    $('#cvmanagment').on('click', 'a.delete-cv',function(event){
        event.preventDefault();
        var $cv = $(this);
        var remove_id = $(this).attr('id');
        remove_id = remove_id.replace("remove-","");

        $.post("/cvmanagment/remove", {
            id: remove_id
        },
        function(data){
            if(data.response == true)
                $cv.parent().parent().remove();
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
		user_id = $("#seluser" + " option:selected").attr('id');

        $.post("/cvmanagment/update", {
            id: update_id,
            userid: user_id,
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