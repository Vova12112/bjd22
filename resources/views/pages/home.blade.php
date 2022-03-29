@extends('pages._common')
@section('title')
	Заголовок
@endsection

@section('content')

	<div class="content-row action js-popup-noscript" data-popup="change-password-popup" data-popup-class="user-profile-popup password-popup js-change-password-popup">
		<div class="row-wrapper d-flex-align-center">
			<h4 class="row-cell right row-text">
				CLICK HERE
			</h4>
		</div>
	</div>
	<noscript class="change-password-popup hidden" data-title="DAWDAWDAWDAWDD">
		<form class="js-password p-20">
			AWDASDWADWA
			<div class="mt-20 w-100 tar">
				<button class="btn primary-btn submit js-save" disabled>
					SAVE
				</button>
			</div>
		</form>
		<script type="application/javascript">
			$(document).ready(function () {
				"use strict";
				let
					$mainPopup = popup.getMainPopup(),
					$inputs = $mainPopup.find("input"),
					$currentPasswordInput = $mainPopup.find("#js-current-password"),
					$newPasswordInput = $mainPopup.find("#js-new-password"),
					$confirmPasswordInput = $mainPopup.find("#js-confirm-password"),
					$saveButton = $mainPopup.find(".js-save");

				$currentPasswordInput.focus();

				$inputs.on("keyup", function () {
					enableButton(
						$saveButton,
						(
							$currentPasswordInput.val() !== undefined && $currentPasswordInput.val() !== "" &&
							$newPasswordInput.val() !== undefined && $newPasswordInput.val() !== "" &&
							$confirmPasswordInput.val() !== undefined && $confirmPasswordInput.val() !== "" &&
							$newPasswordInput.val() === $confirmPasswordInput.val()
						)
					);
					clearInvalid($(this));
				});

				createSubmitHandler($saveButton);

			});
		</script>
	</noscript>
	<script type="text/javascript">
		$(document).ready(function () {
			"use strict";
			noScriptPopupHandlers();
			ajaxPopupHandlers(false);
		});
	</script>
@endsection

