var famille = false;

$(document).ready(function() {

	// Init famille :
	famille = new Famille({
		dom: {
			canvas : 'canvas',
			form : '#formEleve',
			parentCanvas : '#parentCanvas',
			directParentCanvas : '#directParentCanvas',
		},
		url: {
			str : 'like.php?str={0}',
			graph : 'famille.php?ide={0}&remonter={1}',
		}
	});

	// Materialize Complement
	$(document).ready(function() {
		// Update and init range value
		$('input[type="range"]').each(rangeValue).bind('change', rangeValue).bind('input', rangeValue);
	});
});


function rangeValue() {
	$(this).parent().find('label .value').html($(this).val());
}