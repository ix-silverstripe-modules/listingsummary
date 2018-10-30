;jQuery(function($) {
	
	$(document).ready(function() {
		
		var $select = $('select.sort-by');
		
		$('select.sort-by').change(function() {
			var url = $(this).val();
			window.location.href = url;
		});

	});
	
}); 