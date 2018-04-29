<h5>{!! trans('dotenv-editor::views.addnew_title') !!}</h5>
<p>
	{!! trans('dotenv-editor::views.addnew_text') !!}
</p>
<form @submit.prevent="addNew()">
	<div class="form-group">
		<label for="newkey">{!! trans('dotenv-editor::views.addnew_label_key') !!}</label>
		<input type="text" name="newkey" id="newkey" v-model="newEntry.key" class="form-control">
	</div>
	<div class="form-group">
		<label for="newvalue">{!! trans('dotenv-editor::views.addnew_label_value') !!}</label>
		<input type="text" name="newvalue" id="newvalue" v-model="newEntry.value" class="form-control">
	</div>
	<button class="btn btn-default" type="submit">
		{!! trans('dotenv-editor::views.addnew_button_add') !!}
	</button>
</form>