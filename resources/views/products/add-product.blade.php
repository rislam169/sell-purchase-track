<!--begin::Modal-->
<div class="modal fade" id="addProduct" tabindex="-1" role="dialog" aria-labelledby="addProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form id="add-product-form" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="addProductLabel">
                        Add Product
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <h4 class="mt-0">Product Information</h4>
                    </div>
                    <div class="form-group">
                        <label for="product_name" class="form-control-label">
                            Product Name
                        </label>
                        <input type="text" class="form-control" id="product_name" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="product_name" class="form-control-label">
                            Category
                        </label>
                        <input type="text" class="form-control" id="category" name="category" required>
                    </div>
                    <div class="form-group">
                        <label for="description" class="form-control-label">
                            Description
                        </label>
                        <textarea type="text" class="form-control" id="description" name="description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary" id="add-product-submit">
                        Add Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Modal-->
