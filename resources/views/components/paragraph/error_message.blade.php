@if(!empty($errors->first($fieldName)))
    <p style="color: red;" >{{$errors->first($fieldName)}}</p>
@endif