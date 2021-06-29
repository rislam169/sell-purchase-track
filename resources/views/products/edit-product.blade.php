<form id="edit-product-form" enctype="multipart/form-data" autocomplete="off">
    @csrf
    <input type="hidden" name="id" id="product_id" value="{{ $product->id }}">
    <input type="hidden" name="old_image" value="{{ $product->image }}">
    <div class="form-group">
        <label for="product_name" class="form-control-label">
            Product Name
        </label>
        <input type="text" class="form-control" id="product_name" name="title" value="{{ $product->title }}" required>
    </div>
    <div class="form-group">
        <label for="product_name" class="form-control-label">
            Category
        </label>
        <input type="text" class="form-control" id="category" name="category" value="{{ $product->category }}" required>
    </div>
    <div class="form-group">
        <label for="description" class="form-control-label">
            Description
        </label>
        <textarea type="text" class="form-control" id="description" name="description">{{ $product->description }}</textarea>
    </div>
    <div class="form-group">
        <label for="status" class="form-control-label">Status</label>
        <select class="form-control" name="status">
            <option value="{{ \App\Models\Product::ACTIVE }}" {{ $product->status == \App\Models\Product::ACTIVE ? 'selected' : '' }}>Active</option>
            <option value="{{ \App\Models\Product::NOT_ACTIVE }}" {{ $product->status == \App\Models\Product::NOT_ACTIVE ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
</form>
