<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" style="width: 100%" tabindex="{{ $tabindex }}">
    <option value="">Select material</option>
    @if(!empty($materialsCombo) && (count($materialsCombo) > 0))
        @foreach($materialsCombo as $material)
            <option value="{{ $material->id }}" {{ (old($selectName) == $material->id || $selectedMaterialId == $material->id) ? 'selected' : '' }}>{{ $material->name }}</option>
        @endforeach
    @endif
</select>