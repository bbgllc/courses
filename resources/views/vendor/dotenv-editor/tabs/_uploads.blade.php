<h5>{!! trans('dotenv-editor::views.upload_title') !!}</h5>
<p>
	{!! trans('dotenv-editor::views.upload_text') !!}<br>
	<span class="text text-warning">
		{!! trans('dotenv-editor::views.upload_warning') !!}
	</span>
</p>
<form method="post" action="{{ url(config("dotenveditor.route") . "/upload") }}" enctype="multipart/form-data">
	<div class="form-group">
		<label for="backup">{!! trans('dotenv-editor::views.upload_label') !!}</label>
		<input type="file" name="backup">
	</div>
	<button type="submit" class="btn btn-primary" title="Ein Backup von deinem Computer hochladen">
		{!! trans('dotenv-editor::views.upload_button') !!}
	</button>
</form>