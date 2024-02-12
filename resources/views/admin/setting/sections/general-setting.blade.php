<div class="tab-pane fade show active" id="general-setting" role="tabpanel" aria-labelledby="home-tab4">
    <div class="card">
        <div class="card-body border">
            <form action="{{ route('general-setting.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="">Site Name</label>
                    <input name="site_name" type="text" class="form-control"
                        value="{{ config('settings.site_name') }}">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Site Email</label>
                            <input name="site_email" type="text" class="form-control"
                                value="{{ config('settings.site_email') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Site Phone</label>
                            <input name="site_phone" type="text" class="form-control"
                                value="{{ config('settings.site_phone') }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Default Currency</label>
                            <select name="site_default_currency" id="" class="select2 form-control">
                                <option value="">Select</option>
                                @foreach (config('currencys.currency_list') as $currency)
                                    <option @selected(config('settings.site_default_currency') === $currency) value="{{ $currency }}">{{ $currency }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Include Tax in Prices</label>
                            <div class="form-check">
                                @php
                                    if (config('settings.site_inclusive_tax') == 1) {
                                        $checked = 'checked';
                                    } else {
                                        $checked = '';
                                    }
                                @endphp
                                <input class="form-check-input" name="site_inclusive_tax" type="checkbox"
                                    id="site_inclusive_tax" value="1" {{ $checked }}
                                    style="width: 20px; height: 20px;">
                                &nbsp; &nbsp;
                                <label class="form-check-label" for="site_inclusive_tax">
                                    <code>Price will include Taxes.</code>
                                </label>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Currency Icon</label>
                            <input name="site_currency_icon" type="text" class="form-control"
                                value="{{ config('settings.site_currency_icon') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Currency Icon Position</label>
                            <select name="site_currency_icon_position" id="" class="select2 form-control">
                                <option @selected(config('settings.site_currency_icon_position') === 'right') value="right">Right</option>
                                <option @selected(config('settings.site_currency_icon_position') === 'left') value="left">Left</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
</div>
