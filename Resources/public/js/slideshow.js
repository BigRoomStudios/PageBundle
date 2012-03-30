
var supports_history = ( window.history && history.pushState ? true : false ); 

$('#slide-next').live('click', function(event){
	
	event.preventDefault();
	
	$j.widgets['swiper'].next();
	
	//updateSlideHash();
	
});

$('.slide-next').live('click', function(event){
	
	event.preventDefault();
	
	$j.widgets['swiper'].next();
	
	//updateSlideHash();
});

$('#slide-back').live('click', function(event){
	
	event.preventDefault();
	
	$j.widgets['swiper'].prev();
	
	//updateSlideHash();
});


$('#slide-indicators a').live('click', function(event){
	
	event.preventDefault();
	
	var key = $(this).html();
	
	$j.widgets['swiper'].goTo(key-1);
	
	updateSlideIndicators();
	
	//updateSlideHash();
});

function updateSlideIndicators(){
	
	var index = $j.widgets['swiper'].index;
	
	var $indicators = $('#slide-indicators a');
	
	$indicators.removeClass('selected');
	
	$($indicators[index]).addClass('selected');
}


function updateSlideHash(){
	
	var index = $j.widgets['swiper'].index;
	
	
	
	//alert(index);
	
	/*if(supports_history){
				
		history.pushState({slide: index}, "", '#'+(index+1));
	}*/
}
