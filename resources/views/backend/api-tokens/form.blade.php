<div class="row">
    <div class="col-12 col-sm-4 mb-3">
        <div class="form-group">
            <x-backend.fields.select
                name="user_id"
                :label="__('user_id')"
                :data="$data"
                :placeholder="__('Select an option')"
                required="required"

                select2
                select2_model="\\App\\Models\\User"
                select2_ajax_url="{{route('backend.users.index_list')}}"
            />
        </div>
    </div>

    <div class="col-12 col-sm-4 mb-3">
        <div class="form-group">
            <x-backend.fields.text
                name="name"
                :label="__('Token Name')"
                :placeholder="__('Enter token name')"
                required="true"
            />
        </div>
    </div>

    <div class="col-12 col-sm-4 mb-3">
        <div class="form-group">
            <x-backend.fields.text
                name="abilities"
                :label="__('Abilities (comma separated)')"
                :placeholder="__('Enter abilities')"
            />
        </div>
    </div>
</div>