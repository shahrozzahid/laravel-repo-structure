@props(['id', 'src' ,'width' => '40', 'height' => '40',])


<div class="mt-2">
    <img id="preview_{{ $id ?? 'profile_image' }}"
         src="{{ $src ?? asset('images/default.jpg') }}"
         alt="Preview"
         class="rounded-full border p-1"
         style="width: {{$width}}px; height: {{$height}}px; object-fit: cover; }}">
</div>

@once
<script>
    function previewImage(event, id) {
        let input = event.target;
        let preview = document.getElementById('preview_' + id);

        console.log(input.files[0]);
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endonce
