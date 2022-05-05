var optionShowState = 0;
var isFileUploaded = 0;

var homeEventHandlers = function() {

	// Show Options/Hide Options event handler
	var handleShowHideOptionsHandler = function() {
		$('.show_hide_options').on('click', function() {
			if (optionShowState === 0) {
				optionShowState = 1;
				$(this).html('Hide options');
				$('.optional_items').fadeIn(700);
			} else if (optionShowState === 1) {
				optionShowState = 0;
				$(this).html('Show options');
				$('.optional_items').fadeOut(700);
			}
		});
	};

	// file uploading using dropzone
	var privateDropzoneHandler = function() {
		var hiddenFileName = "";

		$("#my-private-dropzone").dropzone({ 
			url: UPLOAD_URL,
			maxFilesize: 50, // MB
			uploadMultiple: false,
			timeout: 50000,
			maxFiles: 1,
			init: function() {
		        this.on("processing", function(file) {
		        	$.blockUI({ message: '<span class="spinner spinner-primary"></span>' });
		        });

		        this.on("complete", function(file) {
		        	$.unblockUI();
		        });
		    },
			renameFile: function(file) {
                var dt = new Date();
                var time = dt.getTime();
                const tagArray = file.name.split('.');
                var extension = tagArray[tagArray.length - 1];
                var returnName = time + '.' + extension;
                hiddenFileName = returnName;
                $('#upload_file_name').val(hiddenFileName);
                return returnName;
            },
   			accept: function(file, done) {
   				isFileUploaded = 1;
   				$('#my-private-dropzone').addClass('no_action');
   				$('.upload_section').addClass('no_allowd_cursor');
                if (file.name == "justinbieber.jpg") {
                    done("Naha, you don't.");
                } else {
                    done();
                }
            },
            removedfile: function(file) {

            }
		});
	};

	// create note handler
	var saveButtonHandler = function() {
		$('.create_btn').on('click', function() {
			if (createNoteValidator() === false) {
				return;
			}

			var note = $('#note').val();
			var uploadedFileName = $('#upload_file_name').val();
			var destruct_actions = $('#destruct_actions').val();
			var destruct_confirm = $('#destruct_confirm').is(':checked');
			var password = $('#password').val();
			var notification = $('#notification').val();
			var ref_name = $('#ref_name').val();
			var access_by_ip = $('#access_by_ip').val();
			var sms_secure = $('#sms_secure').val();
			
			$.ajax({
				url: CREATE_NOTE_URL,
				type: "POST",
				data: {
					note: note,
					uploadedFileName: uploadedFileName,
					destruct_actions: destruct_actions,
					destruct_confirm: destruct_confirm,
					password: password,
					notification: notification,
					ref_name: ref_name,
					access_by_ip: access_by_ip,
					sms_secure: sms_secure,
					isFileUploaded : isFileUploaded
				},
				beforeSend: function() {
					$.blockUI({ message: '<span class="spinner spinner-primary"></span>' });
				},
				success: function(data) {
					data = $.parseJSON(data);
					if (data.status === "success") {
						$('.create_section').fadeOut();
						$('.result_section').fadeIn();
						var note = data.data;
						var newUrl = BASE_URL + '/n/' + note.hashed_id + '#' + note.manual_password;
						$('#hidden_id').val(note.id);
						$('#show_rlt_link').val(newUrl);
						$('.rlt_btn1').parent().attr('href', 'mailto:?body=' + newUrl);
					} else {
						swal({
                            title: data.data,
                            type: 'error',
                            confirmButtonText: "Ok, got it!",
                            confirmButtonClass: 'btn-warning',
                            showConfirmButton: false,
                            timer: 3000,
                        });
					}
					$.unblockUI();
				},
				error: function(error) {
					console.log(error);
					$.unblockUI();
				}
			});
			 
		});

		var createNoteValidator = function() {
			var note = $('#note').val();
			if (note === "" && isFileUploaded === 0) {
				swal({
                    title: 'You should write private note or should upload private file',
                    type: 'error',
                    confirmButtonText: "Okay, Got it!",
                    confirmButtonClass: 'btn-danger',
                    showConfirmButton: true,
                });
				return false;
			}
			if ($('#password_confirm').val() !== $('#password').val()) {
				swal({
                    title: 'Password not matches with Password confirmation',
                    type: 'error',
                    confirmButtonText: "Ok, Got it!",
                    confirmButtonClass: 'btn-danger',
                    showConfirmButton: true,
                });
				return false;
			}

			if ( $('#password').val() != "" && $('#password').val().length < 6 ) {
				swal({
                    title: 'Password is too simple',
                    type: 'error',
                    confirmButtonText: "Ok, Got it!",
                    confirmButtonClass: 'btn-danger',
                    showConfirmButton: true,
                });
				return false;
			}

			if ($('#notification').val() != "") {
				var email = $('#notification').val();
				let regex = new RegExp('[a-z0-9]+@[a-z]+\.[a-z]{2,3}');
				if (regex.test(email) == false) {
					swal({
	                    title: 'Email address is not valid',
	                    type: 'error',
	                    confirmButtonText: "Ok, Got it!",
	                    confirmButtonClass: 'btn-danger',
	                    showConfirmButton: true,
	                });
					return false;
				}
			}

			return true;
		};
	};

	var resultHandler = function() {
		$('.rlt_btn2').on('click', function() {
			var cpyText = $('#show_rlt_link')[0];
			/* Select the text field */
			cpyText.select();
			cpyText.setSelectionRange(0, 99999); /* For mobile devices */
   			/* Copy the text inside the text field */
  			navigator.clipboard.writeText(cpyText.value);
			swal({
                title: 'Copied!',
                type: 'success',
                confirmButtonText: "Ok, Got it!",
                confirmButtonClass: 'btn-success',
                showConfirmButton: false,
                timer: 1500,
            });
  			$(this).html('<span class="fa fa-check">&nbsp;</span>COPIED');
		});

		$('.rlt_btn3').on('click', function() {
			var id = $('#hidden_id').val();
			$.ajax({
				url: UPDATE_EXPIRED_STATE_URL,
				type: "POST",
				data: {
					id: id,
				},
				beforeSend: function() {
					$.blockUI({ message: '<span class="spinner spinner-primary"></span>' });
				},
				success: function(data) {
					data = $.parseJSON(data);
					if (data.status === "success") {
						var note = data.note;
						var hash_id = note.hashed_id;	

						$('.show_rlt_link_info').html("You're about to read and destroy the note with id " + hash_id + ". <span style='font-weight: 600; font-size: 24px;'>" + hash_id) + "</span>";
						$('.rlt_note_desc').remove();
						$('.show_rlt_link_wrapper').remove();
						$('.show_rlt_link_actions').remove();

						$.unblockUI();
					} else {
						$.unblockUI();
						swal({
		                    title: data.data,
		                    type: 'error',
		                    confirmButtonText: "Ok, Got it!",
		                    confirmButtonClass: 'btn-danger',
		                    showConfirmButton: true,
		                });
					}
				},
				error: function(error) {
					$.unblockUI();
					console.log(error);
				}
			});

		});
	}

	return {
		init: function() {
			handleShowHideOptionsHandler();
			privateDropzoneHandler();
			saveButtonHandler();
			resultHandler();
		},
	};
}();


jQuery(document).ready(function() {
	homeEventHandlers.init();
});