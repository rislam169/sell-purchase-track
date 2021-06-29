<form id="edit-project-form" enctype="multipart/form-data" autocomplete="off">
    @csrf
    <input type="hidden" name="id" id="project_id" value="{{ $project->id }}">
    <div class="form-group">
        <label for="project_name" class="form-control-label">
            Project Name
        </label>
        <input type="text" class="form-control" id="project_name" name="project_name" value="{{ $project->project_name }}" required>
    </div>
    <div class="form-group">
        <label for="description" class="form-control-label">
            Total Budget
        </label>
        <input type="number" class="form-control" id="total_budget" name="total_budget" value="{{ $project->total_budget }}" required>
    </div>
    <div class="form-group">
        <label for="description" class="form-control-label">
            Staff Person
        </label>
        <input type="text" class="form-control" id="staff_person" name="staff_person" value="{{ $project->staff_person }}" required>
    </div>
</form>
