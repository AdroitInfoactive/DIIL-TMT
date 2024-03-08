<div class="tab-pane fade" id="erase-setting" role="tabpanel" aria-labelledby="home-tab4">
    <div id="overlay" onclick="off()">
        <div class="w-100 d-flex justify-content-center align-items-center">
            <div class="spinner"></div>
        </div>
    </div>
    <div class="card">
        <div class="card-body border">
            <h6 class="text-danger text-center">Once data deleted, it can not be restored. Be sure before deleting.</h6>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <ul style="list-style-type: none;">
                            <li style="list-style-type: none;">
                                <label><input type="checkbox">All</label>
                                <ul style="list-style-type: none;">
                                    <li style="list-style-type: none;">
                                        <label><input type="checkbox" name="modules[]" value="clients">Clients</label>
                                    </li>
                                    <li>
                                        <label><input type="checkbox" name="modules[]" value="products">Products</label>
                                    </li>
                                    <li>
                                        <label><input type="checkbox" name="modules[]" value="orders">Orders</label>
                                    </li>
                                    <li>
                                        <label><input type="checkbox" name="modules[]" value="receipts">Receipts</label>
                                    </li>
                                    <li>
                                        <label><input type="checkbox" name="modules[]" value="expenses">Expenses</label>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <a href='javascript:;' class='btn btn-danger delete-data' title='Delete'>Delete Data</a>
            {{-- <button type="submit" class="btn btn-danger delete-data"></button> --}}
        </div>
    </div>
</div>
