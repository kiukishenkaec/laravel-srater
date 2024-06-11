<div>
    <div class="row mt-4">
        <div class="col">
            <input class="form-control my-2" type="text" placeholder=" Search" wire:model.live="searchTerm" />

            <div class="table-responsive">
                <table class="table-hover table-responsive-sm table" wire:loading.class="table-secondary">
                    <thead>
                        <tr>
                            <th>{{ __('labels.backend.users.fields.name') }}</th>

                            <th class="text-end">{{ __('labels.backend.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($api_tokens as $api_token)
                            <tr>
                                <td>
                                    <strong>
                                        <a href="{{ route('backend.api-token.show', $api_token->id) }}">
                                            {{ $api_token->name }}
                                        </a>
                                    </strong>
                                </td>

                                <td class="text-end">
                                    <a class="btn btn-success btn-sm mt-1" data-toggle="tooltip"
                                        href="{{ route('backend.api-tokens.show', $api_token) }}"
                                        title="{{ __('labels.backend.show') }}"><i
                                            class="fas fa-desktop fa-fw"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-7">
            <div class="float-left">
                {!! $api_tokens->total() !!} {{ __('labels.backend.total') }}
            </div>
        </div>
        <div class="col-5">
            <div class="float-end">
                {!! $api_tokens->links() !!}
            </div>
        </div>
    </div>
</div>
