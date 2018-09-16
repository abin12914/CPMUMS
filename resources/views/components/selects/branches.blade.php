<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" style="width: 100%" tabindex="{{ $tabindex }}">
    <option value="">Select branch</option>
    @if(!empty($branchesCombo) && (count($branchesCombo) > 0))
        @foreach($branchesCombo as $branch)
            <option value="{{ $branch->id }}" {{ (old($selectName) == $branch->id || $selectedBranchId == $branch->id) ? 'selected' : '' }}>{{ $branch->name. " - ". $branch->place }}</option>
        @endforeach
    @endif
</select>