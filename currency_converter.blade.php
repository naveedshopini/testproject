<div class="widget">
    <div class="widget__inner">
        <h2 class="widget__title">{{ __('strings.frontend.general.currency_converter.title')}}</h2>
       @if($currency_heading==='yes')
       @include('front.includes.currency-converter.heading')
       @endif
        <div class="widget__content" style="height: 100%;max-height: 440px;"> 
            <form method="post" class="calc-form pt15">
                <div class="form-group">
                    <label for="fromCurr">{{ __('strings.frontend.general.currency_converter.from_currency')}}<sup>*</sup>
                    </label>
                    <select class="country-select_js" tabindex="1" id="fromCurr" data-placeholder="{{ __('strings.frontend.general.currency_converter.country_placeholder')}}">
                        
                    </select>
                </div>
                <div class="form-group">
                    <label for="toCurr">{{ __('strings.frontend.general.currency_converter.to_currency')}}<sup>*</sup>
                    </label>
                    <select class="country-select_js" tabindex="2" id="toCurr" data-placeholder="{{ __('strings.frontend.general.currency_converter.country_placeholder')}}">
                        
                    </select>
                </div>

                <div class="row d-flex align-items-end">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="amount">{{ __('strings.frontend.general.currency_converter.amount')}}<sup>*</sup>
                            </label>
                            <input type="text" maxlength="15" tabindex="3" id="amount" class="form-control mw220">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group d-flex justify-content-end">
                            <button type="submit" disabled id="exchange" tabindex="4" class="btn btn_type1 mw220">{{ __('strings.frontend.general.currency_converter.exchange')}}</button>
                        </div>
                    </div>
                </div>
                <div class="text-center spinner-cc" style="display: none;">
                    <div class="spinner-border" role="status"></div>
                </div>


                 <div class="form-group" id="ExchangeAmoutDiv" style="display: none;">
                    <div>{{ __('strings.frontend.general.currency_converter.amount_exchanged')}}</div>
                    <p id="ExchangeAmout" name="exchange_amout">
                    </p>
                </div>
                <div class="form-group" id="ExchangeAmoutErrorDiv" style="display: none;"> 
                    <p></p>
                </div>

            </form>
        </div>
    </div>
</div>