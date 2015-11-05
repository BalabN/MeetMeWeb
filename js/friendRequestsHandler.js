// JavaScript Document


$( document ).ready(function() {
	
	
	// Send request
	$("#sendReq").on('click', function() {
		var friendName = $("#keyinput").val();
		if ($.trim(friendName) !== ''){
				$.post('sendRequest.php', {friendName: friendName}, function(data){
						$('#requestStatus').text(data);
					});
		}
	});
	
	
});

// Accept/reject request
	function responseRequest(btnID) {
		console.log(btnID);
		var strLength = btnID.length;
		var ID = btnID.substring(strLength-1, strLength);
		var status = btnID.substring(0,6);
		var friendUsername = $("#usernameRequest" + ID).text();
		if(status === "accept"){
			status = 1;
			}else {
				status = 4;
				}
		if ($.trim(friendUsername) !== '') {
			$.post('sendRequest.php', {friendUsername: friendUsername, status: status}, function(data){
				$("#usernameRequest" + ID).text(data);
					});
			}
		}

