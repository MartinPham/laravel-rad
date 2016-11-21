<html>
	<body>
		<h1>Reindirizzamento..</h1>
		<a href="{{ $url }}">Clicca qui se non vieni reindirizzato correttamente</a>
		<script>
			setTimeout(function(){ location = '{{ $url }}'}, 100);
		</script>
	</body>
</html>