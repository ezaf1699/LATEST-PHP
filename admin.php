<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - SkillSwap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="admin.php">
                <i class="bi bi-shield-lock"></i> Admin Panel
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Back to Site</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <ul class="nav nav-tabs mb-4" id="adminTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="users-tab" data-bs-toggle="tab" data-bs-target="#users"
                    type="button">
                    <i class="bi bi-people"></i> Users
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="listings-tab" data-bs-toggle="tab" data-bs-target="#listings"
                    type="button">
                    <i class="bi bi-list-ul"></i> Listings
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button">
                    <i class="bi bi-star"></i> Reviews
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="chats-tab" data-bs-toggle="tab" data-bs-target="#chats" type="button">
                    <i class="bi bi-chat-dots"></i> Reported Chats
                </button>
            </li>
        </ul>

        <div class="tab-content" id="adminTabContent">
            <div class="tab-pane fade show active" id="users" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">User Management</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Joined</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="users-table-body">
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="listings" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Skill Listings Management</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Skill Offered</th>
                                        <th>Skill Requested</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="listings-table-body">
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="reviews" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Reviews Management</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Reviewer</th>
                                        <th>Reviewee</th>
                                        <th>Rating</th>
                                        <th>Comment</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="reviews-table-body">
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="chats" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Reported Chat Rooms</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Room ID</th>
                                        <th>Users</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="chats-table-body">
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm">
                        <input type="hidden" id="edit-user-id">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" id="edit-full-name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" id="edit-location" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select id="edit-status" class="form-select">
                                <option value="active">Active</option>
                                <option value="banned">Banned</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="js/admin.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // DELETE LISTING BUTTON FUNCTION
            document.querySelector("#listings-table-body").addEventListener("click", async (e) => {
                const deleteBtn = e.target.closest(".delete-listing");
                if (deleteBtn) {
                    const id = deleteBtn.dataset.id;
                    if (!confirm("Are you sure you want to delete this listing?")) return;

                    try {
                        const res = await fetch(`delete_listing.php?id=${id}`);
                        const result = await res.json();

                        if (result.success) {
                            alert("Listing deleted successfully.");
                            location.reload(); // Refresh to update the list
                        } else {
                            alert(result.error || "Failed to delete listing.");
                        }
                    } catch (err) {
                        console.error(err);
                        alert("Error deleting listing.");
                    }
                }
            });
        });
    </script>



</body>

</html>