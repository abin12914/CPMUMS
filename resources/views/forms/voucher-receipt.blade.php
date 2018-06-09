<div class="form-group">
    <label class="col-md-3 control-label"><b style="color: red;">* </b> Transaction Type : </label>
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-6 {{ !empty($errors->first('transaction_type')) ? 'has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon">
                        <input type="radio" name="transaction_type" class="transaction_type" id="transaction_type_debit" value="1" {{ empty(old('transaction_type')) || old('transaction_type') == '1' ? 'checked' : ''}} tabindex="1">
                    </span>
                    <label for="transaction_type_debit" class="form-control" tabindex="9">Debit / Reciept</label>
                </div>
                @if(!empty($errors->first('transaction_type')))
                    <p style="color: red;" >{{$errors->first('transaction_type')}}</p>
                @endif
            </div>
            <div class="col-md-6 {{ !empty($errors->first('transaction_type')) ? 'has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon">
                        <input type="radio" name="transaction_type" class="transaction_type" id="transaction_type_credit" value="2" {{ old('transaction_type') == '2' ? 'checked' : ''}} tabindex="2">
                    </span>
                    <label for="transaction_type_credit" class="form-control">Credit / Voucher</label>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-md-3 control-label">
        <b style="color: red;">* </b>
        <b id="account_label">{{ (empty(old('transaction_type')) || old('transaction_type') == 1) ? "Giver / From " : "Reciever / To " }}- Account :</b>
    </label>
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-6 {{ !empty($errors->first('voucher_reciept_account_id')) ? 'has-error' : '' }}">
                <select class="form-control select2" name="voucher_reciept_account_id" id="voucher_reciept_account_id" style="width: 100%;" tabindex="3">
                    <option value="" {{ empty(old('voucher_reciept_account_id')) ? 'selected' : '' }}>Select account</option>
                    @if(!empty($accounts))
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ (old('voucher_reciept_account_id') == $account->id) ? 'selected' : '' }}>{{ $account->account_name }}</option>
                        @endforeach
                    @endif
                </select>
                @if(!empty($errors->first('voucher_reciept_account_id')))
                    <p style="color: red;" >{{$errors->first('voucher_reciept_account_id')}}</p>
                @endif
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control decimal_number_only datepicker_reg" name="date" id="date" placeholder="Transaction date" value="{{ old('date') }}" tabindex="4">
                @if(!empty($errors->first('date')))
                    <p style="color: red;" >{{$errors->first('date')}}</p>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <label for="description" class="col-md-3 control-label">Description : </label>
    <div class="col-md-9 {{ !empty($errors->first('description')) ? 'has-error' : '' }}">
        @if(!empty(old('description')))
            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Truck Description" style="resize: none;" tabindex="5">{{ old('description') }}</textarea>
        @else
            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Truck Description" style="resize: none;" tabindex="5"></textarea>
        @endif
        @if(!empty($errors->first('description')))
            <p style="color: red;" >{{$errors->first('description')}}</p>
        @endif
    </div>
</div>
<div class="form-group">
    <label class="col-md-3 control-label"><b style="color: red;">* </b> Amount : </label>
    <div class="col-md-9 {{ !empty($errors->first('amount')) ? 'has-error' : '' }}">
        <input type="text" class="form-control decimal_number_only" name="amount" id="amount" placeholder="Transaction amount" value="{{ old('amount') }}" maxlength="6" tabindex="6">
        @if(!empty($errors->first('amount')))
            <p style="color: red;" >{{$errors->first('amount')}}</p>
        @endif
    </div>
</div>