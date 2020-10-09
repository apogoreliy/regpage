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

// ФИО SHORT NAMES
// Lastname Firstname (without Middle name)
  function fullNameToNoMiddleName(fullName) {
    var shortName;
    fullName ? fullName = fullName.split(' ') : '';
    if (fullName) shortName = fullName[0] + ' ' + fullName[1];
    return shortName;
  }
// Lastname F.M. OR Lastname F.
	function fullNameToShortFirstMiddleNames(fullName, nameOnly) {
		var shortName;
		fullName ? fullName = fullName.split(' ') : '';
		if (fullName) {
			shortName = fullName[0] + ' ' + fullName[1][0] + '. ';
		}
		if (fullName[2] && !nameOnly && fullName[2] !== '-') {
			shortName = shortName + fullName[2][0] + '. ';
		}
		return shortName;
	}
// STOP SHORT NAMES

// DATES
// get (today or from date parameter) Name Day Of Week By Day Number true = short name / false = full name
	function getNameDayOfWeekByDayNumber(date, short) {
		var day;
		if (date) {
			day = new Date(date);
		} else {
			day = new Date();
		}

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
	// date convert mmyyyy to yyyymmdd & yyyymmdd to mmyyyy
	function dateStrToddmmyyyyToyyyymmdd(date, toRus, separator) {
		var yyyy, mm, dd;

		if (!date) {
			console.log('function should receive the next parameter: DATE');
			return
		}

		if (toRus) {
			separator ? '' : separator = '.';
			yyyy = date.slice(0,4),
			mm = date.slice(5,7),
			dd = date.slice(8,10);
			date = dd + separator + mm + separator + yyyy;
		} else if (!toRus || toRus == 0){
			separator ? '' : separator = '-';
			yyyy = date.slice(6,10),
			mm = date.slice(3,5),
			dd = date.slice(0,2);
			date = yyyy + '-' + mm + '-' + dd;
		}
		return date
	}

// STOP DATES
