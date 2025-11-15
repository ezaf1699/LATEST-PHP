// js/admin.js
document.addEventListener("DOMContentLoaded", () => {
  loadAdminData();

  // Delegate clicks from users table for dynamic rows
  document.querySelector("#users-table-body").addEventListener("click", async (e) => {
    const editBtn = e.target.closest(".edit-user");
    const deleteBtn = e.target.closest(".delete-user");
    if (editBtn) {
      const id = editBtn.dataset.id;
      openEditUserModal(id);
    } else if (deleteBtn) {
      const id = deleteBtn.dataset.id;
      if (!confirm("Are you sure you want to delete this user?")) return;
      const res = await fetch(`delete_user.php?id=${id}`);
      const result = await res.json();
      if (result.success) {
        alert("User deleted.");
        loadAdminData();
      } else {
        alert(result.error || "Failed to delete user.");
      }
    }
  });

  // Delegate listings table actions
  document.querySelector("#listings-table-body").addEventListener("click", async (e) => {
    const del = e.target.closest(".delete-listing");
    const toggle = e.target.closest(".toggle-listing");
    if (del) {
      const id = del.dataset.id;
      if (!confirm("Delete this listing?")) return;
      const r = await fetch("admin_actions.php", {
        method: "POST",
        body: new URLSearchParams({ action: "delete", type: "listing", id })
      });
      const data = await r.json();
      if (data.success) {
        alert("Listing deleted.");
        loadAdminData();
      } else {
        alert("Failed to delete listing.");
      }
    } else if (toggle) {
      const id = toggle.dataset.id;
      const r = await fetch("admin_actions.php", {
        method: "POST",
        body: new URLSearchParams({ action: "toggle", type: "listing", id })
      });
      const data = await r.json();
      if (data.success) loadAdminData();
      else alert("Failed to toggle listing.");
    }
  });

  // Edit user form submit
  const editForm = document.getElementById("editUserForm");
  if (editForm) {
    editForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const id = document.getElementById("edit-user-id").value;
      const full_name = document.getElementById("edit-full-name").value.trim();
      const location = document.getElementById("edit-location").value.trim();
      const status = document.getElementById("edit-status").value;

      const formData = new FormData();
      formData.append("id", id);
      formData.append("full_name", full_name);
      formData.append("location", location);
      formData.append("status", status);

      const res = await fetch("update_user.php", { method: "POST", body: formData });
      const result = await res.json();
      if (result.success) {
        const modalEl = document.getElementById("editUserModal");
        const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        modal.hide();
        alert("User updated.");
        loadAdminData();
      } else {
        alert(result.error || "Failed to update user.");
      }
    });
  }
});

async function loadAdminData() {
  try {
    const res = await fetch("get_admin_data.php");
    const data = await res.json();

    // users
    const usersBody = document.getElementById("users-table-body");
    usersBody.innerHTML = "";
    if (!data.users || data.users.length === 0) {
      usersBody.innerHTML = `<tr><td colspan="6" class="text-center text-muted">No users found</td></tr>`;
    } else {
      data.users.forEach(u => {
        usersBody.innerHTML += `
          <tr>
            <td>${escapeHtml(u.full_name) || "—"}</td>
            <td>${escapeHtml(u.email)}</td>
            <td>${escapeHtml(u.location) || "—"}</td>
            <td><span class="badge ${u.status === 'active' ? 'bg-success' : 'bg-danger'}">${escapeHtml(u.status || '')}</span></td>
            <td>${escapeHtml(u.created_at || '')}</td>
            <td>
              <button class="btn btn-sm btn-warning edit-user" data-id="${u.id}" title="Edit"><i class="bi bi-pencil"></i></button>
              <button class="btn btn-sm btn-danger delete-user" data-id="${u.id}" title="Delete"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
        `;
      });
    }

    // listings
    const listingsBody = document.getElementById("listings-table-body");
    listingsBody.innerHTML = "";
    if (!data.listings || data.listings.length === 0) {
      listingsBody.innerHTML = `<tr><td colspan="6" class="text-center text-muted">No listings found</td></tr>`;
    } else {
      data.listings.forEach(l => {
        listingsBody.innerHTML += `
          <tr>
            <td>${escapeHtml(l.user_name)}</td>
            <td>${escapeHtml(l.skill_offered)}</td>
            <td>${escapeHtml(l.skill_wanted)}</td>
            <td><span class="badge ${l.is_active ? 'bg-success' : 'bg-secondary'}">${l.is_active ? 'Active' : 'Inactive'}</span></td>
            <td>${escapeHtml(l.created_at || '')}</td>
            <td>
              <button class="btn btn-sm btn-warning toggle-listing" data-id="${l.id}" title="Toggle Active"><i class="bi bi-toggle-on"></i></button>
              <button class="btn btn-sm btn-danger delete-listing" data-id="${l.id}" title="Delete"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
        `;
      });
    }

  } catch (err) {
    console.error(err);
    document.getElementById("users-table-body").innerHTML = `<tr><td colspan="6" class="text-danger text-center">Failed to load data</td></tr>`;
    document.getElementById("listings-table-body").innerHTML = `<tr><td colspan="6" class="text-danger text-center">Failed to load data</td></tr>`;
  }
}

async function openEditUserModal(id) {
  try {
    const res = await fetch(`edit_user.php?id=${encodeURIComponent(id)}`);
    const user = await res.json();
    if (user.error) {
      alert(user.error);
      return;
    }
    document.getElementById("edit-user-id").value = user.id;
    document.getElementById("edit-full-name").value = user.full_name || "";
    document.getElementById("edit-location").value = user.location || "";
    document.getElementById("edit-status").value = user.status || "active";

    const modal = new bootstrap.Modal(document.getElementById("editUserModal"));
    modal.show();
  } catch (err) {
    console.error(err);
    alert("Failed to load user data.");
  }
}

function escapeHtml(unsafe) {
  if (unsafe === null || unsafe === undefined) return "";
  return String(unsafe)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}
