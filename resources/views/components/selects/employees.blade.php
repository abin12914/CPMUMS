<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" style="width: 100%" tabindex="{{ $tabindex }}">
    <option value="">Select employee</option>
    @if(!empty($employeesCombo) && (count($employeesCombo) > 0))
        @foreach($employeesCombo as $employee)
            <option value="{{ $employee->id }}" {{ (old($selectName) == $employee->id || $selectedEmployeeId == $employee->id) ? 'selected' : '' }}>{{ $employee->account->account_name }}</option>
        @endforeach
    @endif
</select>