<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8'/>
		<title>Colorbox Examples</title>
		
		<link rel="stylesheet" href="assets/plugins/colorbox-master/example1/colorbox.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="assets/plugins/colorbox-master/jquery.colorbox.js"></script>
		<script>
			$(document).ready(function(){
				$('.retina').colorbox({rel:'group5', transition:'none', retinaImage:true, retinaUrl:true});
			});
		</script>
	</head>
	<body>
		
		<a class="retina" href="test.php" title="">view</a>
		
	</body>
</html>