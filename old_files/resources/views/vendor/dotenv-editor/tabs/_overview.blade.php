<h5>{{ trans('dotenv-editor::views.overview_title') }}</h5>
<p>
	{!! trans('dotenv-editor::views.overview_text') !!}
</p>
<p>
	<a href="javascript:;" v-show="loadButton" class="btn btn-primary" @click="loadEnv">
	    {{ trans('dotenv-editor::views.overview_button') }}
	</a>
</p>
<div class="table-responsive" v-show="!loadButton">
    <table class="table table-sm table-striped" style="font-size: .8em;">
        <thead>
        <tr>
            <th>{{ trans('dotenv-editor::views.overview_table_key') }}</th>
			<th>{{ trans('dotenv-editor::views.overview_table_value') }}</th>
			<th>{{ trans('dotenv-editor::views.overview_table_options') }}</th>
        </tr>
        </thead>
        <tbody>
            <tr v-for="entry in entries">
                <td>@{{ entry.key }}</td>
    			<td v-if="!settings('enable_demo')">@{{ entry.value }}</td>
    			<td v-if="settings('enable_demo')">*****DEMO****</td>
    			<td>
    				<a href="javascript:;" @click="editEntry(entry)" class="btn btn-sm btn-info text-white"
    					title="{{ trans('dotenv-editor::views.overview_table_popover_edit') }}">
    					<span class="fa fa-pencil" aria-hidden="true"></span>
    				</a>
    				<a href="javascript:;" @click="modal(entry)" class="btn btn-sm btn-danger"
    					title="{{ trans('dotenv-editor::views.overview_table_popover_delete') }}">
    					<span class="fa fa-trash" aria-hidden="true"></span>
    				</a>
    			</td>
            </tr>
        </tbody>
    </table>
</div>


{{-- Modal delete --}}
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">@{{ deleteModal.title }}</h4>
			</div>
			<div class="modal-body">
				<p>{!! trans('dotenv-editor::views.overview_delete_modal_text') !!}</p>
				<p class="text text-warning">
					<strong>@{{ deleteModal.content }}</strong>
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">
					{!! trans('dotenv-editor::views.overview_delete_modal_no') !!}
				</button>
				<button type="button" class="btn btn-danger" @click="deleteEntry">
					{!! trans('dotenv-editor::views.overview_delete_modal_yes') !!}
				</button>
			</div>
		</div>
	</div>
</div>

{{-- Modal edit --}}
<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">{!! trans('dotenv-editor::views.overview_edit_modal_title') !!}</h4>
				<button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				
			</div>
			<div class="modal-body">
				<strong>{!! trans('dotenv-editor::views.overview_edit_modal_key') !!}:</strong> @{{ toEdit.key }}<br><br>
				<div class="form-group">
					<label for="editvalue">{!! trans('dotenv-editor::views.overview_edit_modal_value') !!}</label>
					<input type="text" v-model="toEdit.value" id="editvalue" class="form-control">
					<div class="text-danger">No spaces are allowed. If your value has multiple strings, 
						ensure that they are enclosed with double quotes. eg write <code>John Doe</code> as <code>"John Doe"</code>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">
					{!! trans('dotenv-editor::views.overview_edit_modal_quit') !!}
				</button>
				<button type="button" class="btn btn-primary" @click="updateEntry">
					{!! trans('dotenv-editor::views.overview_edit_modal_save') !!}
				</button>
			</div>
		</div>
	</div>
</div>



