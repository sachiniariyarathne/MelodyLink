<?php require APPROOT . '/views/users/event_management/header.php'; ?>

<div class="my-requests-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-white">My Equipment Requests</h2>
        <a href="<?php echo URLROOT; ?>/eventequipmentcontroller" class="btn-back">
            <i class="fas fa-arrow-left me-2"></i>Back to Equipment
        </a>
    </div>
    <?php if (empty($data['requests'])): ?>
        <div class="alert alert-info text-center">You have not made any equipment requests yet.</div>
    <?php else: ?>
        <div class="vstack gap-3">
            <?php foreach ($data['requests'] as $req): ?>
                <div class="notification-card d-flex align-items-stretch p-3 bg-dark-subtle rounded shadow-sm">
                    <span class="badge status-badge <?php echo $req->status; ?> ms-2"><?php echo ucfirst($req->status); ?></span>
                    <div class="notif-img-wrap flex-shrink-0 me-3">
                        <img src="<?php echo $req->image_url; ?>" alt="<?php echo $req->equipment_name; ?>" class="notif-img">
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h5 class="mb-0 text-white"><?php echo $req->equipment_name; ?></h5>
                        </div>
                        <div class="mb-1 text-muted" style="font-size: 0.95rem;">
                            <?php if (isset($req->category)): ?>
                                <span class="badge bg-category"><i class="fas fa-tag me-1"></i> <?php echo ucfirst($req->category); ?></span>
                            <?php endif; ?>
                            <span class="ms-2"><i class="fas fa-dollar-sign me-1"></i><?php echo number_format($req->price, 2); ?>/day</span>
                        </div>
                        <div class="mb-1"><strong>Message:</strong> <span class="text-light" id="msg-<?php echo $req->id; ?>"><?php echo htmlspecialchars($req->message); ?></span></div>
                        <div class="mb-2"><strong>Date:</strong> <span class="text-light" id="date-<?php echo $req->id; ?>"><?php echo date('Y-m-d\TH:i', strtotime($req->request_date)); ?></span></div>
                        <?php if ($req->status === 'pending'): ?>
                        <div class="d-flex gap-2 mt-2">
                            <!-- <button type="button" class="btn-beauty" onclick="openEditModal(<?php echo $req->id; ?>, '<?php echo htmlspecialchars(addslashes($req->message)); ?>', '<?php echo date('Y-m-d\TH:i', strtotime($req->request_date)); ?>')"><i class="fas fa-edit me-1"></i>Edit</button> -->
                            <form action="<?php echo URLROOT; ?>/eventequipmentcontroller/deleterequest/<?php echo $req->id; ?>" method="post" style="display:inline;">
                                <button type="submit" class="btn-beauty delete" onclick="return confirm('Are you sure you want to delete this request?');"><i class="fas fa-trash me-1"></i>Delete</button>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Edit Modal (move here, outside all containers) -->
<div class="modal fade" id="editRequestModal" tabindex="-1" aria-labelledby="editRequestModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="editRequestModalLabel">Edit Request</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editRequestForm">
        <div class="modal-body">
          <input type="hidden" name="edit_id" id="edit_id">
          <div class="mb-3">
            <label for="edit_message" class="form-label">Request Message</label>
            <textarea class="form-control" name="edit_message" id="edit_message" rows="4" required></textarea>
          </div>
          <div class="mb-3">
            <label for="edit_date" class="form-label">Request Date</label>
            <input type="datetime-local" class="form-control" name="edit_date" id="edit_date" required>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-beauty">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function openEditModal(id, message, date) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_message').value = message;
    document.getElementById('edit_date').value = date;
    if (window.bootstrap && bootstrap.Modal) {
        var modal = new bootstrap.Modal(document.getElementById('editRequestModal'));
        modal.show();
    } else {
        alert('Bootstrap JS is not loaded. Modal cannot open.');
    }
}

document.getElementById('editRequestForm').onsubmit = async function(e) {
    e.preventDefault();
    const id = document.getElementById('edit_id').value;
    const message = document.getElementById('edit_message').value;
    const date = document.getElementById('edit_date').value;
    const formData = new FormData();
    formData.append('message', message);
    formData.append('request_date', date);
    try {
        const response = await fetch(`<?php echo URLROOT; ?>/eventequipmentcontroller/editrequest/${id}`, {
            method: 'POST',
            body: formData
        });
        if (response.ok) {
            document.getElementById('msg-' + id).textContent = message;
            document.getElementById('date-' + id).textContent = date;
            bootstrap.Modal.getInstance(document.getElementById('editRequestModal')).hide();
        } else {
            alert('Failed to update request.');
        }
    } catch (err) {
        alert('Error updating request.');
    }
};
</script>

