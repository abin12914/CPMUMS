<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" style="width: 100%" tabindex="{{ $tabindex }}">
    <option value="">Select product</option>
    @if(!empty($productsCombo) && (count($productsCombo) > 0))
        @foreach($productsCombo as $product)
            <option value="{{ $product->id }}" {{ (old($selectName) == $product->id || $selectedProductId == $product->id) ? 'selected' : '' }}>{{ $product->name }}</option>
        @endforeach
    @endif
</select>