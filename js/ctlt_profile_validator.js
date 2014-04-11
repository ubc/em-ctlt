jQuery("#registration_form").submit( function() {
var x = jQuery("#event_espresso_organization").val();
    if ( jQuery("#event_espresso_organization").val() == "" )
    {
        alert("Organization name must be filled out");
        return false;
    }
    if ( jQuery("#event_espresso_faculty").val() == "" )
    {
        alert("Faculty name must be filled out");
        return false;
    }
    if ( jQuery("#event_espresso_department").val() == "" )
    {
        alert("Department name must be filled out");
        return false;
    }
});