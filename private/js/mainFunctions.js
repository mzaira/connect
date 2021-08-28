//FOR SEARCH BAR
$(document).click(function (e) {

	if (e.target.class != "searchResult" && e.target.id != "search_text_input") {

		$(".searchResult").html("");
		$('.search_results_footer').html("");
		$('.search_results_footer').toggleClass("searchResultFooter");
		$('.search_results_footer').toggleClass("search_results_footer");
	}

	if (e.target.className != "dropdown_data_window") {

		$(".dropdown_data_window").html("");
		$(".dropdown_data_window").css({ "padding": "0px", "height": "0px" });
	}


});


//FOR MESSAGE, NEW MESSAGE
function getUsers(value, user) {
	$.post("../private/includes/handlers/ajax_friend_search.php", { query: value, userLoggedIn: user }, function (data) {
		$(".results").html(data);
	});
}

//FOR MODAL OF NOTIFICATION AND MESSAGES
function getDropdownData(user, type) {

	if ($(".dropdown_data_window").css("height") == "0px") {

		var pageName;

		if (type == 'notification') {
			pageName = "ajax_load_notifications.php";
			$("span").remove("#unread_notification");
		}
		else if (type == 'message') {
			pageName = "ajax_load_messages.php";
			$("span").remove("#unread_message");
		}

		var ajaxreq = $.ajax({
			url: "../private/includes/handlers/" + pageName,
			type: "POST",
			data: "page=1&userLoggedIn=" + user,
			cache: false,

			success: function (response) {
				$(".dropdown_data_window").html(response);
				$(".dropdown_data_window").css({ "padding": "0px", "height": "280px", "border": "1px solid #DADADA" });
				$("#dropdown_data_type").val(type);
			}

		});

	}
	else {
		$(".dropdown_data_window").html("");
		$(".dropdown_data_window").css({ "padding": "0px", "height": "0px", "border": "none" });
	}

}

//FOR SEARCH BAR
function getLiveSearchUsers(value, user) {

	$.post("../private/includes/handlers/ajax_search.php", { query: value, userLoggedIn: user }, function (data) {

		if ($(".searchResultFooter")[0]) {
			$(".searchResultFooter").toggleClass("search_results_footer");
			$(".searchResultFooter").toggleClass("searchResultFooter");
		}

		$('.searchResult').html(data);
		$('.search_results_footer').html("<a href='search.php?q=" + value + "'>See All Results</a>");

		if (data == "") {
			$('.search_results_footer').html("");
			$('.search_results_footer').toggleClass("searchResultFooter");
			$('.search_results_footer').toggleClass("search_results_footer");
		}

	});

}

function forgotPassword(){
    document.getElementById('forgot-password').style.display = 'flex';
}
function closeforgot(){
    document.getElementById('forgot-password').style.display = 'none';
}
function openLogout(){
    document.getElementById('logoutModal').style.display = 'flex';
}
function closeLogout(){
    document.getElementById('logoutModal').style.display = 'none';
}
function openCreate(){
    document.getElementById('createModal').style.display = 'flex';
}
function closeCreate(){
    document.getElementById('createModal').style.display = 'none';
}