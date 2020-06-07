// Message for user
// Message show
function showHint(html, autohide) {
	$("#globalHint > span").html (html);
	$("#globalHint").fadeIn();
	if (autohide || typeof autohide === "undefined") window.setTimeout(function() { $("#globalHint").fadeOut (); }, 2000);
	$(".close-alert").click(function() {
		$("#globalHint").attr('style','display: none;');
	});
}
// Error show
function showError(html, autohide) {
	$("#globalError > span").html (html);
  var a = $("#globalError > span").text();
  if (a.length === 0) {
    //window.location = "login.php?returl="+/\/[^\/]+$/g.exec (document.URL);
    var b = window.location.href;
    window.location = b;
  }
	$("#globalError").fadeIn();
	if (autohide || typeof autohide === "undefined") window.setTimeout(function() { $("#globalError").fadeOut (); }, 4000);
	$(".close-alert").click(function() {
		$("#globalError").attr('style','display: none;');
	});
}
// STOP Message for user

// SHORT NAMES
// Lastname Firstname (without third name)
  function twoNames2(fullName) {
    var shortName;
    fullName ? fullName = fullName.split(' ') : '';
    if (fullName) shortName = fullName[0] + ' ' + fullName[1];
    return shortName;
  }
// Lastname F.T.
	function shortNames3(fullName) {
		var shortName;
		fullName ? fullName = fullName.split(' ') : '';
		if (fullName) {
			shortName = fullName[0] + ' ' + fullName[1][0] + '. ';
		}
		if (fullName[2]) {
			shortName = shortName + fullName[2][0] + '. ';
		}
		return shortName;
	}
// STOP SHORT NAMES

// DATES
// get Name Day Of Week By Day Number true = short name / false = full name
	function getNameDayOfWeekByDayNumber() {
		var day = new Date(date);
	  var dayNumber = day.getDay();
	  var weekday = new Array(7);
	  if (short) {
	    weekday[0] = "Вс";
	    weekday[1] = "Пн";
	    weekday[2] = "Вт";
	    weekday[3] = "Ср";
	    weekday[4] = "Чт";
	    weekday[5] = "Пт";
	    weekday[6] = "Сб";
	  } else {
	    weekday[0] = "Воскресенье";
	    weekday[1] = "Понедельник";
	    weekday[2] = "Вторник";
	    weekday[3] = "Среда";
	    weekday[4] = "Четверг";
	    weekday[5] = "Пятница";
	    weekday[6] = "Суббота";
	  }
	  return weekday[dayNumber];
	}
// STOP DATES
