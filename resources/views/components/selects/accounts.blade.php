<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" style="width: 100%" tabindex="{{ $tabindex }}">
    <option value="">Select account</option>
    @if(!empty($nonAccountFlag))
        <option value="-1" {{ old($selectName) == '-1' ? 'selected' : '' }}>Short Term Credit Without Account</option>
    @endif
    @if(!empty($accountsCombo) && (count($accountsCombo) > 0))
        @foreach($accountsCombo as $account)
            @if(!$cashAccountFlag && $account->type != 3) {{-- type != 3 means not personal account --}}
                @continue
            @endif
            @if($activeFlag && $account->status != 1)
                @continue
            @endif
            <option value="{{ $account->id }}" {{ (old($selectName) == $account->id || $selectedAccountId == $account->id) ? 'selected' : '' }} data-phone="{{ $account->phone }}">
                {{ $account->account_name. ((($account->status != 1 || config('settings.display_phone_flag')) && $account->type == 3) ? ' - '. $account->phone : '')  }}
            </option>
        @endforeach
    @endif
</select>