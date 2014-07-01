<div id="messages-header" class="page-header">
    <h1>日志</h1>
</div> <!-- /add-users-header -->
<div id="messages-section" class="section">
	<div id="error-messages" class="alert alert-error">
		<?php 
			$error_log_file = APPPATH.'logs/error.log';
			if ( file_exists($error_log_file) ) {
				require_once ($error_log_file);
				unlink($error_log_file);
			}
		?>
	</div>

	<div id="success-messages" class="alert alert-success">
		<?php 
			$success_log_file = APPPATH.'logs/success.log';
			if ( file_exists($success_log_file) ) {
				require_once ($success_log_file);
				unlink($success_log_file);
			}
		?>
	</div>
</div>

<script type="text/javascript">
	(function($){
		$.isBlank = function(obj){
	    	return(!obj || $.trim(obj) === "");
		};
	})(jQuery);
</script>
<script type="text/javascript">
	$(document).ready(function(){
		var number_of_messages = 2;

		if ( $.isBlank($('#error-messages').html()) ) {
			$('#error-messages').hide();
			-- number_of_messages;
		}
		if ( $.isBlank($('#success-messages').html()) ) {
			$('#success-messages').hide();
			-- number_of_messages;
		}

		if ( number_of_messages == 0 ) {
			$('#messages-section').html('暂无可用日志.');
		}
	});
</script>