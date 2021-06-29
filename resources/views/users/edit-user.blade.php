<!--begin::Modal-->
<div class="modal fade" id="editUser" tabindex="-1" role="dialog" aria-labelledby="editUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="edit-user-form">
                @csrf
                <input type="hidden" name="id" id="user_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserLabel">
                        Edit User
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name" class="form-control-label">
                            Name
                        </label>
                        <input type="text" class="form-control" id="user_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-control-label">
                            email
                        </label>
                        <input type="text" class="form-control" id="user_email" name="email" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="form-control-label">
                                    Status
                                </label>
                                <select name="status" id="user_status" class="form-control">
                                    <option value="0">Active</option>
                                    <option value="1">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary" id="edit-user-submit">
                        Edit User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Modal-->
