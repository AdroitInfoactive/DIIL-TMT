<div class="modal fade" id="add_product_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Product</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form class="add_product_form">
                    @csrf
                    <input type="hidden" name="sessionId" id="sessionId" value="">
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <select name="product" id="product" class="select2 select2pop form-control">
                                    <option value="">Select Product *</option>

                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                {{-- <textarea name="description" id="" cols="30" rows="10" class="form-control" placeholder="Description">{!! old('description') !!}</textarea> --}}
                                <input type="text" name="description" id="description" placeholder="Description"
                                    value= "{{ old('description') }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <select name="uom" id="uom" class="select2 select2pop form-control">
                                    <option value="">Select UOM *</option>
                                    @foreach ($sizes as $size)
                                        <option value="{{ $size->id }}">{{ $size->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                       
                    </div>
                    <hr>
                    <h5>Make - 1</h5>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <select name="make" id="make" class="select2 select2pop form-control">
                                    <option value="">Select Make *</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <input type="text" name="quantity" id="quantity" value="{{ old('quantity') }}"
                                    id="quantity" placeholder="Quantity *" class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <input type="text" name="price" id="price" value="{{ old('price') }}"
                                    id="price" placeholder="Price *" class="form-control">
                            </div>
                        </div>
                    </div>
                   
                    <hr>
                    <div class="taxes row ml-3 mr-3">
                    </div>
                    <div class="button_to_submit">
                        <a href="#" class='btn btn-primary edit-product' data-id="">Add Product</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
