@extends('dashboard.body.main')

@section('content')
    <!-- BEGIN: Header -->
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
                            Add Purchase
                        </h1>
                    </div>
                </div>

                <nav class="mt-4 rounded" aria-label="breadcrumb">
                    <ol class="breadcrumb px-3 py-2 rounded mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Purchases</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
    </header>
    <!-- END: Header -->

    <!-- BEGIN: Main Page Content -->
    <div class="container-xl px-2 mt-n10">
        <form action="{{ route('purchases.storePurchase') }}" method="POST">
            @csrf
            <div class="row">

                <div class="col-xl-12">
                    <!-- BEGIN: Product List -->
                    <div class="card mb-4">
                        <div class="card-header">
                            Add Product Codes
                        </div>
                        <div class="card-body">
                          <div class="row">
                              <div class="col-6">
                                  <form action="{{route('add-product-sku',[$pDetails['product_id']])}}" method="POST">
                                      {{csrf_field()}}
                                      @for($i=0; $i <= $pDetails['quantity']; $i++)
                                          <div class="mb-3">
                                              <label>Add Sku</label>
                                              <input type="text" name="product_sku[]" id="total_amount" class="form-control total_amount">
                                          </div>
                                      @endfor
                                      <div>
                                          <button class="btn btn-primary" type="submit">
                                              Submit
                                          </button>
                                      </div>
                                  </form>
                              </div>
                          </div>
                        </div>
                    </div>
                    <!-- END: Product List -->
                </div>
            </div>
        </form>
    </div>
    <!-- END: Main Page Content -->
@endsection

@section('specificpagescripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ asset('assets/js/handlebars.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js" ></script>

    <script id="document-template" type="text/x-handlebars-template">
        <tr class="delete_add_more_item" id="delete_add_more_item">
            <td>
                <input type="hidden" name="product_id[]" value="@{{product_id}}" required>
                @{{product_name}}
            </td>

            <td>
                <input type="number" min="1" class="form-control quantity" name="quantity[]" value="" required>
            </td>

            <td>
                <input type="number" class="form-control unitcost" name="unitcost[]" value="" required>
            </td>

            <td>
                <input type="number" class="form-control total" name="total[]" value="0" readonly>
            </td>

            <td>
                <button class="btn btn-danger removeEventMore" type="button">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </td>
        </tr>
    </script>

    <script type="text/javascript">
        $(document).ready(function(){
            $(document).on("click",".addEventMore", function() {
                var product_id = $('#product_id').val();
                var product_name = $('#product_id').find('option:selected').text();

                if(product_id == ''){
                    $.notify("Product Field is Required" ,  {globalPosition: 'top right', className:'error' });
                    return false;
                }

                var source = $("#document-template").html();
                var tamplate = Handlebars.compile(source);
                var data = {
                    product_id:product_id,
                    product_name:product_name

                };
                var html = tamplate(data);
                $("#addRow").append(html);
            });

            $(document).on("click",".removeEventMore",function(event){
                $(this).closest(".delete_add_more_item").remove();
                totalAmountPrice();
            });

            $(document).on('keyup click','.unitcost,.quantity', function(){
                var unitcost = $(this).closest("tr").find("input.unitcost").val();
                var quantity = $(this).closest("tr").find("input.quantity").val();
                var total = unitcost * quantity;
                $(this).closest("tr").find("input.total").val(total);
                totalAmountPrice();
            });


            // Calculate sum of amout in invoice
            function totalAmountPrice(){
                var sum = 0;
                $(".total").each(function(){
                    var value = $(this).val();
                    if(!isNaN(value) && value.length != 0){
                        sum += parseFloat(value);
                    }
                });
                $('#total_amount').val(sum);
            }
        });
    </script>

    <!-- Get Products by category -->
    <script type="text/javascript">
        $(function(){
            $(document).on('change','#category_id',function(){
                var category_id = $(this).val();
                $.ajax({
                    url:"{{ route('get-all-product') }}",
                    type: "GET",
                    data:{category_id:category_id},
                    success:function(data){
                        var html = '';
                        $.each(data,function(key,v){
                            html += '<option value=" '+v.id+' "> '+v.product_name+'</option>';
                        });
                        $('#product_id').html(html);
                    }
                })
            });
        });

    </script>
@endsection
