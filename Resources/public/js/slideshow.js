
$('#slide-next').live('click', function(event){
	
	event.preventDefault();
	
	$j.widgets['swiper'].next();
	
});

$('.slide-next').live('click', function(event){
	
	event.preventDefault();
	
	$j.widgets['swiper'].next();
	
});

$('#slide-back').live('click', function(event){
	
	event.preventDefault();
	
	$j.widgets['swiper'].prev();
	
});
	