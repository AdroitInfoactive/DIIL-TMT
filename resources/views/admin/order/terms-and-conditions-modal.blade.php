<div class="modal fade" id="terms_condition_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add / Edit Terms and Conditions</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form class="add_terms_condition_form">
                    @csrf
                    <div class="row">
                        @foreach (@$terms as $term)
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" name="terms_condition[]" type="checkbox"
                                        id="terms_condition{{ @$term->id }}" value="{{ @$term->id }}"
                                        style="width: 20px; height: 20px;"@if (isset($quotationTerms) && count($quotationTerms) > 0) @if(in_array(@$term['id'], array_column($quotationTerms, 'id'))) checked @endif @endif>
                                    &nbsp;
                                    <label class="form-check-label" for="terms_condition{{ @$term->id }}" style="padding-top: 2px;">
                                        {{ @$term->name }} - {{ @$term->description }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="row mt-3 justify-content-end">
                        <div class="mr-2">
                            <a href="#" class='btn btn-primary terms-and-conditions' data-id="">Save</a>
                        </div>
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
