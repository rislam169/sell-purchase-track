<!--begin::Modal-->
<div class="modal fade" id="addProject" tabindex="-1" role="dialog" aria-labelledby="addProjectLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form id="add-project-form" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addProjectLabel">
                        Add Project
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <h4 class="mt-0">Project Information</h4>
                    </div>
                    <div class="form-group">
                        <label for="project_name" class="form-control-label">
                            Project Name
                        </label>
                        <input type="text" class="form-control" id="project_name" name="project_name" required>
                    </div>
                    <div class="form-group">
                        <label for="description" class="form-control-label">
                            Total Budget
                        </label>
                        <input type="number" class="form-control" id="total_budget" name="total_budget" required>
                    </div>
                    <div class="form-group">
                        <label for="description" class="form-control-label">
                            Staff Person
                        </label>
                        <input type="text" class="form-control" id="staff_person" name="staff_person" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary" id="add-project-submit">
                        Add Project
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Modal-->
