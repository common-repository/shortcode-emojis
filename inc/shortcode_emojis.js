jQuery(document).ready(function($) {
	$("select")
	  .change(function () {
		  var str = "";
		  $("select option:selected").each(function () {
			  str = $(this).val() + " ";
		  });
		  $("div#selected_sce").attr("class", "sce_emoji-" + str);
	  })
	  .change();
});
