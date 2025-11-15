<?php
session_start();
require 'db.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Listings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">


    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">SkillSwap</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="my-listings.php">My Listings</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>My Skill Listings</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createListingModal">
                <i class="bi bi-plus-lg"></i> Create Listing
            </button>
        </div>
    </div>

    <!-- Listings Container -->
    <div class="container">
        <div class="row" id="my-listings-container">
            <!-- Dynamic listings will appear here -->
        </div>
    </div>

    <!-- Create Listing Modal -->
    <div class="modal fade" id="createListingModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="create-listing-form" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Skill Listing</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="skill_offered" class="form-label">Skill I Can Offer</label>
                        <input type="text" name="skill_offered" class="form-control" id="skill_offered" required>
                    </div>
                    <div class="mb-3">
                        <label for="skill_wanted" class="form-label">Skill I Want to Learn</label>
                        <input type="text" name="skill_wanted" class="form-control" id="skill_wanted" required>
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            <option value="1">Programming</option>
                            <option value="2">Design</option>
                            <option value="3">Teaching</option>
                            <option value="4">Cooking</option>
                            <option value="5">Photography</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control" id="description" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Create Listing</button>
                </div>
            </form>
        </div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            loadMyListings();

            const form = document.getElementById("create-listing-form");

            form.addEventListener("submit", async (e) => {
                e.preventDefault();
                const formData = new FormData(form);
                const isEditing = form.dataset.editing === "true";
                let url = isEditing ? "update_listing.php" : "create_listing.php";

                if (isEditing) {
                    formData.append("id", form.dataset.listingId);
                }

                const response = await fetch(url, { method: "POST", body: formData });
                const data = await response.json();

                if (data.success) {
                    alert(isEditing ? "Listing updated successfully!" : "Listing created successfully!");
                    form.reset();
                    delete form.dataset.editing;
                    delete form.dataset.listingId;
                    document.querySelector("#createListingModal .modal-title").innerText = "Create Skill Listing";
                    document.querySelector("#createListingModal button[type='submit']").innerText = "Create Listing";
                    loadMyListings();
                    bootstrap.Modal.getInstance(document.getElementById("createListingModal")).hide();
                } else {
                    alert(data.error || "Operation failed.");
                }
            });
        });

        async function loadMyListings() {
            const container = document.getElementById("my-listings-container");
            container.innerHTML = '<div class="col-12 text-center py-5"><div class="spinner-border text-primary"></div></div>';

            const res = await fetch("get_my_listings.php");
            const listings = await res.json();

            if (!Array.isArray(listings) || listings.length === 0) {
                container.innerHTML = '<div class="text-center text-muted py-5">No listings yet.</div>';
                return;
            }

            container.innerHTML = listings.map(listing => `
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">${listing.skill_offered}</h5>
                    <p class="text-muted mb-1"><i class="bi bi-arrow-left-right"></i> Wants to learn: <strong>${listing.skill_wanted}</strong></p>
                    <p class="small mb-1">Category: ${listing.category_name}</p>
                    <p class="small">${listing.description}</p>
                    <span class="badge bg-${listing.is_active ? 'success' : 'secondary'}">
                        ${listing.is_active ? 'Active' : 'Inactive'}
                    </span>
                </div>
                <div class="card-footer bg-white d-flex justify-content-between">
                    <button class="btn btn-sm btn-outline-primary" onclick="editListing(${listing.id}, '${listing.skill_offered}', '${listing.skill_wanted}', ${listing.category_id}, \`${listing.description.replace(/`/g, "\\`")}\`)">
                        <i class="bi bi-pencil-square"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteListing(${listing.id})">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    `).join("");
        }

        function editListing(id, skillOffered, skillWanted, categoryId, description) {
            const form = document.getElementById("create-listing-form");
            form.dataset.editing = "true";
            form.dataset.listingId = id;

            document.getElementById("skill_offered").value = skillOffered;
            document.getElementById("skill_wanted").value = skillWanted;
            document.querySelector("select[name='category_id']").value = categoryId;
            document.getElementById("description").value = description;

            document.querySelector("#createListingModal .modal-title").innerText = "Edit Skill Listing";
            document.querySelector("#createListingModal button[type='submit']").innerText = "Update Listing";

            new bootstrap.Modal(document.getElementById("createListingModal")).show();
        }

        async function deleteListing(id) {
            if (!confirm("Are you sure you want to delete this listing?")) return;

            const res = await fetch(`delete_listing.php?id=${id}`);
            const data = await res.json();

            if (data.success) {
                alert("Listing deleted successfully!");
                loadMyListings();
            } else {
                alert(data.error || "Failed to delete listing.");
            }
        }
    </script>

</body>

</html>