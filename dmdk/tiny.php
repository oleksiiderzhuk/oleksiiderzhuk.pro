<script src='../js/tinymce/jquery.tinymce.min.js'></script>
	<script src='../js/tinymce/tinymce.min.js'></script>
	<script type="text/javascript">
		tinymce.init({
			selector: '#editablediv',
			width:800,
			height:877,
			language: 'uk',
			theme: 'modern',
			plugins: [
			'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
			'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
			'save table contextmenu directionality emoticons template paste textcolor'
			],
			content_css: 'css/content.css',
			toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor | fontsizeselect fontselect',
			setup:
			function(ed)
			{
				ed.on('init', function()
				{
					this.getDoc().body.style.fontSize = '14pt';
					this.getDoc().body.style.fontFamily = 'Times New Roman';
				});
				ed.on('init', function (ed) {
    d.target.editorCommands.execCommand("fontFamily", true, "Times New Roman");
});
			}

		});

</script>