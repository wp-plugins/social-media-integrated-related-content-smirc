/**
* JQUERY MUST BE INSTALLED AND FUNCTIONAL!
*/

$(document).ready(function() {

    $('div.smirc_wrapper h2').toggle(
	function(){
	    $(this).removeClass('collapsed');
	    $(this).addClass('expanded');
	    $(this).next('ul').show('fast');
	},
	function(){
	    $(this).removeClass('expanded');
	    $(this).addClass('collapsed');
	    $(this).next('ul').hide('fast');
	}
    );

});