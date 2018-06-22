<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" style="width: 100%" tabindex="{{ $tabindex }}">
    <option value="">Select account</option>
    @if(!empty($accountsCombo) && (count($accountsCombo) > 0))
        @foreach($accountsCombo as $account)
            @if(!$cashAccountFlag && $account->id == 1)
                @continue
            @endif
            <option value="{{ $account->id }}" {{ (old($selectName) == $account->id || $selectedAccountId == $account->id) ? 'selected' : '' }}>{{ $account->account_name }}</option>
        @endforeach
    @endif
</select>