<style>
.my-requests-container {
    padding: 2rem;
    background: #1a1625;
    min-height: 100vh;
}
.notification-card {
    display: flex;
    flex-direction: row;
    align-items: stretch;
    min-height: 140px;
    position: relative;
    background: rgba(255,255,255,0.08) !important;
    border-radius: 1rem;
    border: none;
    transition: box-shadow 0.3s, transform 0.3s;
    box-shadow: 0 2px 8px rgba(44, 19, 56, 0.10);
}
.notification-card:hover {
    box-shadow: 0 8px 32px rgba(44, 19, 56, 0.18);
    transform: translateY(-2px) scale(1.01);
}
.notif-img-wrap {
    width: 140px;
    height: 100%;
    min-height: 140px;
    background: #232046;
    border-radius: 0.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
}
.notif-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 0.9rem;
}
.flex-grow-1 {
    min-width: 0;
    flex: 1 1 0%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding-left: 1.5rem;
}
.status-badge {
    font-size: 1rem;
    font-weight: 600;
    border-radius: 0.7rem;
    padding: 0.4em 1.1em;
    text-transform: capitalize;
    position: absolute;
    top: 1rem;
    right: 1.5rem;
    z-index: 2;
    box-shadow: 0 2px 8px rgba(44, 19, 56, 0.10);
}
.status-badge.pending {
    background: linear-gradient(90deg, #ffe066 60%, #ffb347 100%);
    color: #232046;
}
.status-badge.accepted {
    background: linear-gradient(90deg, #4caf50 60%, #81c784 100%);
    color: #fff;
}
.status-badge.rejected {
    background: linear-gradient(90deg, #f44336 60%, #ff7961 100%);
    color: #fff;
}
.btn-beauty {
    background: linear-gradient(90deg, #ff4d94 60%, #ff6b6b 100%);
    border: none;
    color: #fff;
    font-weight: 600;
    border-radius: 0.7rem;
    transition: background 0.3s, box-shadow 0.3s;
    box-shadow: 0 2px 8px rgba(255,77,148,0.10);
    padding: 0.4rem 1.2rem;
    font-size: 1rem;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}
.btn-beauty:hover {
    background: linear-gradient(90deg, #ff6b6b 60%, #ff4d94 100%);
    color: #fff;
    box-shadow: 0 4px 16px rgba(255,77,148,0.18);
}
.btn-beauty.delete {
    background: linear-gradient(90deg, #f44336 60%, #ff7961 100%);
}
.btn-beauty.delete:hover {
    background: linear-gradient(90deg, #ff7961 60%, #f44336 100%);
}
.btn-back {
    background: linear-gradient(90deg, #232046 60%, #1a1625 100%);
    color: #fff;
    border: none;
    border-radius: 0.7rem;
    padding: 0.5rem 1.3rem;
    font-weight: 600;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 2px 8px rgba(44, 19, 56, 0.10);
    transition: background 0.3s;
}
.btn-back:hover {
    background: linear-gradient(90deg, #1a1625 60%, #232046 100%);
    color: #fff;
}
@media (max-width: 600px) {
    .notification-card {
        flex-direction: column;
        align-items: center;
        min-height: unset;
    }
    .flex-grow-1 {
        padding-left: 0;
        padding-top: 1rem;
        width: 100%;
    }
    .notif-img-wrap {
        width: 100%;
        min-height: 140px;
        margin-bottom: 0.5rem;
    }
    .status-badge {
        right: 1rem;
        top: 0.7rem;
    }
}
.bg-category {
    background: #fff;
    color: #ff4d94;
    font-weight: 600;
    font-size: 0.95rem;
    border-radius: 0.5rem;
    padding: 0.3em 0.9em;
}
.modal-content.bg-dark {
    background: #232046 !important;
    border-radius: 1.2rem !important;
    box-shadow: 0 8px 32px rgba(44, 19, 56, 0.25) !important;
    border: none !important;
}
.modal-header, .modal-footer {
    background: #232046 !important;
    border: none !important;
    border-radius: 1.2rem 1.2rem 0 0 !important;
}
.modal-footer {
    border-radius: 0 0 1.2rem 1.2rem !important;
    padding-bottom: 1.5rem !important;
    padding-top: 1.5rem !important;
}
.modal-header {
    padding-bottom: 0.5rem !important;
    padding-top: 1.5rem !important;
}
.modal-body {
    padding: 2rem 2rem 1rem 2rem !important;
}
@media (min-width: 576px) {
    .modal-dialog {
        max-width: 500px;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php require APPROOT . '/views/users/event_management/footer.php'; ?> 