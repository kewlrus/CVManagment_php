$(document).ready(function () 
{
	user_id = $("#seluser" + " option:selected").attr('id');
	$.get("/cvmanagment/getjobs/" + user_id, function(data) {
		$("#ajaxjobs").html(data);
	});
});

jQuery(function($) {
	//Users operations
	$('#seluser').change(function() {
		user_id = $("#seluser" + " option:selected").attr('id');
		window.location.href = "/cvmanagment/index/" + user_id;
	});
	
    $("a.add-user").on('click', function(event){
		event.preventDefault();
		
        $.post("/user/add/", null,
			function(data){
				if(data.response == true){
					window.location.href = "/cvmanagment/index/" + data.new_user_id;   
				} else {
					// print error message
					console.log('could not add');
				}
			}, 'json');
	});
		
    $("a.save-user").on('click', function(event){
	    var cv = $(this);
        user_id = cv.attr('id'),

		_name = $("textarea#name").val();
		_email = $("textarea#email").val();
		_skype = $("textarea#skype").val();
		_phone = $("textarea#phone").val();
		_url = $("textarea#url").val();

        $.post("/user/update/", {
            userid: user_id,
            name: _name,
            email: _email,
            skype: _skype,
            phone: _phone,
            url: _url,
        },function(data){
            if(data.response == false){
                // print error message
                cv.html('Save This User: Could not update!');
            }
			else
			{
				$("#seluser" + " option:selected").html(_name);
				cv.html('Save This User: Saved!');
			}
        }, 'json');

	});
	
    $("a.delete-user").on('click', function(event){
		event.preventDefault();
        //var $cv = $(this);
        var remove_id = $(this).attr('id');

        $.post("/user/remove/", {
            userid: remove_id
        },
        function(data){
            if(data.response == true)
				window.location.href = "/";
            else{
                // print error message
                console.log('could not remove ');
            }
        }, 'json');
	});
	

	// Jobs experience operations
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