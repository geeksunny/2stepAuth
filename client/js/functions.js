$(document).ready(function() {
	$("#username_form").submit(function(e) {
		e.preventDefault();

		username = $("#username_input").val();
		$("#username_div").slideUp("200",function() {
			if (username == '')
				$("#username_span").html("[blank]");
			else
				$("#username_span").html(username);
			$("#hash_div").slideDown("200");
			startProcess();
		})
	});

	$("#reset_link").click(function(e) {
		e.preventDefault();

		$("#username_input").val(username);
		$("#hash_div").slideUp("200",function() {
			killProcess();
			$("#username_div").slideDown("200");
		})
	});
});

var username;
var countdown = 30;
var interval;

function startProcess() {
	$("#countdown_clock").html(countdown);
	getHash();
	interval = setInterval("timer()",1000);
}

function killProcess() {
	clearInterval(interval);
}

function timer() {
	countdown -= 1;
	if (countdown == 0) {
		getHash();
		countdown = 30;
	}
	if (console) { console.log("timer: "+countdown); }
	$("#countdown_clock").html(pad(countdown, 2));
}

function getHash() {
	$.ajax({
		type: "POST",
		url: "get_hash.php",
		data: "user="+username,
		success: function(data) {
			if (console) {
				console.log("username: "+username);
				console.log("Response: "+data);
			}

			$("#hash_code").html(data);
		},
		error: function() {
			alert("An error has occurred...");
		}
	});
}

function pad(n, len) {
	s = n.toString();
	if (s.length < len) {
		s = ('0000000000' + s).slice(-len);
	}

	return s;
}