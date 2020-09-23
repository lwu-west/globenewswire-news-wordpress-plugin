jQuery(document).ready(function( $ ) {
    const body = $("body");
	
    $( "#dialog-message" ).dialog({
		modal: true,
        position: { my: "left top", at: "left top-20", of: window },
        resizable: false,
        closeOnEscape: false,
        width: body.width(),
		height: body.height() * 1.3,
		backgroundColor: body.css( "background-color" ),
		dialogClass: "gnw_wp_no_close gnw_wp_custom",

    });
	


});
