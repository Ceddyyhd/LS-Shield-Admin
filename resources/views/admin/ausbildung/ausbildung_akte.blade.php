@extends('layouts.vertical', ['title' => 'Verwaltung', 'subTitle' => 'Ausbildungen'])

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <h3 class="profile-username">{{ $ausbildung->name }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" href="#leitfaden" data-bs-toggle="tab">Leitfaden</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#leitfaden-edit" data-bs-toggle="tab">Leitfaden Bearbeiten</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="leitfaden">
                            {!! $ausbildung->content !!}
                        </div>

                        <div class="tab-pane" id="leitfaden-edit">
                        <form action="{{ route('admin.ausbildungen_akte.updateContent', $ausbildung->id) }}" method="POST">
                        @csrf
                                @method('PUT')
                                <div id="snow-editor" style="height: 300px;">
                                    {!! $ausbildung->content !!}
                                </div>
                                <input type="hidden" name="content" id="hiddenContent">
                                <button type="submit" class="btn btn-primary mt-3" onclick="setContent()">Speichern</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
$(document).ready(function() {
    var quill = new Quill('#snow-editor', {
        theme: 'snow',
        modules: {
            toolbar: {
                container: [
                    [{ 'font': [] }, { 'size': [] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'script': 'super' }, { 'script': 'sub' }],
                    [{ 'header': [false, 1, 2, 3, 4, 5, 6] }, 'blockquote', 'code-block'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }, { 'indent': '-1' }, { 'indent': '+1' }],
                    ['direction', { 'align': [] }],
                    ['link', 'image', 'video'],
                    ['clean']
                ],
                handlers: {
                    image: imageHandler
                }
            }
        }
    });

    function resizeImage(file) {
        return new Promise((resolve) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = (e) => {
                const img = new Image();
                img.src = e.target.result;
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    let width = img.width;
                    let height = img.height;
                    
                    if (width > height) {
                        if (width > 500) {
                            height = Math.round((height * 500) / width);
                            width = 500;
                        }
                    } else {
                        if (height > 500) {
                            width = Math.round((width * 500) / height);
                            height = 500;
                        }
                    }
                    
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);
                    
                    canvas.toBlob((blob) => {
                        resolve(new File([blob], file.name, {
                            type: 'image/jpeg',
                            lastModified: Date.now()
                        }));
                    }, 'image/jpeg', 0.8);
                };
            };
        });
    }

    function imageHandler() {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();

        input.onchange = async () => {
            const file = input.files[0];
            const resizedFile = await resizeImage(file);
            const formData = new FormData();
            formData.append('image', resizedFile);

            try {
                const response = await fetch('{{ route("admin.ausbildungen_akte.uploadImage") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });

                if (!response.ok) throw new Error('Upload failed');

                const data = await response.json();
                if (data.url) {
                    const range = quill.getSelection(true);
                    quill.insertEmbed(range.index, 'image', data.url);
                    quill.setSelection(range.index + 1);
                }
            } catch (error) {
                console.error('Upload error:', error);
                alert('Image upload failed');
            }
        };
    }

    $('form').on('submit', function(e) {
        e.preventDefault();
        var content = quill.root.innerHTML;
        $('#hiddenContent').val(content);
        this.submit();
    });
});
</script>
@endsection