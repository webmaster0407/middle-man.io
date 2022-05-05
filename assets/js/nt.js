var ntPageHandlers = function() {

	var handleConfirmationSection = function() {
		$('.confirm_no_btn').on('click', function() {
			window.location.href = BASE_URL;
		});

		$('.destroy_note').on('click', function() {
			var elem = $(this);
			var id = elem.attr('data-val');
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
						// var note = data.note;
						var destr = data.destruct;
						var hash_id = data.note.hashed_id;	
						var notification_string = (destr.is_read == 1) ? 'The note with id <span>' + hash_id + '</span> was read and destroyed' : 'The note with id <span>' + hash_id + '</span> was destroyed';
						$('.confirmation_noti_info').text('Note destroyed');
						$('.confirmation_buttons').remove();
						$('.confirmation_notification').html(notification_string);
						$('.destroy_note_wrapper').remove();
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


		$('.confirm_yes_btn').on('click', function() {
			var elem = $(this);
			var id = elem.attr('data-val');
			var hash = location.hash;

			// update note state to is_read true and show notes if hash is correct
			$.ajax({
				url: UPDATE_READSTATE_NOTE_URL,
				type: "POST",
				data: {
					id: id,
					hash: hash
				},
				beforeSend: function() {
					$.blockUI({ message: '<span class="spinner spinner-primary"></span>' });
				},
				success: function(data) {
					data = $.parseJSON(data);
					if (data.status === "success") {
						location.hash = '#hidden';
						$('.confirmation_section').fadeOut();
						$('.note_content').fadeIn();
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
	};

	var handleDownload = function() {

		$('.download_attach').on('click', function() {
			var elem = $(this);
			var id = elem.attr("data-val");  // attached id
			var filename = elem.attr("data-val-filename");  // attached file name
			console.log(filename);
			$.ajax({
				url: DOWNLOAD_FILE_URL,
				type: "POST",
				data: {
					id: id,
				},
                xhr: function () {
                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState == 2) {
                            if (xhr.status == 200) {
                                xhr.responseType = "blob";
                            } else {
                                xhr.responseType = "text";
                            }
                        }
                    };
                    return xhr;
                },
				beforeSend: function() {
					$.blockUI({ message: '<span class="spinner spinner-primary"></span>' });
				},
                success: function (data) {
                    // Convert the Byte Data to BLOB object.
                    var blob = new Blob([data], { type: "application/octetstream" });
 						
                    //Check the Browser type and download the File.               
                    var isIE = false || !!document.documentMode;
                    if (isIE) {
                        window.navigator.msSaveBlob(blob, filename);
                    } else {
                        var url = window.URL || window.webkitURL;
                        link = url.createObjectURL(blob);
                        var a = $("<a />");
                        a.attr("download", filename);
                        a.attr("href", link);
                        $("body").append(a);
                        a[0].click();
                        $("body").remove(a);
                    }
                    $.unblockUI();
                },
				error: function(error) {
					console.log(error);
					$.unblockUI();
				}
			});
		});
	}

	return {
		init: function() {
			handleConfirmationSection();
			handleDownload();
		},
	};
}();

jQuery(document).ready(function() {
	ntPageHandlers.init();
});