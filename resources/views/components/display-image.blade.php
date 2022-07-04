@props(['data', 'fileName', 'required'])
@php
use Illuminate\Support\Str;
$imgFileName = ($fileName ?? false)
            ? $fileName
            : 'image';
$required = ($required ?? false)
            ? 'required'
            : '';
@endphp
@if($data && !Str::contains($data->image, 'text=Default'))
    <div class="p-image-sec">
        <div class="p-image-container">
            <img class="p-image" src="{{asset($data->$imgFileName)}}"/>
            <button type="button" class="close btn-img-clear" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <input type="hidden" class="existing_image" name="image_existing" value="1" />
        <input class="form-control form-file" type="file" id="formFile" name='{{$imgFileName}}'>
        <input value="{{asset($data->$imgFileName)}}" type="hidden" name='ex_img_path'>
    </div>
@else
    <input class="form-control" type="file" id="formFile" name="{{$imgFileName}}" {{$required}}>
@endif