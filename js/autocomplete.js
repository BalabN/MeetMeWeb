var MIN_LENGTH = 2;

$( document ).ready(function() {
	$("#keyword").keyup(function() {
		var keyword = $("#keyword").val();
		if (keyword.length >= MIN_LENGTH) {
			$.get( "include/autocomplete.php", { keyword: keyword } )
			.done(function( data ) {
				$('#results').html('');
				var results = jQuery.parseJSON(data);
				$(results).each(function(key, value) {
					$('#results').append('<div class="item">' + value + '</div>');
					console.log($('#results').value);
				});

			    $('.item').click(function() {
			    	var text = $(this).html();
			    	$('#keyword').val(text);
					console.log($('#keyword').val(text));
			    });
			});
		} else {
			$('#results').html('');
		}
	});

    $("#keyword").blur(function(){
    		$("#results").fadeOut(500);
    	})
        .focus(function() {		
    	    $("#results").show();
    	});
	
	
	
	$("#keyinput").keyup(function() {
		var keyword = $("#keyinput").val();
		if (keyword.length >= MIN_LENGTH) {
			$.get( "include/autocomplete.php", { keyword: keyword } )
			.done(function( data ) {
				$('#addFrResults').html('');
				var results = jQuery.parseJSON(data);
				$(results).each(function(key, value) {
					$('#addFrResults').append('<div class="item">' + value + '</div>');
					console.log($('#addFrResults').value);
				});

			    $('.item').click(function() {
			    	var text = $(this).html();
			    	$('#keyinput').val(text);
					console.log(text);
			    });
			});
		} else {
			$('#addFrResults').html('');
		}
	});

    $("#keyinput").blur(function(){
    		$("#addFrResults").fadeOut(500);
    	})
        .focus(function() {		
    	    $("#addFrResults").show();
    	});
	});
	
	