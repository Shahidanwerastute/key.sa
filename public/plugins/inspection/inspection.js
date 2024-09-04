$(function () {
	/*Open Inspection Image*/
	$(document).on("click", ".inspection-images .form-input img", function(e) {
		custom_class.OpenPopup("inspection-images", $(this));
	});
	/*Inspection Make Shadow Active Function*/
	$(document).on("click", "#drawBtn", function(e) {
		$('.faultCreating .buttons ul li').removeClass('active');
		$(this).addClass('active');
		$(".watermarker-remove-item").remove();
		$('body').addClass('makePhyCondUp bodyOverHidden');
		$(".faultCreating").addClass("open");
		/*Set Scroll From Top*/
		$('html, body').animate({
			scrollTop: $(".faultCreating").position().top - 50
		}, 500);
	});
	/*Discard Changes*/
	$(document).on("click", ".discard", function() {
		$('body').removeClass('makePhyCondUp bodyOverHidden');
		$('.faultCreating .buttons ul li').removeClass('active');
		$(".watermarker-container").remove();
		$(".faultCreating").removeClass("open");
	});
	/*Clear all watermark*/
	$(document).on("click", "#clearAll", function(e) {
		custom_class.clear(clear_confirmation_title_language, e.originalEvent);
	});
	/*Erase watermark option*/
	$(document).on("click", ".erase-all", function(){
		custom_class.eraseEvent($(this));
		$(".faultCreating").removeClass("open");
	});
	/*Choose option from modal*/
	$(document).on("click", ".option ul li", function(){
		var src = $(this).find('img:first').attr('src');
		if(!$(this).hasClass('selfie')) custom_class.watermark(src);
	});
	/*Take picture from webcam*/
	$(document).on("click", ".selfie", function(){
		custom_class.selfie();
	});
	/*save watermark*/
	$(document).on("click", ".saveInspection", function(){
		custom_class.save_watermark();
	});
	/*save drop off inspection*/
	$(document).on("click", ".endTripInspection", function(e){
        custom_class.save_watermark_end_trip();
	});
	/*Close Popup*/
	$(document).on("click", ".close-icon", function(e){
	   custom_class.close();
	});
	/*Close Inspection Popup*/
	$(document).on("click", ".close-inspection-popup", function(e){
	   $(".faultCreating").removeClass("open");
	});
	//bar code scanner
	$(document).on("click", ".barCode", function(){
	  $("#camera video").css({"display": "block", "width": "100%"});
	  custom_class.scanner();
	});
});
custom_class = {
	watermark: function (src) {
		function updateCoords (coords){
			$("#posx").val(coords.x);
			$("#posy").val(coords.y);
			$("#width").val(coords.width);
			$("#height").val(coords.height);
			$("#opacity").val(coords.opacity);		
		}
		$(document).on("mousemove",function(event){
			$("#mousex").val(event.pageX);
			$("#mousey").val(event.pageY);
		});
		$(".inspection .image img:first").watermarker({
			imagePath: src,
			removeIconPath: base_url+"/public/frontend/inspection/images/close-icon.png",
			offsetLeft:50,
			offsetTop: 50,
			onChange: updateCoords,
			onInitialize: updateCoords,
			containerClass: "myContainer",
			watermarkImageClass: "myImage superImage",		
			watermarkerClass: "js-watermark-1 js-watermark",
			removeClass: "watermarker-remove-item",
			data: {id: 1, "class": "superclass", pepe: "pepe"},		
			onRemove: function(){
				if(typeof console !== "undefined" && typeof console.log !== "undefined"){
					console.log("Removing...");
				}
			},
			onDestroy: function(){
				if(typeof console !== "undefined" && typeof console.log !== "undefined"){
					console.log("Destroying...");	
				}
			}
		});
	},
	eraseEvent: function(erase) {
		if(erase.hasClass('active')) {
			$(".watermarker-remove-item").remove();
			erase.removeClass('active');
		} else {
			var $removeContainer = $("<div>", {
				"class": "watermarker-remove-item"
			});
			var $img = $("<img>", {
				src: base_url+"/public/frontend/inspection/images/close-icon.png"
			});
			$removeContainer.append($img);
			$(".watermarker-container").append($removeContainer);
			$('.faultCreating .buttons ul li').removeClass('active');
			erase.addClass('active');
		} 	
	},
	selfie: function () {
		$('#inspection-picture').modal('show');
	},
	hidePopup: function () {
        $('#inspection-picture').modal('hide');
	},
	save_watermark: function () {
		if(watermark_route) {

            $('.loaderSpiner').show();
			var obj = [];
			$(".watermarker-container").each(function( index ) {
				obj.push({
					x : $( this ).css('left').replace(/[^-\d\.]/g, ''), 
					y : $( this ).css('top').replace(/[^-\d\.]/g, ''),
					h : $( this ).css('height').replace(/[^-\d\.]/g, ''),
					w : $( this ).css('width').replace(/[^-\d\.]/g, ''),
					src : $( this ).find('img:first').attr('src'),
				});
			});
            var main_image_src = '<img src="'+base_url+'/public/frontend/inspection/images/mainImage.jpg" alt="" height="474" width="617">';
            var getPlateNo = $('#carPlate_no').val();
            var lastFuelTank = $('#lastFuelTank').val();
            var lastKm = $('#lastKm').val();
			$.ajax({
				type: "POST",
				url: watermark_route,
				dataType: "json",
				data: {data: JSON.stringify(obj), src: $(".inspection .image img:first").attr('src'), inspection_id: inspection_id, w: $(".inspection .image img:first").width(), h: $(".inspection .image img:first").height(), org_img_width : $(".inspection .image").css('width').replace(/[^-\d\.]/g, ''),getPlateNo:getPlateNo,lastKm:lastKm,lastFuelTank:lastFuelTank},
                cache: false,
                contentType: "application/x-www-form-urlencoded",
				success: function(data){
                    $('#getCarInsp').attr('src',data.src.encoded);
					$(".inspection .image img:first").attr('src',data.src.encoded);
					$(".discard").trigger("click");
					$('#showCarInspection').fadeOut('slow',function(){$(this).modal('hide');});
                    //show_wiz4();
                    $('.loaderSpiner').hide();
                    $('#mainImgSrc').html(main_image_src);
				}
			});
		}
	},
	save_watermark_end_trip: function () {
		if(end_trip_inspection_route) {

            $('.loaderSpiner').show();
			var obj = [];
			$(".watermarker-container").each(function( index ) {
				obj.push({
					x : $( this ).css('left').replace(/[^-\d\.]/g, ''),
					y : $( this ).css('top').replace(/[^-\d\.]/g, ''),
					h : $( this ).css('height').replace(/[^-\d\.]/g, ''),
					w : $( this ).css('width').replace(/[^-\d\.]/g, ''),
					src : $( this ).find('img:first').attr('src'),
				});
			});
            var main_image_src = '<img src="'+base_url+'/public/frontend/inspection/images/mainImage2.jpg" alt="" height="474" width="617">';
            var inspe_image_src = $(".inspection .image img:first").attr('src');
            var kmIn = $('#kmIn').val();
            var fuelTankIn = $('#fuelTankIn').val();
			$.ajax({
				type: "POST",
				url: end_trip_inspection_route,
				dataType: "json",
				data: {data: JSON.stringify(obj), src: $(".inspection .image img:first").attr('src'), inspection_id: inspection_id, w: $(".inspection .image img:first").width(), h: $(".inspection .image img:first").height(), org_img_width : $(".inspection .image").css('width').replace(/[^-\d\.]/g, ''),kmIn:kmIn,fuelTankIn:fuelTankIn},
				success: function(data){
					$('#endTripInsp').attr('src',data.src.encoded);
					$(".inspection .image img:first").attr('src',data.src.encoded);
					$(".discard").trigger("click");
					$('#showCarInspection').fadeOut('slow',function(){$(this).modal('hide');});
                    $('.loaderSpiner').hide();
                    $('#mainImgSrc').html(main_image_src);
				}
			});
		}
	},
	default_canvas: function (canvas, src, selector) {
		if(src) {
			var ctx = canvas.getContext('2d');
			var defImg = new Image();
			defImg.src = src;
			defImg.onload = function() {
				canvas.width = $("."+selector).find(".optinForm .canvWrapper").width();
				canvas.height = $("."+selector).find(".optinForm .canvWrapper").height();
				ctx.drawImage(defImg, 0, 0);
			};
		} else {
			canvas.width = $("."+selector).find(".optinForm .canvWrapper").width();
			canvas.height = $("."+selector).find(".optinForm .canvWrapper").height();
		}
	},
	close: function (src) {
		$('.optinFormWrapper').fadeOut(500);
		$('body').removeClass('BodyOverHidden');
		$('.oFTextArea').removeClass('isActive');
	},
	isCanvasBlank: function (selector) {
		var canvas = document.getElementById(selector);
		var blank = document.createElement('canvas');
		blank.width = canvas.width;
		blank.height = canvas.height;
		return canvas.toDataURL() == blank.toDataURL();
	},
	OpenPopup: function (selector, $clicked = null) {
		if($("."+selector).find('.oFTextArea').length)
		{
			$("."+selector).find('.optinFormWrapper').fadeIn(500);
			$('body').addClass('BodyOverHidden');
			$("."+selector).find('.oFTextArea').addClass('isActive');
		}
		if(selector == "inspection-images" && $(".inspection-images").length)
			$("."+selector).find('.oFTextArea img:first').attr('src', $clicked.attr('src'))
	},
	convertFileToDataURLviaFileReader: function (url, callback) {
		var xhr = new XMLHttpRequest();
		xhr.onload = function() {
			var reader = new FileReader();
			reader.onloadend = function() {
				callback(reader.result);
			}
			reader.readAsDataURL(xhr.response);
		};
		xhr.open('GET', url);
		xhr.responseType = 'blob';
		xhr.send();
	},
	clear: function (outputMsg, originalEvent, titleMsg, onCloseCallback) {
		if (!outputMsg) return;
		var div=$('<div></div>');
		$('#loader-content').fadeOut('slow',function(){$(this).show();});
		$('.faultCreating .buttons ul li').removeClass('active');
		$(".watermarker-container").remove();
		$(".faultCreating").removeClass("open");
		var imageName = 'mainImage.jpg';
		if(inspection_mode === 'dropOff'){
            imageName = 'mainImage2.jpg';
		}
		if (originalEvent !== undefined) {
			$(this).addClass('active');
			$.ajax({
				type: "POST",
				url: clear_inspection_route,
				data: {inspection_id: inspection_id},
				success: function(data){
					custom_class.convertFileToDataURLviaFileReader(base_url+"/public/frontend/inspection/images/mainImage.jpg", function(base64Img) {
						$(".inspection .image img:first").attr('src', base64Img)
					});
					$('#loader-content').fadeOut('slow',function(){$(this).hide();});
				}
			});
		}
	},
	scanner: function () {
		$('#qrScanner').modal('show');

        // Use facingMode: environment to attemt to get the front camera on phones
        navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } }).then(function(stream) {
            video.srcObject = stream;
            video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
            video.play();
            requestAnimationFrame(tick);
        });
	}
};
function drawLine(begin, end, color) {
    canvas.beginPath();
    canvas.moveTo(begin.x, begin.y);
    canvas.lineTo(end.x, end.y);
    canvas.lineWidth = 4;
    canvas.strokeStyle = color;
    canvas.stroke();
}
function tick() {
    if (video.readyState === video.HAVE_ENOUGH_DATA) {
        canvasElement.hidden = false;
        outputContainer.hidden = false;

        canvasElement.height = 150;
        canvasElement.width = 150;
        canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
        var imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
        var code = jsQR(imageData.data, imageData.width, imageData.height, {
            inversionAttempts: "dontInvert",
        });
        if (code) {
            drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#FF3B58");
            drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#FF3B58");
            drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner, "#FF3B58");
            drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#FF3B58");
            outputMessage.hidden = true;
            outputData.parentElement.hidden = false;
            outputData.innerText = code.data;
            $('#carPlate_no').val(code.data);
            if(checkArNumericString(code.data)){
                $('#qrScanner').modal('hide');
                selectCarPlate(booking_id,code.data);
			}else{
                $('.response_msg').text(qr_error_message);
                $('.humanLessPopup').click();
                $('#qrScanner').modal('hide');
                return false;
			}

        } else {
            outputMessage.hidden = false;
            outputData.parentElement.hidden = true;
        }
    }
    requestAnimationFrame(tick);
}

//check arabic character and number string (saudi car plate number)
function checkArNumericString(string){
    //regex for alphanumeric string without spaces.
    if( string.match(/^[\u0621-\u064A\s0-9]*$/) ) {
        return true;
    }else{
        return false;
    }
}
