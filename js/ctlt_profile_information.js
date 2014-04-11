jQuery.getJSON( ctlt_profile_infomration_js_url, function( data ) {    
    jQuery("#event_espresso_faculty").on('change', function(){
        jQuery("#event_espresso_department").empty().append('<option value=""></option>');
        
        jQuery.each( data, function( key, value ) {
            jQuery.each( value, function( key2, value2) {
                if(key2 == jQuery("#event_espresso_faculty").children("option").filter(":selected").text()) {
                    jQuery.each( value2, function( key3, value3) {
                        if( value3.name.substr(0, 3).toLowerCase() != "(i)" )
                        jQuery("#event_espresso_department").append('<option value="'+value3.name+'">'+value3.name+'</option>');
                    });
                }
            });
        });
    });
    
});