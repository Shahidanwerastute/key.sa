document.addEventListener('DOMContentLoaded', function() {
	// Detect Mobile Device 
	var isMobile = {
		Android: function() {
			return navigator.userAgent.match(/Android/i);
		},
		BlackBerry: function() {
			return navigator.userAgent.match(/BlackBerry/i);
		},
		iOS: function() {
			return navigator.userAgent.match(/iPhone|iPad|iPod/i);
		},
		Opera: function() {
			return navigator.userAgent.match(/Opera Mini/i);
		},
		Windows: function() {
			return navigator.userAgent.match(/IEMobile/i);
		},
		any: function() {
			return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
		}
	};
    // References to all the element we will need.
    var video = document.querySelector('#camera-stream'),
        image = document.querySelector('#snap'),
        start_camera = document.querySelector('#start-camera'),
        controls = document.querySelector('.controls'),
        take_photo_btn = document.querySelector('#take-photo'),
        start_video = document.querySelector('#delete-photo'),
        error_message = document.querySelector('#error-message');
    // The getUserMedia interface is used for handling camera input.
    // Some browsers need a prefix so here we're covering all the options
    navigator.getMedia = (navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia);
    if (!navigator.getMedia) {
        displayErrorMessage("Your browser doesn't have support for the navigator.getUserMedia interface.");
    } else {
		if(1===2)
		{
			var constraints = window.constraints = {
			  audio: false,
			  video: { facingMode: { exact: "environment" } }
			};
			navigator.mediaDevices.getUserMedia(constraints).
			then(handleSuccess).catch(handleError);
		} else {
            // Use facingMode: environment to attemt to get the front camera on phones
            navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } }).then(function(stream) {
                video.srcObject = stream;
                video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
                video.play();
            },
            // Error Callback
            function(err) {
                displayErrorMessage("There was an error with accessing the camera stream: " + err.name, err);
            });

		}
    }
    // Mobile browsers cannot play video without user input,
    // so here we're using a button to start it manually.
    start_camera.addEventListener("click", function(e) {
        e.preventDefault();
        // Start video playback manually.
        video.play();
        showVideo();
    });
    take_photo_btn.addEventListener("click", function(e) {
        e.preventDefault();
        var snap = takeSnapshot();
        // Show image. 
        image.setAttribute('src', snap);
        image.classList.add("visible");
        // Enable delete and save buttons
        start_video.classList.remove("disabled");
        // Pause video playback of stream.
        video.pause();
		/*Add watermark for inspection*/
		custom_class.watermark(snap);
		$(".faultCreating").removeClass("open");
		custom_class.hidePopup();
    });
    start_video.addEventListener("click", function(e) {
        e.preventDefault();
        // Hide image.
        image.setAttribute('src', "");
        image.classList.remove("visible");
        // Disable delete and save buttons
        start_video.classList.add("disabled");
        // Resume playback of stream.
        video.play();
    });

    function showVideo() {
        // Display the video stream and the controls.
        hideUI();
        video.classList.add("visible");
        controls.classList.add("visible");
    }

    function takeSnapshot() {
        // Here we're using a trick that involves a hidden canvas element.  
        var hidden_canvas = document.querySelector('#canvas'),
            context = hidden_canvas.getContext('2d');
        var width = video.videoWidth,
            height = video.videoHeight;
        if (width && height) {
            // Setup a canvas with the same dimensions as the video.
            hidden_canvas.width = width;
            hidden_canvas.height = height;
            // Make a copy of the current frame in the video on the canvas.
            context.drawImage(video, 0, 0, width, height);
            // Turn the canvas image into a dataURL that can be used as a src for our photo.
            return hidden_canvas.toDataURL('image/png');
        }
    }

    function displayErrorMessage(error_msg, error) {
        error = error || "";
        if (error) {
            console.error(error);
        }
        error_message.innerText = error_msg;
        hideUI();
        error_message.classList.add("visible");
    }

    function hideUI() {
        // Helper function for clearing the app UI.
        controls.classList.remove("visible");
        start_camera.classList.remove("visible");
        video.classList.remove("visible");
        snap.classList.remove("visible");
        error_message.classList.remove("visible");
    }
	
	function handleSuccess(stream) {
		var videoTracks = stream.getVideoTracks();
		console.log('Got stream with constraints:', constraints);
		console.log('Using video device: ' + videoTracks[0].label);
		stream.oninactive = function() {
			console.log('Stream inactive');
		};
		window.stream = stream; // make variable available to browser console
		video.srcObject = stream;
	}

	function handleError(error) {
		if (error.name === 'ConstraintNotSatisfiedError') {
			errorMsg('The resolution ' + constraints.video.width.exact + 'x' +
			constraints.video.width.exact + ' px is not supported by your device.');
		} else if (error.name === 'PermissionDeniedError') {
			errorMsg('Permissions have not been granted to use your camera and ' +
			'microphone, you need to allow the page access to your devices in ' +
			'order for the demo to work.');
		}
		errorMsg('getUserMedia error: ' + error.name, error);
	}

	function errorMsg(msg, error) {
        //console.log(msg);
		errorElement.innerHTML += '<p>' + msg + '</p>';
		if (typeof error !== 'undefined') {
			console.error(error);
		}
	}
}); 