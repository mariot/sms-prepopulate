jQuery(document).ready(function($){
  if ( !navigator.userAgent.match(/(iPad|iPhone|iPod)/g) ) return;

  jQuery('a[href^="sms:"]').attr('href', function() {
    return jQuery(this).attr('href').replace(/sms:(\+?([0-9]*))?\?/, 'sms:$1;');
  });
});
