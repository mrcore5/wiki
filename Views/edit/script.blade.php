@section('script')
<!--<script src="{{ asset('js/bootbox.min.js') }}"></script>-->
<script src="{{ asset('ace-editor/ace.js') }}"></script>
<script src="{{ asset('ace-editor/ext-language_tools.js') }}"></script>
{{-- <script src="{{ asset('typo/typo.js') }}"></script> --}}
{{-- <script src="{{ asset('typo/spellcheck_ace.js') }}"></script> --}}
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>
<!--<script src="{{ asset('js/jquery.chosen.min.js') }}"></script>-->
<script>
$(function() {

	// Variables
	var aceTheme = 'xcode'; //monokai xcode
	var aceMode = 'text'; //php html text plain_text...
	var autosaveDelay = 3; // seconds
	
	var postRoute = "{{ URL::route('permalink', array('id' => $post->id)) }}"; // redirect here on save/view

	@if ($post->format->constant == 'php' || $post->format->constant == 'phpw')
		aceTheme = 'monokai';
		aceMode = 'php';
	@elseif ($post->format->constant == 'html' || $post->format->constant == 'htmlw')
		aceTheme = 'monokai';
		aceMode = 'html';
	@endif


	// Ace Editor
	require("ace/lib/fixoldbrowsers");
	require("ace/ext/language_tools");
	//require("ace/ext/spellcheck");
	require("ace/ext/keybinding_menu");

	var config = require("ace/config");
	config.init();

	var editor = ace.edit("editor");
	editor.focus();

	// Editor Settings
	editor.setTheme("ace/theme/" + aceTheme);
	editor.setOptions({
		fontSize: "14px"
	});	
	editor.getSession().setMode("ace/mode/" + aceMode);
	editor.setBehavioursEnabled(true); // auto-pairing of special characters, like quotation marks, parenthesis, or brackets
	editor.getSession().setUseSoftTabs(false); // true is spaces
	editor.setShowPrintMargin(true);
	editor.setShowInvisibles(false);
	editor.setDisplayIndentGuides(true);
	editor.setOption("scrollPastEnd", true);
	//editor.setOption("spellcheck", true);
	editor.setOptions({
		enableBasicAutocompletion: true,
		enableSnippets: true,
	});
	editor.getSession().setUseWrapMode(true);

	// Editor custom commands
	editor.commands.addCommands([{
		name: 'publish',
		bindKey: {win: 'Ctrl-S',  mac: 'Ctrl-S'},
		exec: function(editor) {
			autosaveCounter.stop();
			message('saving ...', 'danger');
			if (unpublishedChanges) {
				updatePost(false).done(function(response) {
					// post saved successfully
					unpublishedChanges = false;
					message('saved and published ' + response, 'success');
				});
			} else {
				message('no changes to publish', 'success');
			}
		},
		readOnly: true // false if this command should not apply in readOnly mode
	}, {
		name: 'publishShow',
		bindKey: {win: 'Ctrl-Shift-S',  mac: 'Ctrl-Shift-S'},
		exec: function(editor) {
			if (unpublishedChanges) {
				updatePost(false).done(function() {
					// post saved successfully
					window.location = postRoute;
				});
			} else {
				// no changes to post since last publish
				window.location = postRoute;
			}
		},
		readOnly: true // false if this command should not apply in readOnly mode
	}, {
		name: 'publishShow2',
		bindKey: {win: 'Ctrl-Enter',  mac: 'Ctrl-Enter'},
		exec: function(editor) {
			updatePost(false).done(function() {
				// post saved successfully
				window.location = postRoute;
			});
		},
		readOnly: true // false if this command should not apply in readOnly mode
	}, {
		name: 'discard',
		bindKey: {win: 'Ctrl-Shift-Esc',  mac: 'Ctrl-Shift-Esc'},
		exec: function(editor) {
			discard();
		},
		readOnly: true // false if this command should not apply in readOnly mode
	}, {
	    name: "showKeyboardShortcuts",
	    bindKey: {win: "Ctrl-Alt-h", mac: "Command-Alt-h"},
	    exec: function(editor) {
	        config.loadModule("ace/ext/keybinding_menu", function(module) {
	            module.init(editor);
	            editor.showKeyboardShortcuts();
	        });
	    }
	}]);


	

	// Editor autosave feature
	var saved = true;
	var unpublishedChanges = false;
	var autosaveCounter = new countdown({  
		seconds: autosaveDelay-1,
		onUpdateStatus: function(sec) {
			// callback for each second
			message('autosave in ' + sec);
		}, 
		onCounterEnd: function() {
			// Countdown complete, autosave post date
			message('saving ...', 'danger');
			updatePost(true).done(function(response) {
				// post saved successfully
				saved = true;
				unpublishedChanges = true;
				message(response, 'success');
			});
		}
	});
	editor.getSession().on('change', function(e) {
		saved = false;
		unpublishedChanges = true;
		message('autosave in ' + autosaveDelay);
		autosaveCounter.start();
	});
	editor.getSession().selection.on('changeCursor', function(e) {
		if (!saved) {
			message('autosave in ' + autosaveDelay);
			autosaveCounter.start();
		}
	});

	// Save only post content via ajax $.post
	function updatePost(autosave) {
		unpublishedChanges = false;
		var content = editor.getValue();
		
		return $.post(
			"{{ URL::route('updatePost', array('id' => $post->id)) }}",
			{
				content: content,
				autosave: autosave
			}
		);
	}

	// Save only post organizational settings
	function updatePostOrg() {
		return $.post(
			"{{ URL::route('updatePostOrg', array('id' => $post->id)) }}",
			{
				format: $('#format').val(),
				type: $('#type').val(),
				framework: $('#framework').val(),
				mode: $('#mode').val(),
				title: $('#title').val(),
				slug: $('#slug').val(),
				badges: $('#badges').val(),
				tags: $('#tags').val(),
				'new-tags': $('#new-tags').val(),
				hidden: $('#hidden').prop('checked'),
				hashtag: $('#hashtag').val(),
			}
		);
	}

	// Delete post
	function deletePost() {
		return $.post(
			"{{ URL::route('deletePost', array('id' => $post->id)) }}",
			{
				permanent: $('#confirm-delete').prop('checked'),
			}
		);
	}

	// Undelete post
	function undeletePost() {
		return $.post(
			"{{ URL::route('undeletePost', array('id' => $post->id)) }}"
		);
	}

	// Save only post permission settings
	function updatePostPerms() {
		// Get object of checked role/permission items
		var perms = new Array();
		//$('input[type=checkbox]').each(function () {
		$("input[name='perm']:checked").each(function () {
			var value = $(this).val();
			// Checkbox value is in form of 'role_xx_perm_yy'
			var perm = new Object();
			var tmp = value.split("_");
			perm.role_id = tmp[1];
			perm.perm_id = tmp[3];
			perms.push(perm);
		});

		return $.post(
			"{{ URL::route('updatePostPerms', array('id' => $post->id)) }}",
			{
				shared: $('#shared').prop('checked'),
				perms: JSON.stringify(perms)
			}
		);
	}

	// Save only post advanced settings
	function updatePostAdv() {
		return $.post(
			"{{ URL::route('updatePostAdv', array('id' => $post->id)) }}",
			{
				'default-slug': $('#default-slug').val(),
				'static': $('#static').prop('checked'),
				'symlink': $('#symlink').prop('checked'),
				'workbench': $('#workbench').val()
			}
		);
	}

	// Create app
	function createApp() {
		return $.post(
			"{{ URL::route('updatePostCreateApp', array('id' => $post->id)) }}",
			{
				'workbench': $('#workbench').val()
			}
		);
	}

	// Discard changes and return to view post
	function discard() {
		if (unpublishedChanges) {
			if (confirm("NOTICE: There are unpublished changes.  Changes will be lost!")) {
				unpublishedChanges = false; //to supress the window unload message
				// Remove unpublished revisions for this user
				$.ajax({
					url: "{{ URL::route('deleteRevision') }}",
					type: 'DELETE',
					data: {
						postID: {{ $post->id }},
						userID: {{ Mrcore::user()->id() }}
					}
				}).done(function(response) {
					// old unpublished revision deleted, show post
					window.location = postRoute;
				});
			}
		} else {
			window.location = postRoute;
		}
	}

	// Display small onscreen message
	function message(text, type) {
		if (!type) type = 'warning';
		if (/error/i.test(text)) type = 'danger';
		$('#alert').removeClass('alert-danger');
		$('#alert').removeClass('alert-success');
		$('#alert').removeClass('alert-warning');
		$('#alert').addClass('alert-' + type);
		$('#alert').html(text);
		$("#alert").slideDown(300);
		if (type == 'success') {
			$("#alert").delay(3000).slideUp(300);
		}
	}

	// Show uncommitted modal message
	@if (count($uncommitted) > 0)
	$('#myModal').modal({
		keyboard: false,
		show: true,
		backdrop: 'static',
	})
	@endif


	// Start chosen (before validator)
	//$(".chosen-select").chosen({ width: '250px' });
	// Attach Select2
	$(".select2-tags").select2();

	// Post organization form validation
	var validator = $('#organization-form').validate({
		errorElement: 'div',
		errorClass: 'help-inline',
		focusInvalid: true,
		ignore: ':hidden:not(.chzn-done)',

		rules: {
			title: {
				required: true
			}
		},
		messages: {
			title: {
				required: 'Title is required'
			}
		},

		highlight: function (e) {
			$(e).closest('.form-group').addClass('has-error');
		},

		success: function (e) {
			$(e).closest('.form-group').removeClass('has-error');
			$(e).remove();
		}
	});
	/*$('#organization-form').find('select.chosen-select').each(function(){
		$(this).chosen().change(function(){
			$(this).valid();
		});
		$(this).rules('add', {
			required: true,
		});
	});*/



	// Discard click event
	$('#btnCancel').click(function() {
		discard();
	})

	// Publish click event
	$('#btnPublish').click(function() {
		autosaveCounter.stop();
		message('saving ...', 'danger');
		if (unpublishedChanges) {
			updatePost(false).done(function() {
				// post saved successfully
				unpublishedChanges = false;
				message('saved and published', 'success');
			});
		} else {
			message('no changes to publish', 'success');
		}
	});

	// Publish and show click event
	$('#btnPublishShow').click(function() {
		if (unpublishedChanges) {
			updatePost(false).done(function(response) {
				// post saved successfully
				window.location = postRoute;
			});
		} else {
			// no changes to post since last publish
			window.location = postRoute;
		}
	});

	// Save post organization event
	$('#btnSaveOrg').click(function() {
		if ($("#organization-form").valid()) {
			message('saving ...', 'danger');
			updatePostOrg().done(function(response) {
				message(response, 'success');
			});
		}
	});
	$('#btnSaveOrgView').click(function() {
		if ($("#organization-form").valid()) {
			message('saving ...', 'danger');
			updatePostOrg().done(function(response) {
				if (!/error/i.test(response)) {
					$('#btnPublishShow').click();
				} else {
					message(response, 'success');
				}
			});
		}
	});

	// Delete Post
	$("#btnDeletePost").click(function() {
		if ($("#confirm-mark-delete").is(":checked")) {
			message('deleting...', 'danger');
			deletePost().done(function(response) {
				//console.log(response);
				window.location = "{{ URL::route('home') }}";
			}).fail(function() {
				alert('Delete Failed');
			});
		} else {
			alert('Please confirm deletion by checking the confirm checkbox');
		}
	});

	// Undelete Post
	$("#btnUndeletePost").click(function() {
		message('undeleting...', 'danger');
		undeletePost().done(function(response) {
			//console.log(response);
			window.location = postRoute;
		}).fail(function() {
			alert('Undelete Failed');
		});
	});

	// Save post permissions event
	$('#btnSavePerms').click(function() {
		if ($("#permissions-form").valid()) {
			message('saving ...', 'danger');
			updatePostPerms().done(function(response) {
				message(response, 'success');
			});
		}
	});
	$('#btnSavePermsView').click(function() {
		if ($("#permissions-form").valid()) {
			message('saving ...', 'danger');
			updatePostPerms().done(function(response) {
				if (!/error/i.test(response)) {
					$('#btnPublishShow').click();
				} else {
					message(response, 'success');
				}
			});
		}
	});

	// Save advanced event
	$('#btnSaveAdv').click(function() {
		if ($("#advanced-form").valid()) {
			message('saving ...', 'danger');
			updatePostAdv().done(function(response) {
				message(response, 'success');
			});
		}
	});
	$('#btnSaveAdvView').click(function() {
		if ($("#advanced-form").valid()) {
			message('saving ...', 'danger');
			updatePostAdv().done(function(response) {
				if (!/error/i.test(response)) {
					$('#btnPublishShow').click();
				} else {
					message(response, 'success');
				}
			});
		}
	});

	// Create App event
	$('#btnCreateApp').click(function() {
		if ($("#advanced-form").valid()) {
			message('saving ...', 'danger');
			createApp().done(function(response) {
				message(response, 'success');
			});
		}
	});





	// Help button
	$('#btnHelp').click(function() {
		window.open("{{ Config::get('mrcore.help') }}");
	})

	// Cheatsheet button
	$('#btnCheat').click(function() {
		window.open("{{ Config::get('mrcore.cheat') }}", "_blank", "width=800, height=600");
	})

	// Type dropdown changed (if app show framework dropdown)
	$('#type').change(function()
	{
		var type = $('#type option:selected').val();
		$('#framework-group').hide();
		if (type == {{ Config::get('mrcore.app_type') }}) {
			$('#framework-group').show();
		}

	});

	// Window Before Unload Warning
	$(window).bind('beforeunload', function(){
		if (unpublishedChanges) {
			return 'Click Publish or Discard instead of forcing the window closed';
		}
	});

})


// Automatic #editor div resize
function resizeAce(resize) {
	//45 if no header/crumbs, 95 if header no crumbs, 135 if header+crumbs
	var fromBottom = 50;
	if (resize) {
		$('#editor').height($(window).height() - fromBottom);
		$('#tab-content').height($(window).height() - (fromBottom - 1));
	} else {
		$('#editor').height($(window).height() - fromBottom);
		$('#tab-content').height($(window).height() - (fromBottom - 3));
	}
};
$( window ).resize(function() { resizeAce(true); });
resizeAce(false);




</script>
@stop