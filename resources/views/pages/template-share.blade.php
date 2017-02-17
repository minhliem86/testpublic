	<div class="wrap" id="canvas">
		<div class="content">
			<h2>Letter From Future</h2>
			<form action="" method="POST" class="form-letter">
				<div class="form-group" class="from-group">
					<label for="from" class="from">From:</label>
					<input type="text" name="from" class="input-me" id="from" value="{!!$from!!}" >
				</div>
				<!-- <div class="form-group" class="to-group">
					<label for="to" class="from">To:</label>
					<input type="text" name="to" class="input-me" id="to" >
				</div> -->
				<div class="form-group" class="message">
					<label for="message" class="text">Message</label>
					<textarea name="letter" id="message">{!!$message!!}</textarea>
				</div>
			</form>
		</div>

	</div>
	<style>

		#canvas.wrap{
			background:url('{!!asset('public')!!}/images/fb-share-bg.png');
			background-position: center top;
			background-repeat: no-repeat;
			max-width:600px;
			width: 100%;
			height:315px;
			margin:0 auto;
			padding:50px 40px;
		}
		#canvas .form-group{
			margin-bottom: 10px;
		}
		#canvas .form-group label{
			font-size:20px;
			width:100px;
			padding-right:20px;
			text-align: right;
			display: inline-block;
			text-transform: uppercase;
			vertical-align: bottom;
			color:#6b2020;
		}
		#canvas .form-group input, textarea{
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
		#canvas #message{
			background:url('{!!asset('public')!!}/images/dot2.png');
			height:120px;
			resize:none;
			background-repeat: repeat;
			line-height: 35px;
			letter-spacing: 1px;
			background-position: 0 -5px;
		}
		#canvas .text{
			vertical-align: top !important;
			padding-top:10px;
		}
		#canvas .btn-wrap{
			text-align: right;
		}
		#canvas input#send{
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
		#canvas h2{
			font-size:25px;
			text-transform: uppercase;
			text-align: center;
			color:#6b2020;
			margin-bottom: 15px
		}
	</style>
