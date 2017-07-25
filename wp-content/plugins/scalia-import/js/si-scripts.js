(function($) {
	$(function() {

		$('.scalia-import-output').each(function() {
			var $importOutput = $(this);
			var $progressBar = $('<div class="progress-bar" />').appendTo($importOutput);
			var $progressBarLine = $('<div class="progress-bar-line" />').appendTo($progressBar);
			var $importStatus = $('<div class="import-status" />').appendTo($importOutput);
			var $importMesages = $('<div class="import-messages" />').appendTo($importOutput);
			var $importButton = $('<button>Import Main Demo Content</button>').appendTo($importOutput);

			window.onbeforeunload = function(e) {
				if($importOutput.data('proccess'))
				return 1;
			}

			var files_list = [];

			var import_file = function(num) {
				if(files_list[num] != undefined){
					$progressBarLine.css({
						width: 100*num/files_list.length + '%'
					});
					$progressBarLine.text(Math.round(100*num/files_list.length) + '%');
					//$importStatus.html('');
					//var $message = $('<p class="msg-loading">Importing file '+files_list[num]+' ('+(num+1)+'/'+files_list.length+')... </p>').appendTo($importStatus);
					$.ajax({
						url: ajaxurl,
						data: {action: 'scalia_import_file', filename: files_list[num]},
						method: 'POST',
						timeout: 50000
					}).done(function(msg) {
						msg = jQuery.parseJSON(msg);
						/*$message.removeClass('msg-loading');
						$('<span>'+msg.message+'</span>').appendTo($message);*/
						import_file(num+1);
					}).fail(function() {
						/*$message.removeClass('msg-loading');
						$('<span>Error.</span>').appendTo($message);
						$('<p>Importing file '+files_list[num]+' failed.</p>').appendTo($importMesages);*/
						import_file(num+1);
					});
				} else {
					$progressBarLine.css({
						width: '100%'
					});
					$progressBarLine.text('100%');
					$importStatus.html('');
					$('<p>All done. Have fun! ;)</p>').appendTo($importStatus);
				}
			}

			$importButton.click(function(e) {
				e.preventDefault();
				$importOutput.data('proccess', true)
				$(this).remove();
				$.ajax({
					url: ajaxurl,
					data: { action: 'scalia_import_files_list' },
					method: 'POST',
					timeout: 30000
				}).done(function(msg) {
					msg = jQuery.parseJSON(msg);
					$importStatus.html('<p>'+msg.message+'</p>');
					if(msg.status) {
						files_list = msg.files_list;
						import_file(0);
					}
				}).fail(function() {
					$('<p>Ajax error. Try again...</p>').appendTo($importMesages);
				});
			});

		});


	});
})(jQuery);