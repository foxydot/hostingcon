var showPage =  function(){
    $('body').fadeIn();
    $('form#phRecordForm-requestInfo :input').removeAttr('disabled');
};

var displayRecord = function(data) {
    var str = '';
    for(p in data) {
        str += p+': '+data[p]+'\n';
    }
    $('#request-form').fadeOut(function(){
        $('#thank-you').append('<pre>'+str+'</pre>')
        $('#thank-you').fadeIn();
    });
}

jQuery(document).ready(function($) {
    showPage();
});