
<!-- setup details pane template -->
<div class="card border-info" id="details-pane" style="display: none;">
	<div class="card-body">
	    <p class="text-muted published_date">Published: 12/2017</p>
		<h3 class="title"></h3>
		<!--
		<div class="big-img"><a href=""><img src=""></a></div>
		-->
		<div class="meta">
			<div class="row">
				<div class="col-md-8">
					<h3 class="title"></h3>
					<span class="subtitle"></span><br />
					<span class="details-author pull-left"></span><br />
				</div>
				<div class="col-md-4">
					<span class="details-price"></span>
				</div>
			</div>
		</div>
	</div>
	
	<div class="card-footer clearfix">
		<span class="details-url">
			<span class="details-category pull-left"></span>
			<a href="#" class="btn btn-info btn-xs pull-right">Details</a>
		</span>
		
	</div>
</div>
	
<script type="text/javascript">
	$(function(){
		
		// grid
		$('.details-item').on('mouseover', function(e){
	  	
	  	var layout = $(e.currentTarget).hasClass('list-item') ? 'list' : 'grid';
	  	
	  	
	    var dpane      = $('#details-pane');
	    var dpanetitle = $('#details-pane .title');
	    var dpanedesc  = $('#details-pane .desc');
	   
	    var newimg     = $(this).attr('data-fullsize');
	    
	    var position = $(this).offset();
	    var imgwidth = $(this).width() + 10;
	    var ycoord   = position.top-150;
	    
	    if(layout === 'grid'){
		    if(position.left / $(window).width() >= 0.5) {
		      var xcoord = position.left - 505;
		    } else {
		        var xcoord = position.left + 295;
		      //var xcoord = position.left + imgwidth;
		    }
	    } else {
	    	var xcoord = position.left - 510;
	    }
	    
	    $('.big-img a').attr('href',$(this).attr('data-detailsUrl'));
	    $('.big-img a img').attr('src',newimg);
	    
	    
	    $('.details-url a').attr('href', $(this).attr('data-detailsUrl'));
	    $('#details-pane .title').html($(this).attr('data-title'));
	    $('#details-pane .subtitle').html($(this).attr('data-subtitle'));
	    $('#details-pane .details-price').html($(this).attr('data-price'));
	    $('#details-pane .details-author').html($(this).attr('data-author'));
	    $('#details-pane .details-category').html($(this).attr('data-category'));
	    $('#details-pane').css({ 'left': xcoord, 'top': ycoord, 'display': 'block'});
	    
	  }).on('mouseout', function(e){
	    $('#details-pane').css('display','none');
	  });
	
	  
	  // when hovering the details pane keep displayed, otherwise hide
	  $('#details-pane').on('mouseover', function(e){
		$(this).css('display','block');
		//$(this).fadeIn(400);
	  });
	  $('#details-pane').on('mouseout', function(e){
	    //this is the original element the event handler was assigned to
	    var e = e.toElement || e.relatedTarget;
	    if (e.parentNode == this || e.parentNode.parentNode == this || e.parentNode.parentNode.parentNode == this || e == this || e.nodeName == 'IMG') {
	      return;
	    }
	    
	    $(this).css('display','none');
	
	  });
	});
</script>