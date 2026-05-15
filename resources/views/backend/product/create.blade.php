@extends('layouts.backend.master')
@section('title')
    Tambah Product Baru
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>@yield('title')</h4>
                </div>
                <div class="card-body">
                    <form class="form" action="{{ route('admin.product.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-5">
                                <img src="{{ asset('assets/no-image.jpeg') }}" class="img-fluid shadow-sm"
                                    style="border-radius: 14px;" id="blah">
                                <div class="mt-3">
                                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                                        <div>
                                            Gambar hanya JPG dan PNG serta Maks Size 5 MB
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="mb-3">
                                    <label class="form-label">Nama Produk</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        name="title" value="{{ old('title') }}" autocomplete="off">
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Masukan Harga Produk</label>
                                    <input type="number" class="form-control @error('harga') is-invalid @enderror"
                                        name="harga" value="{{ old('harga') }}" autocomplete="off">
                                    @error('harga')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Pilih Status Produk</label>
                                            <fieldset class="form-group">
                                                <select class="form-select @error('status') is-invalid @enderror"
                                                    id="basicSelect" name="status">
                                                    <option value="">-- Pilih --</option>
                                                    <option {{ old('status') == 'publish' ? 'selected' : '' }}
                                                        value="publish">
                                                        Tersedia</option>
                                                    <option {{ old('status') == 'draft' ? 'selected' : '' }} value="draft">
                                                        Sold Out
                                                    </option>
                                                </select>
                                                @error('status')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Pilih Kategori</label>
                                            <fieldset class="form-group">
                                                <select class="form-select @error('kategori') is-invalid @enderror"
                                                    id="kategori" name="kategori">
                                                </select>
                                                @error('kategori')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Masukan Thumbnail Product</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                                        name="image" autocomplete="off" id="imgInp" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="summernote" class="form-label">Deskripsi Produk</label>
                            <textarea class="form-control @error('desc') is-invalid @enderror" id="summernote" name="desc" rows="3">{!! old('desc') !!}</textarea>
                            @error('desc')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                            <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        imgInp.onchange = evt => {
            const [file] = imgInp.files
            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran gambar maksimal 5 MB.')
                    imgInp.value = ''
                    return
                }

                blah.src = URL.createObjectURL(file)
            }
        }
    </script>
    <script>
        $('#kategori').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Pilih --',
            ajax: {
                url: '{{ route('admin.product.create') }}',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                },
            }
        });
    </script>
    <script>
        $('#summernote').summernote({
            placeholder: 'Masukan Deskripsi lengkap produk',
            tabsize: 2,
            height: 120,
            toolbar: [
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['codeview']]
            ]
        });
    </script>
@endsection
