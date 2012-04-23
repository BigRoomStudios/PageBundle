/*
 * ContentPanel v1 | Sam Mateosian
 * Admin Content Management Panel
*/
var ContentPanel = Class.create({

	initialize: function(config) {
			
		var $this = this;
		
		// set UI configs
		this.config = config;
		
		this.id = config.id;
		this.widget_name = config.name;
		this.widget_route = config.route;
		this.action = config.action;
		this.page_id = config.page_id;
		
		this.container = $('#' + this.id);
		
		this.add_btn = this.container.find('.add-button');
		this.cancel_add_btn = this.container.find('.content-form-widget .btn-cancel');
		this.save_add_btn = this.container.find('.content-form-widget .btn-save');
		
		this.content_list = this.container.find('.content-list-widget').first();
		this.content_form = this.container.find('.content-form-widget').first();
		var form_widget_id = this.content_form.attr('id') + '_form';
		this.content_form_widget = $j.widgets[form_widget_id];
		this.content_actions = this.container.find('.content-list-actions');
		
		
		this.add_btn.click(function(event){
			
			$this.addContent(event);
		});
		
		this.cancel_add_btn.unbind('click');
		this.cancel_add_btn.click(function(event){
			
			$this.cancelAdd(event);
		});
		
		this.save_add_btn.click(function(event){
			
			$this.saveContent(event);
		});
		
		$('#' + this.id + ' .content-list .edit-button').live('click',function(event){
			
			$this.editContent(this);
		});
		
		$('#' + this.id + ' .content-list .delete-button').live('click',function(event){
			
			$this.deleteContent(this);
		});
		
		this.list = this.container.find('ul.sortable');
		
		this.list.sortable({ 
			axis: 'y',
			update: function(event, ui){
				
				$this.onSort(event, ui);
			}
		});
		
	},
	
	onSort: function(event, ui){
		
		var content = this.list.sortable('serialize');
		
		var data = {
			page_id: this.page_id,
			content: content
		};
		
		var action = this.action + '/reorder';
		
		$.ajax({
			type: 'POST',
			url: action,
			dataType: 'json',
			data: content,
			success: function(result){
				
				$j.msg({
				    type:'success',
				    content:"<p>Your changes have been saved.</p>" // should come from server
				});
			}
		});
	},
	
	addContent: function(event){
		
		this.content_form.removeClass('hidden');
		
		this.content_actions.hide();
		
		this.content_form_widget.entity_id = undefined;
		
		this.content_form.find('.widget-header h2').html('Add Content:');
		
		this.content_form.find('input[type=text]').attr('value', '');
		
		this.content_form.find('textarea').attr('value', '');
		
	},
	
	cancelAdd: function(event){
		
		event.preventDefault();
		
		this.content_form.addClass('hidden');
		
		this.content_actions.show();
	},
	
	saveContent: function(event){
		
		event.preventDefault();
		
		this.content_form.addClass('hidden');
		
		this.content_actions.show();
		
		this.refreshData();
	},
	
	editContent: function(item){
		
		this.content_form.removeClass('hidden');
		
		this.content_actions.hide();
		
		this.content_form.find('.widget-header h2').html('Edit Content:');
		
		var $li = $(item).closest('li');
		
		var content_id = $li.data('id');
		
		//$li.prepend(this.content_form);
		
		//alert(content_id);
		
		this.content_form_widget.entity_id = content_id;
		
		var head = $li.find('h2').html();
		var body = $li.find('span.body').html();
		var template = $li.find('span.template').html();
		
		this.content_form.find('#form_header').attr('value', head);
		this.content_form.find('#form_body').attr('value', body);
		this.content_form.find('#form_template').attr('value', template);
	},
	
	deleteContent: function(item){
		
		var $this = this;
		
		if(confirm('Are you sure you want to delete this item?')){
			
			this.content_form.addClass('hidden');
		
			this.content_actions.show();
			
			var $li = $(item).closest('li');
			
			var content_id = $li.data('id');
			
			var action = this.action + '/delete';
			
			$.getJSON(  
				action,  
				{ajax: 1, id: content_id}, 
				function(data) {
					
					if(data.success){
						
						$li.slideUp('fast');
					}
				}  
			);
		}
	},
	
	refreshData: function(){
		
		var $this = this;
		
		var action = this.action + '/content';
		
		$.getJSON(  
			action,  
			{ajax: 1, page_id: this.page_id}, 
			function(data) {
				
				if(data.count > 0){
					
					$this.content_list.removeClass('hidden');
				
				}else{
					
					$this.content_list.addClass('hidden');
				}
				
				if(data.rendered){
					
					$content_list = $this.container.find('.content-list');
					
					$content_list.html(data.rendered);
					
					$this.list = this.container.find('ul.sortable');
		
					$this.list.sortable({ 
						axis: 'y',
						update: function(event, ui){
							
							$this.onSort(event, ui);
						}
					});
				}
			}  
		);
	}
});