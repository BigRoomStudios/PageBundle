var PageNav = Class.create({
	
	initialize: function(config) {
			
		var $this = this;
		
		this.config = config;
		
		this.supports_history = ( window.history && history.pushState ? true : false ); 
		
		this.container = $(config.container);
		
		this.view_container = $(config.view_container);
		
		this.selected_page_id = this.container.find('li.selected a').first().data('page-id');
		
		if(config.nav_id_stub){
			
			this.nav_id_stub = config.nav_id_stub;
			
		}else{
			
			this.nav_id_stub = 'nav_';
		}
		//this.selected = 
		
		if(this.supports_history){
			
			var click_function = function (event) {
				
				event.stopPropagation();
				
				//event.preventDefault();
				
				//var route = $(this).data('route');
				//var route_params = $(this).data('route-params');
				
				var page_id = $(this).data('page-id');
				var replace = $(this).data('replace-view');
				var page_title = $(this).html();
				
				var data_title = $(this).data('title');
				
				if(data_title){
					
					page_title = data_title;
				}
				
				if(page_id){
					
					var nav_id = this.nav_id_stub + page_id;
					
					if($(nav_id)){
				
						event.preventDefault();			
						
						$this.go(page_id, this.href, {replace: replace, title:page_title});
						
						return false;
					}
				}
				
				if( Modernizr.touch ){
					
					event.preventDefault();
					
					//alert($(this).href());
					
					return false;
				}
				
				return false;
				
			}
			
			
			if( Modernizr.touch ){
				
				$('a').live('touchstart', click_function);
				
				$('a').live('click', click_function);
				
				
			
				//$('a').tappable(click_function);
			
			}else{
				
				$('a').live('click', click_function);
			}
			
			window.onpopstate = function(event) {  
				
				/*if(event.state.slide){
					
					$j.widgets['swiper'].goTo(event.state.slide);
				}*/
				
				if(event.state && event.state.page_id){
				
					var nav_id = this.nav_id_stub + event.state.page_id;
					
					if($(nav_id)){
						
						$this.set_selected(nav_id);
						
						if(event.state.title){
				
							window.document.title = event.state.title;
						}
						
						$this.show_view(event.state.page_id, document.location, event.state.replace);
					}	
				}
			}; 
		}
	},
	
	go: function(id, href, vars){
		
		if(this.supports_history){
		
			var nav_id = this.nav_id_stub + id;
			
			if(!vars){
				vars = {}
			}
			
			vars.page_id = id;
			
			history.pushState(vars, "", href);
			
			if(vars.title){
				
				window.document.title = vars.title;
			}
			
			this.selected_page_id = id;
							
			this.set_selected(nav_id);
							
			this.show_view(id, href, vars.replace);
			
		}else{
			
			window.location = href;
		}
	},
	
	get_next_page: function(){
		
		var nav_id = '#' + this.nav_id_stub + this.selected_page_id;
		
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
		
		var nav_id = '#' + this.nav_id_stub + this.selected_page_id;
		
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
	
	show_view: function(id, href, replace) {
		
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
		
		//alert(id);
		
		$li = $('#'+id);
		
		$ul = $li.closest('ul');
		
		$ul.find('li').removeClass('current');
		
		$li.addClass('current');
	}
});
