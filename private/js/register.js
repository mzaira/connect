$(document).ready(function() {

	//CLICK BUTTON: to hide signin and show signup form
	$("#signupBtn").click(function() {
		$("#signinContainer").slideUp("slow", function(){
			$("#signupContainer").slideDown("slow");
		});
	});

	//CLICK BUTTON: to hide signup and show signin form
	$("#signinBtn").click(function() {
		$("#signupContainer").slideUp("slow", function(){
			$("#signinContainer").slideDown("slow");
		});
	});

});

//CLICK BUTTON: to display the forgot password modal
function forgotPassword(){
    document.getElementById('forgot-password').style.display = 'flex';
}
//CLICK BUTTON: to hide the forgot password modal
function closeforgot(){
    document.getElementById('forgot-password').style.display = 'none';
}
