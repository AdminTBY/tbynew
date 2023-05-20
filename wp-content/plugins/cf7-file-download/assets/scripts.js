
jQuery(function($) {

jQuery('.repeatable-field-add').click(function(e) {
    e.preventDefault();
		//alert();
        var len=$('.repeatable-fields-list  tr').length;
    var theField = jQuery('#repeater_template')
        .find('tr').clone(true);
        console.log(theField);
    var theLocation = jQuery('div.repeatable-wrap')
        .find('.repeatable-fields-list');
   
    jQuery('input,select', theField).val('').attr('name', function(index, name) {
        return $(this).attr('id').replace('x', parseInt(len) + 1)
    }).removeAttr('id');
    
    theField.appendTo(theLocation, jQuery('div.repeatable-wrap'));
    var fieldsCount = jQuery('.repeatable-field-remove').length;
    if( fieldsCount > 1 ) {
        //jQuery('.repeatable-field-remove').css('display','inline');
    }
    return false;
});

jQuery('.repeatable-field-remove').click(function(){
    jQuery(this).parent().parent().remove();
    var fieldsCount = jQuery('.repeatable-field-remove').length;
    if( fieldsCount == 1 ) {
        jQuery('.repeatable-field-remove').css('display','none');
    }
    return false;
});

})