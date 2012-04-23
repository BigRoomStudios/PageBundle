var PageNav = Class.create({
	
	initialize: function(config) {
			
		var $this = this;
		
		this.config = config;
		
		this.supports_history = ( window.history && history.pushState ? true : false ); 
		
		this.container = $(config.container);
		
		this.view_container = $(config.view_container);
		
		this.selected_page_id = this.container.find('li.selected a').first().data('page-id');
		
	
		//this.selected = 
		
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
		
		if(this.supports_history){
		
			var nav_id = 'nav_' + id;
			
			history.pushState({page_id: id}, "", href);
			
			this.selected_page_id = id;
							
			this.set_selected(nav_id);
							
			this.show_view(href);
			
		}else{
			
			window.location = href;
		}
	},
	
	get_next_page: function(){
		
		var nav_id = '#nav_' + this.selected_page_id;
		
		var $selected_nav = $(nav_id);

		var $next_nav = $(nav_id).next('li');
		
		if($next_nav.length > 0){
			
			var $a = $next_nav.find('a').first();
			
			var id = $a.data('page-id');
			
			var href = $a.attr('href');
			
			return {id: id, href: href};
		}
		
		return false;
	},
	
	go_next: function(){
		
		var page = this.get_next_page();
		
		if(page){
			
			this.go(page.id, page.href);	
		}
	},
	
	get_prev_page: function(){
		
		var nav_id = '#nav_' + this.selected_page_id;
		
		var $selected_nav = $(nav_id);

		var $prev_nav = $(nav_id).prev('li');
		
		if($prev_nav.length > 0){
		
			var $a = $prev_nav.find('a').first();
			
			var id = $a.data('page-id');
			
			var href = $a.attr('href');
			
			return {id: id, href: href};
		}
		
		return false;
	},
	
	go_prev: function(){
		
		var page = this.get_prev_page();
		
		if(page){
			
			this.go(page.id, page.href);	
		}
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
		        
		        document.title = json.page.title;
		        
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
		container:'#primary-nav',
		view_container:'#page'
	});
	
	var page_id = $('#container').data('page-id');
	
	if($j.page_nav.supports_history){
		
		//alert(window.location.href);
		
		history.replaceState({page_id: page_id}, "", window.location.href);	
	}
	
});
