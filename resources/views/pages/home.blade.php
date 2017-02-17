<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="csrf-token" content="{!! csrf_token() !!}">
	<title>FACEBOOK</title>


	<script src="{!!asset('public')!!}/js/jquery-2.1.1.js"></script>
	<script src="{!!asset('public')!!}/js/textcounter.min.js"></script>
	<script src="{!!asset('public')!!}/js/html2canvas.js"></script>
	<script src="{!!asset('public')!!}/js/jquery.validate.min.js"></script>
	<script src="{!!asset('public')!!}/js/base64binary.js"></script>



	<script>
	  window.fbAsyncInit = function() {
	    FB.init({
	      appId      : '{!!env("FACEBOOK_APP_ID")!!}',
	      cookie      : true,
			// xfbml  : true,
	      version    : 'v2.8'
	    });
	    FB.AppEvents.logPageView();
	  };

	  (function(d, s, id){
	     var js, fjs = d.getElementsByTagName(s)[0];
	     if (d.getElementById(id)) {return;}
	     js = d.createElement(s); js.id = id;
	     js.src = "//connect.facebook.net/en_US/sdk.js";
	     fjs.parentNode.insertBefore(js, fjs);
	   }(document, 'script', 'facebook-jssdk'));
	</script>

	<script type="text/javascript">
		function checkLoginState(){
			FB.getLoginStatus(function(response) {
			  if (response.status === 'connected') {
			    // console.log(response.authResponse.accessToken);
			    post();
			  } else if (response.status === 'not_authorized') {
			   		console.log('not authenticated app');
			  } else {
			    login();
			    post();
			  }
			});
		}

		function getPictureAjax(){
			FB.getLoginStatus(function(response) {
			  if (response.status === 'connected') {
			    // console.log(response.authResponse.accessToken);
			    getPicture();


			  } else if (response.status === 'not_authorized') {
			   		console.log('not authenticated app');
			  } else {
			    login();
			    getPicture();
			  }
			});
		}

		function post(){
			FB.ui({
			  method: 'feed',
			  display: 'popup',
			  caption: 'Letter from future',
			  link : 'http://ansonvn.com/testfb/test',
			  redirect_uri: '{!!url("done")!!}'

			}, function(response){
				console.log(response);
			});
		}

		function login(){
			FB.login(function(respone){
				// console.log(response);
			},{scope: 'email,publish_actions'})
		}

		function getPicture(){
			FB.api('me/picture',function(response){
				// console.log(response.data.url);
			})
		}

	

	// Convert a data URI to blob
	function dataURItoBlob(dataURI) {
	    var byteString = atob(dataURI.split(',')[1]);
	    var ab = new ArrayBuffer(byteString.length);
	    var ia = new Uint8Array(ab);
	    for (var i = 0; i < byteString.length; i++) {
	        ia[i] = byteString.charCodeAt(i);
	    }
	    return new Blob([ab], {
	        type: 'image/png'
	    });
	}

	function postImageToFacebook(token, filename, mimeType, imageData, message) {
	    var fd = new FormData();
	    fd.append("access_token", token);
	    fd.append("source", imageData);
	    fd.append("no_story", true);

	    // Upload image to facebook without story(post to feed)
	    $.ajax({
	        url: "https://graph.facebook.com/me/photos?access_token=" + token,
	        type: "POST",
	        data: fd,
	        processData: false,
	        contentType: false,
	        cache: false,
	        success: function (data) {
	            console.log("success: ", data);

	            // Get image source url
	            FB.api(
	                "/" + data.id + "?fields=images",
	                function (response) {
	                    if (response && !response.error) {
	                        //console.log(response.images[0].source);

	                        // Create facebook post using image
							FB.ui({
							  method: 'feed',
							  display: 'popup',
							  name: filename,
							  caption: 'Letter from future',
							  description: message,
							  picture: response.images[0].source,
							  link : 'http://ansonvn.com/testfb/test',

							}, function(response){
								console.log(response);
							});
	                    }
	                }
	            );
	        },
	        error: function (shr, status, data) {
	            console.log("error " + data + " Status " + shr.status);
	        },
	        complete: function (data) {
	            console.log('Post to facebook Complete');
	            // window.location = "{!!url('done')!!}"
	        }
	    });
	}


	</script>
	<script>
		
		$(document).ready(function(){
			var getCanvas;
			$('textarea[name="letter"]').textcounter({
				type: 'word',
				countDown: true,
				countDownText : 'Tổng số chữ còn lại: %d'
			})

			$('.form-letter').validate({
				submitHandler:function(data){
					var from = $('input[name="from"]').val();
					var message = $('textarea[name="letter"]').val();

					getPictureAjax();
					$.ajax({
						url:'{!!url("send/ajax")!!}',
						type:'POST',
						data:{x:from,y:message, _token:$("meta[name='csrf-token']").attr("content")},
						success:function(data){
							// console.log(data.rs);
							$('#preview').html(data.rs);

							/*RENDER IMAGE*/
							var element = $('#preview');
							html2canvas(element, {
						        onrendered: function (canvas) {
								  var dataimg = canvas.toDataURL("image/png");
									try {
									    blob = dataURItoBlob(dataimg);
									} catch (e) {
									    console.log(e);
									}
									FB.getLoginStatus(function (response) {
										if (response.status === "connected") {
										postImageToFacebook(response.authResponse.accessToken, "Letter From Future", "image/png", blob, message);
										} else if (response.status === "not_authorized") {
										FB.login(function (response) {
										postImageToFacebook(response.authResponse.accessToken, "Letter From Future", "image/png", blob, message);
										}, {scope: "publish_actions"});
										} else {
										FB.login(function (response) {
										postImageToFacebook(response.authResponse.accessToken, "Letter From Future", "image/png", blob, message);
										}, {scope: "publish_actions"});
										}
									});
								}
				         	});
				         	$('#preview').hide();
						}
					})
				}
			})
		})
	</script>

	<style>
		*{margin:0; padding:0;box-sizing: border-box;}
		body{font-size:12px; line-height: 20px; box-sizing: border-box;}
		.wrap{
			background:url('{!!asset('public')!!}/images/bg-letter.png');
			background-position: center top;
			background-repeat: no-repeat;
			max-width:800px;
			width: 100%;
			height:1275px;
			margin:0 auto;
			padding:180px 60px;
		}
		.form-group{
			margin-bottom: 20px;
		}
		.form-group label{
			font-size:20px;
			width:100px;
			padding-right:20px;
			text-align: right;
			display: inline-block;
			text-transform: uppercase;
			vertical-align: bottom;
			color:#6b2020;
		}
		.form-group input, textarea{
			width:calc(100% - 120px);
			border:none;
			display: inline-block;
			background:url('{!!asset('public')!!}/images/dot.png');
			background-position: 0 -37px;
			background-repeat: repeat-x;
			outline: none;
		    padding: 0px 3px;
		    font-size: 20px;
		    color: #3c0101;
		}
		#message{
			background:url('{!!asset('public')!!}/images/dot2.png');
			height:700px;
			resize:none;
			background-repeat: repeat;
			line-height: 35px;
			letter-spacing: 1px;
			background-position: 0 -5px;
		}
		.text{
			vertical-align: top !important;
			padding-top:10px;
		}
		.btn-wrap{
			text-align: right;
		}
		input#send{
			width:140px;
			height:58px;
			font-size:25px;
			color:#6b2020;
			background:url('{!!asset('public')!!}/images/send.png');
			padding-left:30px;
			text-transform: uppercase;
			font-weight: 600;
			border: none;
			box-shadow: none;
			outline: none;
			cursor: pointer;
		}
		#preview{
			width:600px;
			height:315px;
		}

	</style>
</head>

<body>

	<div class="wrap">
		<form action="" method="POST" class="form-letter">
			{!!Form::token()!!}
			<div class="form-group" class="from-group">
				<label for="from" class="from">From:</label>
				<input type="text" name="from" class="input-me" id="from" >
			</div>
			<div class="form-group" class="to-group">
				<label for="to" class="from">To:</label>
				<input type="text" name="to" class="input-me" id="to" >
			</div>
			<div class="form-group" class="message">
				<label for="message" class="text">Message</label>
				<textarea name="letter" id="message"></textarea>
			</div>
			<div class="form- btn-wrap" >
				<input type="submit" value="Gửi" id="send">
			</div>
		</form>
	</div>
	<div id="preview" ></div>
</body>
</html>