jQuery(document).ready(function($) {
    var numwidgets = $('#homepage-widgets section.widget').length;
    $('#homepage-widgets').addClass('cols-'+numwidgets);
    var cols = 12/numwidgets;
    $('#homepage-widgets section.widget').addClass('col-sm-'+cols);
    $('#homepage-widgets section.widget').addClass('col-xs-12');
    var device = function(){
        if($( window ).width() > 769){
            console.log('desktop');
            //Do for laptop
            $('.section-content .section.first-child .slideshow').addClass('pull-left');
            $('.section-content .section.even img').addClass('alignright');
            $('.section-content .section.last-child img').addClass('alignleft');
        } else if($( window ).width() <= 769){
            console.log('tablet');
            //do for portrait tablet
            $('.section-content .section.first-child .slideshow').removeClass('pull-left');
            $('.section-content .section.even img').removeClass('alignright');
            $('.section-content .section.last-child img').removeClass('alignleft');
            
            
        } else if($( window ).width() <= 480){
            console.log('phone');
            //do for phone
        }
        
    };
    
    device();
    
    $( window ).resize(function() {
        device();
    });
});