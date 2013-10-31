jQuery(document).ready(function(){
  jQuery(".cockpit_settings").hide(); 
  jQuery(".btn_accordion").click(function(){  
    jQuery(".cockpit_settings").slideToggle(700, function() {
      jQuery('.btn_accordion').toggleClass('accordion_opened', jQuery(this).is(':visible'));
    });  
  });
});