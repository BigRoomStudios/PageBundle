
var supports_history = ( window.history && history.pushState ? true : false ); 

$('#slide-next').live('click', slideNext);
$('#slide-next').live('touchstart', slideNext);
$('.slide-next').live('click', slideNext);
$('.slide-next').live('touchstart', slideNext);
$('#slide-back').live('click', slideBack);
$('#slide-back').live('touchstart', slideBack);


$('#slide-indicators a').live('click', function(event){
	
	event.preventDefault();
	
	var key = $(this).html();
	
	$j.widgets['swiper'].goTo(key-1);
	
	//updateSlideIndicators();
	
	//updateSlideHash();
});

function slideNext(event){
	
	event.preventDefault();
	
	var $target = $(event.target);
	
	if($target.hasClass('disabled')){
		
		alert('here');
		
		return false;
	}
	
	var swiper = $j.widgets['swiper'];
	
	//console.log(swiper.index + ' ' + swiper.length);
	
	if (swiper.index < swiper.length - 1){
		
		swiper.next();
		
	}else{
		
		$j.page_nav.go_next();
	}
}

function slideBack(event){
	
	event.preventDefault();
	
	var $target = $(event.target);
	
	if($target.hasClass('disabled')){
		
		alert('here');
		
		return false;
	}

	var swiper = $j.widgets['swiper'];
	
	//console.log(swiper.index + ' ' + swiper.length);
	
	if (swiper.index > 0){
		
		swiper.prev();
		
	}else{
		
		$j.page_nav.go_prev();
	}
}


function updateSlideIndicators(){
	
	var index = 0;
	
	var swiper = $j.widgets['swiper'];
	
	if(swiper){
		
		index = swiper.index;
	}
	
	var $indicators = $('#slide-indicators a');
	
	$indicators.removeClass('selected');
	
	$($indicators[index]).addClass('selected');
	
	$back_link = $('#slide-back a');
	
	$next_link = $('#slide-next a');
	
	if(swiper && (swiper.index == 0) && $j.page_nav && (!$j.page_nav.get_prev_page())){
		
		$back_link.addClass('disabled');
		
	}else{
			
		$back_link.removeClass('disabled');
	}
	
	if(swiper && (swiper.index == swiper.length - 1) && $j.page_nav && (!$j.page_nav.get_next_page())){
		
		$next_link.addClass('disabled');
		
	}else{
			
		$next_link.removeClass('disabled');
	}
}


function updateSlideHash(){
	
	//var index = $j.widgets['swiper'].index;
	
	//alert(index);
	
	/*if(supports_history){
				
		history.pushState({slide: index}, "", '#'+(index+1));
	}*/
}
