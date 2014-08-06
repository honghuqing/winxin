	//增加上传按扭
	function adduploadbutton(buttonid,progressid,cancelid,textareanamevalue,insertnamevalue)
	{
		<?php $timestamp = time();?>
		var settings = {flash_url : "/views/libs/swfupload/swfupload.swf",upload_url: "/upload_server_example.php",file_size_limit : "1 MB",file_types : "*.jpg;*.jpeg",file_types_description : "图片文件",file_upload_limit : 0,file_queue_limit : 5,file_post_name : "file",debug: false,		post_params : {'timestamp' : '<?php echo $timestamp;?>','token': '<?php echo md5('3a7e193dd3ce402aa6313a9640ccfa22d32s' . $timestamp);?>'},button_image_url: "/views/images/progressbar/swf-bg6.png", button_cursor : SWFUpload.CURSOR.HAND,button_width: "100",button_height: "41"
		,button_placeholder_id: buttonid,custom_settings : {progressTarget : progressid, cancelButtonId : cancelid, textareaname:textareanamevalue,insertname:insertnamevalue}
		,file_queued_handler : fileQueued,file_queue_error_handler : fileQueueError,file_dialog_complete_handler : fileDialogComplete,upload_start_handler : uploadStart,upload_progress_handler : uploadProgress,upload_error_handler : uploadError,upload_success_handler : uploadSuccess,upload_complete_handler : uploadComplete,queue_complete_handler : queueComplete};	// Queue plugin event};
		var swfu = new SWFUpload(settings);
	};


	$(window).load(function () {
		//循环增加，可以是多个，ID命名为fuzhujiancha
		var btnname = 'fuzhujiancha';
		for(i=1;i<=1;i++)
		{
			adduploadbutton(btnname + i + 'upload',btnname + i + 'progress',btnname + i + 'cancel',btnname + i,btnname + i + 'image');
		}
	});

 
	     function fileQueued(file) {
	     	try {
	     		var progress = new FileProgress(file, this.customSettings.progressTarget);
	     		progress.setStatus("Pending...");
	     		progress.toggleCancel(true, this);

	     	} catch (ex) {
	     		this.debug(ex);
	     	}

	     }

	     function fileQueueError(file, errorCode, message) {
	     	try {
	     		if (errorCode === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
	     			alert("You have attempted to queue too many files.\n" + (message === 0 ? "You have reached the upload limit." : "You may select " + (message > 1 ? "up to " + message + " files." : "one file.")));
	     			return;
	     		}

	     		var progress = new FileProgress(file, this.customSettings.progressTarget);
	     		progress.setError();
	     		progress.toggleCancel(false);

	     		switch (errorCode) {
	     		case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
	     			progress.setStatus("File is too big.");
	     			this.debug("Error Code: File too big, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
	     			break;
	     		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
	     			progress.setStatus("Cannot upload Zero Byte files.");
	     			this.debug("Error Code: Zero byte file, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
	     			break;
	     		case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
	     			progress.setStatus("Invalid File Type.");
	     			this.debug("Error Code: Invalid File Type, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
	     			break;
	     		default:
	     			if (file !== null) {
	     				progress.setStatus("Unhandled Error");
	     			}
	     			this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
	     			break;
	     		}
	     	} catch (ex) {
	             this.debug(ex);
	         }
	     }

	     function fileDialogComplete(numFilesSelected, numFilesQueued) {
	     	try {
	     		if (numFilesSelected > 0) {
	     			document.getElementById(this.customSettings.cancelButtonId).disabled = false;
	     		}
	     		$("#" + this.customSettings.progressTarget).show();
	     		/* I want auto start the upload and I can do that here */
	     		this.startUpload();
	     	} catch (ex)  {
	             this.debug(ex);
	     	}
	     }

	     function uploadStart(file) {
	     	try {
	     		/* I don't want to do any file validation or anything,  I'll just update the UI and
	     		return true to indicate that the upload should start.
	     		It's important to update the UI here because in Linux no uploadProgress events are called. The best
	     		we can do is say we are uploading.
	     		 */
	     		var progress = new FileProgress(file, this.customSettings.progressTarget);
	     		
	     		progress.setStatus("Uploading...");
	     		progress.toggleCancel(true, this);
	     	}
	     	catch (ex) {}
	     	
	     	return true;
	     }

	     function uploadProgress(file, bytesLoaded, bytesTotal) {
	     	try {
	     		var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);

	     		var progress = new FileProgress(file, this.customSettings.progressTarget);
	     		progress.setProgress(percent);
	     		progress.setStatus("Uploading...");
	     	} catch (ex) {
	     		this.debug(ex);
	     	}
	     }

	     function uploadSuccess(file, serverData) {
	     	try {
	     		var progress = new FileProgress(file, this.customSettings.progressTarget);
	     		progress.setComplete();
	     		 
	     		progress.setStatus("Complete.");
	     		progress.toggleCancel(false);
				 
 				var rs = jQuery.parseJSON(serverData);
	 
				if(rs.status == 'error')
				{
					alert(rs.errorstr);
				}else if(rs.status == 'success')
				{
			 		
					var picurl = rs.errorstr;
					 
					temphtml =   '<div class="imgbox01"><input type="hidden" name="'+ this.customSettings.textareaname+ '_image[]" value="' + picurl + '" /><a href="' + picurl+ '" title="" ><img src="' + picurl + '" alt=""/></a><p><input type="text" name="' +  this.customSettings.textareaname + '_desc[]" size="13" class="textuploadpicdefault" onblur="this.className=' + "'textuploadpicdefault'" + '" onfocus="this.className=' + "'textuploadpicenter'" + '" value="请填写描述"/></p><p style="width:97px; text-align:center;"><input class="deleteimgbtn" type="button"  onclick="deleteimg(this)"   value="删  除" /> </p> </div>';
					$("#" + this.customSettings.insertname).show();
					$("#" + this.customSettings.insertname).append(temphtml);
					$('.imgbox01 a').lightBox();
				}
	
				
	    		
	     	} catch (ex) {
	     		this.debug(ex);
	     	}
	     }

	     function uploadError(file, errorCode, message) {
	     	try {
	     		var progress = new FileProgress(file, this.customSettings.progressTarget);
	     		progress.setError();
	     		progress.toggleCancel(false);

	     		switch (errorCode) {
	     		case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
	     			progress.setStatus("Upload Error: " + message);
	     			this.debug("Error Code: HTTP Error, File name: " + file.name + ", Message: " + message);
	     			break;
	     		case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
	     			progress.setStatus("Upload Failed.");
	     			this.debug("Error Code: Upload Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
	     			break;
	     		case SWFUpload.UPLOAD_ERROR.IO_ERROR:
	     			progress.setStatus("Server (IO) Error");
	     			this.debug("Error Code: IO Error, File name: " + file.name + ", Message: " + message);
	     			break;
	     		case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
	     			progress.setStatus("Security Error");
	     			this.debug("Error Code: Security Error, File name: " + file.name + ", Message: " + message);
	     			break;
	     		case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
	     			progress.setStatus("Upload limit exceeded.");
	     			this.debug("Error Code: Upload Limit Exceeded, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
	     			break;
	     		case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
	     			progress.setStatus("Failed Validation.  Upload skipped.");
	     			this.debug("Error Code: File Validation Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
	     			break;
	     		case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
	     			// If there aren't any files left (they were all cancelled) disable the cancel button
	     			if (this.getStats().files_queued === 0) {
	     				document.getElementById(this.customSettings.cancelButtonId).disabled = true;
	     			}
	     			progress.setStatus("Cancelled");
	     			progress.setCancelled();
	     			break;
	     		case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
	     			progress.setStatus("Stopped");
	     			break;
	     		default:
	     			progress.setStatus("Unhandled Error: " + errorCode);
	     			this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
	     			break;
	     		}
	     	} catch (ex) {
	             this.debug(ex);
	         }
	     }

	     function uploadComplete(file) {
	     	if (this.getStats().files_queued === 0) {
	     		document.getElementById(this.customSettings.cancelButtonId).disabled = true;
	     	}
	     }

	     // This event comes from the Queue Plugin
	     function queueComplete(numFilesUploaded) {
	     	var status = document.getElementById("divStatus");
	     	status.innerHTML = numFilesUploaded + " file" + (numFilesUploaded === 1 ? "" : "s") + " uploaded.";
	     }


function deleteimg(obj)
{
	var fileurl =  $(obj).parent().parent().find("img").attr("src");
	var _url = "/upload_server_example.php";
	var _data =  "action=delete&fileurl=" + fileurl ; 
	$.ajax({
		url: _url,
		data: _data,
		type: "post",
		dataType: "json",
		success: function (_resdata)
		{
			//alert(_resdata);
		}
	});
	$(obj).parent().parent().remove();	
}
 

