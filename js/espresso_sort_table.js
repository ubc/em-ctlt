jQuery(document).ready(function() {
	jQuery( '#ctlt-jQuery-event-espresso-sort-table' ).tablesorter({
		// pass the headers argument and assign an object
		headers: {
			// assign the third column (we start counting with zero)
			3: {
				// disable it by setting the property sorter to false
				sorter: false
			}
		}
	});
});