jQuery(document).ready(function($) {	
    $('*:first-child').addClass('first-child');
    $('*:last-child').addClass('last-child');
    $('*:nth-child(even)').addClass('even');
    $('*:nth-child(odd)').addClass('odd');
	
    var numwidgets = $('.header-widget-area section.widget').length;
    $('.header-widget-area').addClass('cols-'+numwidgets);
    var cols = 12/numwidgets;
    $('.header-widget-area section.widget').addClass('col-sm-'+cols);
    $('.header-widget-area section.widget').addClass('col-xs-12');
    
    var device = function(){
        if($( window ).width() > 769){
            console.log('desktop');
            //Do for laptop
            $('.section-content .section.first-child .slideshow').addClass('pull-left');
            $('#get-this-free-white-paper img,.brochure img').addClass('alignright');
        } else if($( window ).width() <= 769){
            console.log('tablet');
            //do for portrait tablet
            $('.section-content .section.first-child .slideshow').removeClass('pull-left');
            $('#get-this-free-white-paper img,.brochure img').removeClass('alignright');
            
            
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