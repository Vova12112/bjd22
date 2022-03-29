<script type="application/javascript">
	function isButtonDisabled($button) {
		return $button.attr("disabled") === "disabled";
	}
	function enableButton($button, condition) {
		if (condition) {
			if (isButtonDisabled($button)) {
				$button.removeAttr("disabled", "");
			}
		} else {
			if (!isButtonDisabled($button)) {
				$button.attr("disabled", "disabled");
			}
		}
	}

	function isErrorFor($selector) {
		return $selector.hasClass("error");
	}

	function resetErrorFor($selector) {
		if (isErrorFor($selector)) {
			$selector.removeClass("error");
		}
	}

	function showSelector($selector) {
		$selector.removeClass("hidden");
	}

	function hideSelector($selector) {
		$selector.addClass("hidden");
	}

	function toolTipsRender($parent = $("#console")) {
		const tooltips = $parent.find(".tooltip-hover");
		tooltips.each(function () {
			toolTip($(this));
		});
		if (!$("#console").hasClass("mini")) {
			$("#console .side-bar .tooltip-body").remove();
		}
	}

	function toolTip($element) {
		if ($element.data("rendered") === "true") {
			$element.next(".tooltip-body").remove();
		}
		let
			marginLeft,
			marginTop,
			tooltipWidth,
			tooltipHeight,
			elementWidth,
			elementHeight,
			elementPositionLeft,
			elementPositionTop,
			side = $element.data("tooltip-side") ?? "top",
			additionalClass = $element.data("tooltip-class") ?? "",
			$tooltip = $("<div>").addClass("tooltip-body").addClass(side + " " + additionalClass).text($element.data("tooltip-text"));
		$element.after($tooltip);
		tooltipWidth = $tooltip.outerWidth();
		tooltipHeight = $tooltip.outerHeight();
		elementWidth = $element.outerWidth();
		elementHeight = $element.outerHeight();
		elementPositionLeft = $element.position().left;
		elementPositionTop = $element.position().top;

		$tooltip.data("init-top", elementPositionTop).data("init-left", elementPositionLeft);
		$tooltip.css("top", elementPositionTop).css("left", elementPositionLeft);
		switch (side) {
			case "bottom":
				if (elementWidth === tooltipWidth) {
					marginLeft = 0;
				} else {
					marginLeft = (elementWidth > tooltipWidth)
						? Math.round(elementWidth * 0.5 - tooltipWidth * 0.5)
						: Math.round(elementPositionLeft - elementWidth * 0.5 - tooltipWidth * 0.5);
				}
				marginTop = 6;
				break;
			case "left":
				marginLeft = -tooltipWidth - 6;
				marginTop = -tooltipHeight * 0.5 - elementHeight * 0.5;
				break;
			case "right":
				marginLeft = elementWidth + 6;
				marginTop = Math.round((elementHeight - tooltipHeight) * 0.5);
				break;
			default:
				marginLeft = Math.round(elementWidth * 0.5 - tooltipWidth * 0.5);
				marginTop = -(tooltipHeight + 6);
				break;
		}
		$tooltip.css("margin-top", marginTop).css("margin-left", marginLeft);
		$element.data("rendered", "true");
	}

</script>