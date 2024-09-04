$(function () {
	if (typeof pickup_photo !== 'undefined' ) {
		$.each(pickup_photo, function(key, obj) {
			custom_class.watermark(obj, ".pickup_inspection img:first");
		});
	}
	if (typeof dropoff_photo !== 'undefined' ) {
		$.each(dropoff_photo, function(key, obj) {
			custom_class.watermark(obj, ".drop_off_inspection img:first");
		});
	}
	/*open watermark photo in popup*/
	$(document).on("click", ".superImage", function(){
		custom_class.open_image($(this).attr('data-image'));
	});
	/*Close Popup*/
	$(document).on("click", ".close-icon", function(){
	   custom_class.close();
	});
});
custom_class = {
	default_canvas: function (canvas, src) {
		var ctx = canvas.getContext('2d');
		var defImg = new Image();
		defImg.src = src;
		defImg.onload = function() {
			canvas.width = defImg.width;
			canvas.height = defImg.height;
			ctx.drawImage(defImg, 0, 0);
		};
	},
	watermark: function (obj, selector) {
		xnew = custom_class.getNewX(obj.x, pickup_image_width, $(selector).width());
		ynew = custom_class.getNewY(obj.y, pickup_image_height, $(selector).height());
		function updateCoords (coords){
			$(coords.element).css("width", '30');
			$(coords.element).css("height", '30');
			$(coords.element).find('img:first').attr('data-image', obj.image)
		}
		$(document).on("mousemove",function(event){
			$("#mousex").val(event.pageX);
			$("#mousey").val(event.pageY);
		});
		$(selector).watermarker({
			imagePath: base_url+"/public/plugins/watermark/images/zoom.png",
			onInitialize: updateCoords,
			containerClass: "myContainer",
			watermarkImageClass: "myImage superImage",		
			watermarkerClass: "js-watermark-1 js-watermark",
			removeClass: "watermarker-remove-item",
			resizerClass: "",
			draggingClass: "",
			resizingClass: "",
			offsetLeft: xnew,
            offsetTop: ynew,
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
	getNewX: function (xValue, oldWidth, newWidth){
	   return xValue * newWidth / oldWidth;
	},
	getNewY: function (yValue, oldWidth, newWidth){
	   return yValue * newWidth / oldWidth;
	},
	open_image: function (src) {
		if($('.oFTextArea').length)
		{
			$('.oFTextArea img').attr('src', src);
			$('.optinFormWrapper').fadeIn(500);
			$('body').addClass('BodyOverHidden');
			$('.oFTextArea').addClass('isActive');
		}
	},
	close: function (src) {
		$('.optinFormWrapper').fadeOut(500);
		$('body').removeClass('BodyOverHidden');
		$('.oFTextArea').removeClass('isActive');
	}
};
