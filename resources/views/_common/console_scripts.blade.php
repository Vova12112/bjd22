<script type="application/javascript">
	/* global document, $, jQuery */

	const
		loader = (function () {
			const $loader = $("#loader");
			return {
				show: function () {
					$loader.fadeIn();
				},
				stop: function () {
					$loader.fadeOut();
				}
			};
		}()),
		popup = (function () {
			const
				$mainPopup = $('#main-popup'),
				$additionalPopup = $('#additional-popup');

			let mainClass = undefined,
				additionalClass = undefined,
				isMainShowed = false,
				isAdditionalShowed = false,
				$mainTitle = $mainPopup.find('.popup-title'),
				$mainContent = $mainPopup.find('.popup-content'),
				$additionalTitle = $additionalPopup.find('.popup-title'),
				$additionalContent = $additionalPopup.find('.popup-content');

			function addDataToMain(name, value) {
				$mainPopup.data(name, value);
			}

			function removeDataFromMain(name = "all") {
				if (name === "all") {
					$mainPopup.removeData();
				} else {
					$mainPopup.removeData(name);
				}
			}

			function addDataToAdditional(name, value) {
				$additionalPopup.data(name, value);
			}

			function removeDataFromAdditional(name = "all") {
				if (name === "all") {
					$additionalPopup.removeData();
				} else {
					$additionalPopup.removeData(name);
				}
			}

			function hideMainPopup() {
				$mainPopup.hide();
				$mainContent.html("");
				$mainTitle.html("");
				isMainShowed = false;
				if (mainClass !== undefined) {
					$mainPopup.prop("class", "");
					mainClass = undefined;
				}
				if (isChanged) {
					clickToMenu(action);
					isChanged = false;
				}
				removeDataFromMain();
			}

			function hideAdditionalPopup() {
				$additionalPopup.hide();
				$additionalContent.html("");
				$additionalTitle.html("");
				isAdditionalShowed = false;
				if (additionalClass !== undefined) {
					$additionalPopup.removeClass(additionalClass);
					additionalClass = undefined;
				}
				removeDataFromAdditional();
			}

			function handlerCloseButton() {
				$('.popup-close-button span').unbind('click').on('click', function (event) {
					event.preventDefault();
					if (isAdditionalShowed) {
						hideAdditionalPopup()
					} else if (isMainShowed) {
						hideMainPopup();
					}
				});
			}

			function handlerCancelButton() {
				$('button.cancel-btn').unbind('click').on('click', function (event) {
					event.preventDefault();
					if (isAdditionalShowed) {
						hideAdditionalPopup()
					} else if (isMainShowed) {
						hideMainPopup();
					}
				});
			}

			function handlerSubmitButton() {
				$(document).unbind('keyup').on('keyup', function (event) {
					if (parseInt(event.keyCode) === 13) {
						let $saveBtn = $mainPopup.find("button.js-submit");
						if ($saveBtn.attr("disabled") !== "disabled") {
							$saveBtn.trigger("click");
						}
					}
				});
			}

			function handlerEscape() {
				$(document).unbind('keyup').on('keyup', function (event) {
					if (parseInt(event.keyCode) === 27) {
						if (isAdditionalShowed) {
							hideAdditionalPopup()
						} else if (isMainShowed) {
							hideMainPopup();
						}
					}
				});
			}

			return {
				getMainPopup: function () {
					return $mainPopup;
				},

				getAdditionalPopup: function () {
					return $additionalPopup;
				},

				showMain: function (html, popupClass = undefined, title = '') {
					if (popupClass !== undefined) {
						mainClass = popupClass;
						$mainPopup.addClass(mainClass);
					}
					isMainShowed = true;
					$mainTitle.html(title)
					$mainContent.html(html);
					handlerCloseButton();
					handlerCancelButton();
					handlerEscape();
					handlerSubmitButton();
					$mainPopup.show();
				},

				showAdditional: function (html, popupClass = undefined, title = '') {
					if (popupClass !== undefined) {
						additionalClass = popupClass;
						$additionalPopup.addClass(additionalClass);
					}
					isAdditionalShowed = true;
					$additionalTitle.html(title)
					$additionalContent.html(html);
					handlerCloseButton();
					handlerCancelButton();
					handlerEscape();
					handlerSubmitButton();
					$additionalPopup.show();
				},

				hide                    : function () {
					if (isAdditionalShowed) {
						hideAdditionalPopup();
					} else if (isMainShowed) {
						hideMainPopup();
					}
				},
				hideAll                 : function () {
					if (isAdditionalShowed) {
						hideAdditionalPopup();
					}
					if (isMainShowed) {
						hideMainPopup();
					}
				},
				addDataToMain           : function (name, value) {
					addDataToMain(name, value);
				},
				addDataToAdditional     : function (name, value) {
					addDataToAdditional(name, value);
				},
				removeDataFromMain      : function (name = "all") {
					removeDataFromAdditional(name);
				},
				removeDataFromAdditional: function (name = "all") {
					removeDataFromAdditional(name);
				},
				getDataMain             : function (name) {
					return $mainPopup.data(name);
				},
				getDataAdditional       : function (name) {
					return $additionalPopup.data(name);
				}
			};
		}()),
		notification = (function () {
			const $notificationSection = $('#notification'),
				$notificationWrapper = $notificationSection.find('.notification-wrapper'),
				$contentSection = $notificationSection.find('.message');
			return {
				show: function (content, type) {
					$contentSection.removeClass("success error");
					$contentSection.html(content).addClass(type);
					$notificationWrapper.slideDown();
					setTimeout(function () {
						notification.hide();
					}, 7000);
				},
				hide: function () {
					$notificationWrapper.slideUp();
					$notificationSection.css('z-index', '');
				}
			};
		}());

	let
		isChanged = false;

	/*------------------------  REQUEST ---------------------------*/
	function ajaxRequest(action, method, dataType, data = {}, successFunction = undefined, isStopLoader = true, validatorElement = "#console", isShowLoader = true) {
		if (method !== 'GET') {
			data._token = $('input[name=_token]').val();
		}
		if (isShowLoader) {
			loader.show();
		}
		return $.ajax({
			type    : method,
			url     : action,
			dataType: dataType,
			data    : data,
			success : function (response) {
				if (response.ack === 'success') {
					if ("message" in response) {
						notification.show(response.message, 'success');
					}
					successFunction(response);
				} else if (response.ack === 'reload') {
					if ("message" in response) {
						notification.show(response.message, "message-type" in response ? response.message - type : 'success');
					}
					location.reload();
				} else if (response.ack === 'redirect') {
					window.location.href = response.url
				} else if (response.ack === 'fail') {
					if ("message" in response) {
						loader.stop();
						notification.show(response.message, 'error');
					}
					if ("validator" in response) {
						$.each(response.validator, function (key, value) {
							$(validatorElement + " input[name='" + key + "'], " + validatorElement + " select[name='" + key + "']").addClass("invalid");
						});
					}
				}
				if (isStopLoader) {
					loader.stop();
				}
			},
			error   : function (xhr, textStatus, thrownError) {
				//location.reload();
			}
		});
	}

	function getElementInputs($element) {
		let exit = {};
		$($element).find("input, select").each(function () {
			exit[$(this).prop("name")] = $(this).val();
		});
		return exit;
	}

	function clearInvalid($element) {
		$element.removeClass("invalid");
	}

	/*-----------   FILES  ----------*/
	function getBase64(file) {
		return new Promise((resolve, reject) => {
			const reader = new FileReader();
			reader.readAsDataURL(file);
			reader.onload = () => resolve(reader.result);
			reader.onerror = error => reject(error);
		});
	}

	/*-----------   SECTION  ----------*/
	function showSection($section, condition) {
		condition ? showSelector($section) : hideSelector($section);
	}

	function redirectHandlers($section) {
		$section.find(".js-redirect").unbind("on").on("click", function () {
			loader.show();
			setTimeout(() => {
				window.location.replace($(this).data("route"));
			}, 1000);
		});
	}

	function noScriptPopupHandlers() {
		$(".js-popup-noscript").unbind("click").on("click", function (event) {
			event.preventDefault();
			loader.show();
			setTimeout(() => {
				const $popupContent = $("noscript." + $(this).data("popup").trim()),
					isAdditional = $(this).data("is-additional") ?? false;
				if (isAdditional) {
					popup.showAdditional($popupContent.text(), $(this).data("popup-class").trim(), $popupContent.data("title"));
				} else {
					popup.showMain($popupContent.text(), $(this).data("popup-class").trim(), $popupContent.data("title"));
				}
				loader.stop();
			}, 400);
		});
	}

	function ajaxPopupHandlers(isStopLoader = true) {
		$(".js-get-popup").unbind("click").on("click", function (event) {
			event.preventDefault();
			ajaxPopupRequest($(this).data("route"), {}, isStopLoader);
		});
	}

	function ajaxPopupRequest(route, args, isStopLoader = true) {
		ajaxRequest(
			route,
			"POST",
			"json",
			args,
			function (response) {
				popup.showMain(response.html, response.classes, response.title);
			},
			isStopLoader
		);
	}

	function reformHandlers() {
		noScriptPopupHandlers();
		ajaxPopupHandlers();
	}

	function createSubmitHandler($button, formSelector = "#main-popup", triggers = []) {
		$button.unbind("click").on("click", function (event) {
			event.preventDefault();
			if ($(this).attr("disabled") !== "disabled") {
				ajaxRequest(
					$(this).data("route"),
					"POST",
					"json",
					getElementInputs($(formSelector)),
					function () {
						if (triggers.length === 0) {
							isChanged = true;
						} else {
							isChanged = false;
							for (const index in triggers) {
								$(formSelector).trigger(triggers[index]);
							}
						}
						popup.hideAll();
					},
					triggers.length === 0,
					formSelector
				);
			}
		});
	}

	function clearNotNumber($input, isFloat = false, isNegative = false) {
		let numbers = isFloat ? "0123456789." : "0123456789",
			value = $input.val(),
			result = "";
		numbers += isNegative ? '-' : '';
		for (let i = 0; i < value.length; i++) {
			if (numbers.includes(value[i])) {
				result += value[i];
			}
		}
		$input.val(result);
	}

	function clickToMenu(action) {
		$("div.menu-item[data-route='" + action + "']").trigger("click");
	}
</script>