@extends('dashboard.body.main')

@section('specificpagescripts')
    <script src="{{ asset('assets/js/img-preview.js') }}"></script>
@endsection

@section('content')
    <!-- BEGIN: Header -->
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
                            Add Product
                        </h1>
                    </div>
                </div>

                <nav class="mt-4 rounded" aria-label="breadcrumb">
                    <ol class="breadcrumb px-3 py-2 rounded mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
    </header>
    <!-- END: Header -->

    <!-- BEGIN: Main Page Content -->
    <div class="container-xl px-2 mt-n10">
        <form action="{{ route('add-product-sku',[$products['id']]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-xl-4">
                    <!-- Product image card-->
                    <div class="card mb-4 mb-xl-0">
                        <div class="card-header">Product Image</div>
                        <div class="card-body text-center">
                            <!-- Product image -->
                            <img class="img-account-profile mb-2" src="{{ asset('assets/img/products/default.webp') }}" alt="" id="image-preview" />
                            <!-- Product image help block -->
                            <div class="small font-italic text-muted mb-2">JPG or PNG no larger than 2 MB</div>
                            <!-- Product image input -->
                            <input class="form-control form-control-solid mb-2 @error('product_image') is-invalid @enderror" type="file"  id="image" name="product_image" accept="image/*" onchange="previewImage();">
                            @error('product_image')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-xl-8">
                    <!-- BEGIN: Product Details -->
                    <div class="card mb-4">
                        <div class="card-header">
                           Add Product Skus
                        </div>
                        <div class="card-body">
                            <!-- Form Group (product name) -->
                            <div class="mb-3">
                                @error('product_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            @for ($i = 0; $i < $products['stock']; $i++)
                                <div class="mb-3">
                                    <label class="small mb-1" for="product_name">Product Sku <span class="text-danger">*</span></label>
                                    <input class="form-control form-control-solid" id="product_name" name="product_sku[]" type="text" placeholder="" value="" autocomplete="off">
                                </div>
                            @endfor

                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">
                                    Submit
                            </button>
                        </div>
                    </div>
                    <!-- END: Product Details -->
                </div>
            </div>
        </form>
    </div>
    <!-- END: Main Page Content -->
@endsection
