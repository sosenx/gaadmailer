(function($, w){
	
	var GBOX_SLUG = function( args ){
		
		this.args = args;
		
		
		this.set = function(){
			return this;
		}	
				
		this.init = function( ){			
			this.$el = $( '#' + this.args.model.instance_id );
			
			this.set();
			return this;
		}
		
		return this;
	}

	
	
	w.GBOX_SLUG = GBOX_SLUG1 ;
	
})(jQuery, window);
