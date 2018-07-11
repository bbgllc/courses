<h5>{!! trans('dotenv-editor::views.backup_title_one') !!}</h5>
<a href="{{ url(config('dotenveditor.route') . "/createbackup") }}" class="btn btn-primary">
	{!! trans('dotenv-editor::views.backup_create') !!}
</a>
<a href="{{ url(config("dotenveditor.route") . "/download") }}" class="btn btn-primary">
	{!! trans('dotenv-editor::views.backup_download') !!}
</a>

<hr />
<h5>{!! trans('dotenv-editor::views.backup_title_two') !!}</h5>
<p>
	{!! trans('dotenv-editor::views.backup_restore_text') !!}
</p>
<p class="text-danger">
	{!! trans('dotenv-editor::views.backup_restore_warning') !!}
</p>
@if(!$backups)
	<p class="text text-info">
		{!! trans('dotenv-editor::views.backup_no_backups') !!}
	</p>
@endif

@if($backups)
    <div class="table-responsive">
		<table class="table table-sm table-striped">
			<tr>
				<th>{!! trans('dotenv-editor::views.backup_table_nr') !!}</th>
				<th>{!! trans('dotenv-editor::views.backup_table_date') !!}</th>
				<th>{!! trans('dotenv-editor::views.backup_table_options') !!}</th>
			</tr>
			<?php $c = 1; ?>
			@foreach($backups as $backup)
				<tr>
					<td>{{ $c++ }}</td>
					<td>{{ $backup['formatted'] }}</td>
					<td>
						<!--
						<a href="javascript:;" @click="showBackupDetails('{{ $backup['unformatted'] }}', '{{ $backup['formatted'] }}')" title="{!! trans('dotenv-editor::views.backup_table_options_show') !!}">
							<span class="fa fa-search-plus"></span>
						</a>
						<a href="javascript:;" @click="restoreBackup({{ $backup['unformatted'] }})"
							title="{!! trans('dotenv-editor::views.backup_table_options_restore') !!}"
						>
						
							<span class="fa fa-refresh" title="{!! trans('dotenv-editor::views.backup_table_options_restore') !!}"></span>
						</a>
						-->
						<a href="{{ url(config("dotenveditor.route") . "/download/" . $backup['unformatted']) }}">
							<span class="fa fa-download" title="{!! trans('dotenv-editor::views.backup_table_options_download') !!}"></span>
						</a>
						<a href="{{ url(config("dotenveditor.route") . "/deletebackup/" . $backup["unformatted"]) }}" title="{!! trans('dotenv-editor::views.backup_table_options_delete') !!}">
							<span class="fa fa-trash"></span>
						</a>
					</td>
				</tr>
			@endforeach
		</table>
	</div>
@endif

@if($backups)
	{{-- Details Modal --}}
	<div class="modal fade" id="showDetails" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">{!! trans('dotenv-editor::views.backup_modal_title') !!}</h4>
				</div>
				<div class="modal-body">
					<table class="table table-striped">
						<tr>
							<th>{!! trans('dotenv-editor::views.backup_modal_key') !!}</th>
							<th>{!! trans('dotenv-editor::views.backup_modal_value') !!}</th>
						</tr>
						<tr v-for="entry in details">
							<td>@{{ entry.key }}</td>
							<td>@{{ entry.value }}</td>
						</tr>
					</table>
				</div>
				<div class="modal-footer">
					<a href="javascript:;" @click="restoreBackup(currentBackup.timestamp)"
					title="Stelle dieses Backup wieder her"
					class="btn btn-primary">
					{!! trans('dotenv-editor::views.backup_modal_restore') !!}
					</a>

					<button type="button" class="btn btn-default" data-dismiss="modal">
						{!! trans('dotenv-editor::views.backup_modal_close') !!}
					</button>

					<a href="{{ url(config("dotenveditor.route") . "/deletebackup/" . $backup["unformatted"]) }}" class="btn btn-danger">
						{!! trans('dotenv-editor::views.backup_modal_delete') !!}
					</a>
				</div>
			</div>
		</div>
	</div>
@endif