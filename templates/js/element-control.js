// Global Javascript Variables

// Tabbed navigation
$('#myTab a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
})

// Pagination of all Competences
$('#pagination li').click(function (e) {
	e.preventDefault();
	
	var CurrentPageID = $(this).attr('id');
	// For example CurrentPageID = disciplinaryknowledge5
	// For example key = disciplinaryknowledge
	// For example LastPageArray[key] = 1
	
	// We have to remember the last clicked pagination, per page. So let's check this for the whole array.
	for (var key in LastPageArray) {
		if (LastPageArray.hasOwnProperty(key)) {
		
			// Check which Page was clicked, by checking if the clicked ID matches the competence area in our lastpage array.
			if(CurrentPageID.indexOf(key) !== -1) {
				var CurrentPage = key;
				var LastID = LastPageArray[CurrentPage];
				var LastPageID = CurrentPage + LastPageArray[CurrentPage];
				var MaxID = MaxPageArray[CurrentPage];
				var index = key.length;
				var CurrentID = '';
				// Get the CurrentID, needs to have a for loop in case we have an ID higher than 9 (two numbers)
				for ( var i = index; i < CurrentPageID.length; i++ )
					var CurrentID = CurrentID + CurrentPageID.charAt(i);
				
				
				// This case statement deals with all possible states the paginator could be in
				switch (CurrentID) {
					case "P":
						if(String(LastID) != "1") {
							CurrentID = Number(LastID) - 1;
						} else {
							CurrentID = 1;
						}
						if(String(CurrentID) == String(1)) {
							$("#" + CurrentPage + "P").addClass('disabled');
						}
						$("#" + CurrentPage + "N").removeClass('disabled');
						break;
					case "N":
						if(String(LastID) != String(MaxID)) {
							CurrentID = Number(LastID) + 1;
						} else {
							CurrentID = MaxID;
						}
						if(String(CurrentID) == String(MaxID)) {
							$("#" + CurrentPage + "N").addClass('disabled');
						}
						$("#" + CurrentPage + "P").removeClass('disabled');
						break;
					case "1":
						$("#" + CurrentPage + "P").addClass('disabled');
						$("#" + CurrentPage + "N").removeClass('disabled');
						break;
					case String(MaxID):
						$("#" + CurrentPage + "N").addClass('disabled');
						$("#" + CurrentPage + "P").removeClass('disabled');
						break;
					default:
						$("#" + CurrentPage + "P").removeClass('disabled');
						$("#" + CurrentPage + "N").removeClass('disabled');
						break;
				} 	
				CurrentPageID = CurrentPage + CurrentID;
				// New last clicked page:
				LastPageArray[CurrentPage] = CurrentID;
				break;
			}
		}
	}
	
	// Simple Hide or show. TODO: We might want to upgrade this to make this animated.
	if((CurrentID != LastID)) {
		// First we hide the last content and show the current content
		$("." + CurrentPageID).removeClass('hidden');
		$("." + LastPageID).addClass('hidden');
		// Then we adjust the clicked and unclicked pagination
		$("#" + CurrentPageID).addClass('active');
		$("#" + LastPageID).removeClass('active');
	}
	
	
})
