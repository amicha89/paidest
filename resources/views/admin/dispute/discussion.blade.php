@php
	$extensions = json_encode(getFileExtensions(1));
@endphp

@extends('admin.layouts.master')
@section('title', __('Disputes'))

@section('page_content')
	<div id="discussion-reply">
		<div class="box box-default">
			<div class="box-body">
				<div class="d-flex justify-content-between">
					<div>
						<div class="top-bar-title padding-bottom pull-left">{{ __('Dispute') }}</div>
					</div>

					<div>
						<select class="form-control" name="status" id="status">
							<option value="Open" <?= ($dispute->status == 'Open') ? 'selected' : '' ?>>{{ __('Open') }}</option>
							<option value="Solve" <?= ($dispute->status == 'Solve') ? 'selected' : '' ?>>{{ __('Solve') }}</option>
							<option value="Closed" <?= ($dispute->status == 'Closed') ? 'selected' : '' ?>>{{ __('Closed') }}</option>
						</select>
						<input type="hidden" name="id" value="{{$dispute->id}}" id="id">
					</div>
				</div>
			</div>
		</div>

		<div class="box">
			<div class="box-body">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="col-md-12">
							<div class="panel panel-default">
								<div class="panel-body">
									<div class="form-group">
										<label class="control-label col-sm-3">{{ __('Title') }}</label>
										<div class="col-sm-9">
											<p>{{ $dispute->title  }}</p>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-7">
							<div class="form-group">
								<label class="control-label col-sm-3">{{ __('Transaction ID') }}</label>
								<div class="col-sm-9">
									<p>{{ (isset($dispute->transaction)) ? $dispute->transaction->uuid : ''  }}</p>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-sm-3">{{ __('Status') }}</label>
								<div class="col-sm-9">
									<p>
										@php
											echo getStatusLabel($dispute->status);
										@endphp
									</p>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-sm-3">{{ __('Date') }}</label>
								<div class="col-sm-9">
									<p>{{ dateFormat($dispute->created_at) }}</p>
								</div>
							</div>
						</div>

						<div class="col-md-5">
							<div class="form-group">
								<label class="control-label col-sm-4">{{ __('Claimant') }}</label>
								<div class="col-sm-7">
									<p>{{ isset($dispute->claimant) ? $dispute->claimant->first_name .' '.$dispute->claimant->last_name :"-" }}</p>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-sm-4">{{ __('Defendant') }}</label>
								<div class="col-sm-7">
									<p>{{ isset($dispute->defendant) ? $dispute->defendant->first_name .' '.$dispute->defendant->last_name :"-" }}</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="box">
			<div class="box-body">
				<form action="{{url(\Config::get('adminPrefix').'/dispute/reply')}}" id="reply" method="post" enctype="multipart/form-data">
					{{ csrf_field() }}

					<div class="form-group col-sm-12 mt-10">
						<label for="email">{{ __('Reply') }}</label>
						<input type="hidden" name="dispute_id" value="{{ $dispute->id }}">
						<textarea name="description" id="description" class="form-control"></textarea>

						@if($errors->has('description'))
							<span class="error">
								{{ $errors->first('description') }}
							</span>
						@endif
					</div>

					<div class="form-group col-md-3">
						<label class="control-label" for="exampleInputFile">{{ __('File') }}</label>
						<input class="form-controls" type="file" name="file" id="file">
						@if($errors->has('file'))
							<span class="error">
								{{ $errors->first('file') }}
							</span>
						@endif
					</div>

					<div class="form-group col-sm-12 text-right">
						<button type="submit" class="btn btn-theme" id="dispute-reply"><i class="fa fa-spinner fa-spin" style="display: none;"></i> <span id="dispute-reply-text">{{ __('Submit') }}</span></button>
					</div>
					<div class="clearfix"></div>
				</form>
			</div>
		</div>

		<!-- Claimant Description - starts -->
		<div class="box">
			<div class="box-body">
				<div class="well well-sm">
					<div class="media">
						<div class="media-left">
							@if(!empty($dispute->claimant->picture))
								<?php
									$claimantAvatar = $dispute->claimant->picture;
								?>
							<img src="{{ url('public/user_dashboard/profile/' . $claimantAvatar) }}" class="media-object" style="width:60px">
							@else
								<img src="{{ url('public/user_dashboard/images/avatar.jpg') }}" class="media-object" style="width:60px">
							@endif
						</div>

						<div class="media-body">
							<h4><a href="{{ url(\Config::get('adminPrefix').'/admin-user/edit/'. $dispute->claimant->id)}}">{{$dispute->claimant->first_name .' '.$dispute->claimant->last_name}}</a> <small><i>{{ dateFormat($dispute->created_at) }}</i></small> &nbsp;
							</h4>
							{!! $dispute->description !!}
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Claimant Description - ends -->


		@if($dispute->disputeDiscussions->count() > 0)
			<div class="box">
				<div class="box-body">
					@foreach($dispute->disputeDiscussions as $result)
						@if($result->type == 'User' )
							<div class="well well-sm">
								<div class="media">
								<div class="media-left">

									@if(!empty($result->user->picture))
										<?php
											$userAvatar = $result->user->picture;
										?>
										<img src='{{url("public/user_dashboard/profile/$userAvatar")}}' class="media-object" style="width:60px">
									@else
										<img src="{{ url('public/user_dashboard/images/avatar.jpg') }}" class="media-object" style="width:60px">
									@endif

								</div>
									<div class="media-body">
									<h4><a href="{{ url(\Config::get('adminPrefix').'/users/edit/'. $result->user->id)}}">{{$result->user->first_name.' '.$result->user->last_name}}</a> <small><i>{{ dateFormat($result->created_at) }}</i></small> &nbsp;
										</h4>
									<p>{!! $result->message !!}</p>

							@if($result->file)
							----------------<br>
							<?php
								$str_arr = explode('_', $result->file);
								$str_position = strlen($str_arr[0])+1;
								$file_name = substr($result->file,$str_position);
							?>
							<h5>
							<a class="text-info" href="{{ url(\Config::get('adminPrefix').'/dispute/download', $result->file) }}"><i class="fa fa-download"></i> {{$file_name}}
							</a>
						</h5>
							@endif
									</div>
								</div>
							</div>

						@else
							<div class="well well-sm">
								<div class="media">

									<div class="media-left">
										@if(!empty($result->admin->picture))
											<?php
											$adminAvatar = $result->admin->picture;
											?>
										<img src='{{url("public/uploads/userPic/$adminAvatar")}}' class="media-object" style="width:60px">
										@else
											<img src="{{url('public/images/avatar.png')}}" class="media-object" style="width:60px">
										@endif
									</div>

									<div class="media-body">
									<h4><a href="{{ url(\Config::get('adminPrefix').'/admin-user/edit/'. $result->admin->id)}}">{{$result->admin->first_name.' '.$result->admin->last_name}}</a> <small><i>{{ dateFormat($result->created_at) }}</i></small> &nbsp;
									</h4>

									<p>{!! $result->message !!}</p>

										@if($result->file)
										----------------<br>
											<?php
												$str_arr = explode('_', $result->file);
												$str_position = strlen($str_arr[0])+1;
												$file_name = substr($result->file,$str_position);
											?>
											<h5>
												<a class="text-info" href="{{ url(\Config::get('adminPrefix').'/dispute/download', $result->file) }}">
													<i class="fa fa-download"></i> {{$file_name}}
												</a>
											</h5>
										@endif
									</div>
								</div>
							</div>
						@endif
					@endforeach
				</div>
			</div>
		@endif
	</div>
@endsection


@push('extra_body_scripts')

<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/dist/js/jquery-validation-1.17.0/dist/additional-methods.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
	'use strict';
	var extensions = JSON.parse(@json($extensions));
	var extensionsValidationRule = extensions.join('|');
	var extensionsValidation = extensions.join(', ');
	var errorMessage = '{{ __("Please select (:x) file.") }}';
	var extensionsValidationMessage = errorMessage.replace(':x', extensionsValidation);
	var submittingText = '{{ __("Submitting...") }}';
	var statusChangeText = '{{ __("Dispute discussion :x successfully done.") }}'
</script>
<script src="{{ asset('public/dist/js/admin_custom.min.js') }}" type="text/javascript"></script>
@endpush