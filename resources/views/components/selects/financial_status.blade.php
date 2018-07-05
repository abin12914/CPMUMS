<select class="form-control select2" name="financial_status" id="financial_status" tabindex="{{ $tabindex }}" style="width: 100%;">
    <option value="" {{ empty(old('financial_status')) ? 'selected' : '' }}>Select status</option>
    <option value="0" {{ old('financial_status') == '0' ? 'selected' : '' }}>None (No pending transactions)</option>
    <option value="2" {{ old('financial_status') == '2' ? 'selected' : '' }}>Debitor (Account Holder Owe Company)</option>
    <option value="1" {{ old('financial_status') == '1' ? 'selected' : '' }}>Creditor (Company Owe Account Holder)</option>
</select>