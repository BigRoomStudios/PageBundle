var PageNav = Class.create({
	
	initialize: function(config) {
			
		var $this = this;
		
		this.config = config;
		
		this.supports_history = ( window.history && history.pushState ? true : false ); 
		
		this.view_container = $(config.view_container);
		
		if(this.supports_history){
			
			$('a').live('click', function (event) {
				
				//event.preventDefault();
				
				//var route = $(this).data('route');
				//var route_params = $(this).data('route-params');
				
				var page_id = $(this).data('page-id');
				
				if(page_id){
					
					var nav_id = "nav_" + page_id;
					
					if($(nav_id)){
				
						event.preventDefault();			
						
						$this.go(page_id, this.href);
						
						return false;
					}
				}
			});
			
			window.onpopstate = function(event) {  
				
				/*if(event.state.slide){
					
					$j.widgets['swiper'].goTo(event.state.slide);
				}*/
				
				if(event.state && event.state.page_id){
				
					var nav_id = 'nav_' + event.state.page_id;
					
					if($(nav_id)){
						
						$this.set_selected(nav_id);
						
						$this.show_view(document.location);
					}	
				}
			}; 
		}
	},
	
	go: function(id, href){
		
		var nav_id = 'nav_' + id;
		
		history.pushState({page_id: id}, "", href);
						
		this.set_selected(nav_id);
						
		this.show_view(href);
	},
	
	show_view: function(href) {
		
		console.log(href);
		
		var $this = this;
		
		//this.view_container.addClass('hide');

		$.getJSON(  
		    href,  
		    {ajax: 1},  
		    function(json) {
		    	
		        var result = json.rendered;
		        
		        $this.view_container.html(result);
		        $this.view_container.removeClass('hide'); 
		    }  
		);
		
	},
	
	set_selected: function(id) {
		
		$li = $('#'+id);
		
		$ul = $li.closest('ul');
		
		$ul.find('li').removeClass('selected');
		
		$li.addClass('selected');
	}
});


$(document).ready(function() {
	$j.page_nav = new PageNav({
		container:'nav',
		view_container:'#page'
	});
	
	var page_id = $('#container').data('page-id');
	
	if($j.page_nav.supports_history){
		
		//alert(window.location.href);
		
		history.replaceState({page_id: page_id}, "", window.location.href);	
	}
	
});
