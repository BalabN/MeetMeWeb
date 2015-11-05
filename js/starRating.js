    // This is the first thing we add ------------------------------------------
    $(document).ready(function() {
        
        $('.rate_widget').each(function(i) {
			console.log("tuki sam1");
            var widget = this;
            var out_data = {
                widget_id : $(widget).attr('id'),
				place_id: placeID,
                fetch: 1
            };
			var data_json = JSON.stringify(out_data);
			console.log(out_data);
            $.post(
                "http://localhost/meetme/ratings.php",
                {out_data: data_json},
                function(data) {
					console.log("Tuki sam 3");
                    $(widget).data( 'fsr', data );
                    set_votes(widget);
					console.log("vraio je " + data);
                }
            );
        });
    

        $('.ratings_stars').hover(
            // Handles the mouseover
            function() {
                $(this).prevAll().andSelf().addClass('ratings_over');
                $(this).nextAll().removeClass('ratings_vote'); 
            },
            // Handles the mouseout
            function() {
                $(this).prevAll().andSelf().removeClass('ratings_over');
                // can't use 'this' because it wont contain the updated data
                set_votes($('.ratings_stars').parent());
            }
        );
        
        
        // This actually records the vote
        $('.ratings_stars').bind('click', function() {
            var star = this;
            var widget = $(this).parent();
            var clicked_data = {
                clicked_on : $(star).attr('class'),
                widget_id : $(star).parent().attr('id'),
				place_id: placeID,
				fetch: 0
            };
			var clicked_data_json = JSON.stringify(clicked_data);
            $.post(
                'ratings.php',
                {out_data: clicked_data_json},
                function(INFO) {
                    widget.data( 'fsr', INFO );
					console.log("vraio je poslje glasnja" + INFO);
                    set_votes(widget);
                }
            ); 
        });
        
        
        
    });

    function set_votes(widget) {
		console.log("tuki sem 2");
        var avg = $(widget).data('fsr');
      //  var votes = $(widget).data('fsr').number_votes;
        //var exact = $(widget).data('fsr').dec_avg;
    
        console.log('and now in set_votes, it thinks the fsr is ' + $(widget).data('fsr').number_votes);
        
        $(widget).find('.star_' + avg).prevAll().andSelf().addClass('ratings_vote');
        $(widget).find('.star_' + avg).nextAll().removeClass('ratings_vote'); 
    //    $(widget).find('.total_votes').text( votes + ' votes recorded (' + exact + ' rating)' );
    }
