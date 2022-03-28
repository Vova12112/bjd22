$(document).ready(function () {
	const
		$wrapper = $("#wrapper"),
		$actionsCol = $wrapper.find("#action-column"),
		$menu = $actionsCol.find(".menu"),
		$categories = $menu.find(".category"),
		$subcategories = $menu.find(".subcategory");

	$categories.on("click", function () {
		const $group = $(this).closest(".nav-group");
		if ($group.hasClass("active")) {
			$group.removeClass("active");
		} else {
			$categories.each(function () {
				$(this).closest(".nav-group").removeClass("active");
			})
			$group.addClass("active");
		}
	});
});