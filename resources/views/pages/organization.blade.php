@extends('pages._common')
@section('title')
	Відомості про організацію
@endsection

@section('content')
	<div class="organization-wrapper conteiner">
		<div class="orgName">
			<p style="width: 140px">Назва фірми:</p>
			<input type="text" class="name" name="name" value="{{ isset($organization) ? $organization->getName() : '' }}">
		</div>
		<div class="orgName">
			<p style="width: 140px">Адреса:</p>
			<input type="text" class="name2" name="address" value="{{ isset($organization) ? $organization->getAddress() : '' }}">
		</div>
		<div id="orgbtn">
			<button class="btn js-save-organization">Зберегти</button>
		</div>
		<script type="text/javascript">
			$(document).ready(function () {
				const
					$organizationWrapper = $(".organization-wrapper"),
					$saveOrganizationBtn = $organizationWrapper.find(".js-save-organization")
				;

				$saveOrganizationBtn.on("click", function () {
					ajaxRequest(
						"{{ route('organization.save') }}",
						"POST",
						"json",
						{
							"_token" : $organizationWrapper.find("input[name=_token]").val(),
							"name"   : $organizationWrapper.find("input[name=name]").val(),
							"address": $organizationWrapper.find("input[name=address]").val(),
						}
					)
				});

			});
		</script>
	</div>
@endsection