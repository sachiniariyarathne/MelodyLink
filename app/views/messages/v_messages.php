<?php require APPROOT . '/views/inc/header3.php'; ?>

<div style="background:#f5f8fa; min-height:100vh; padding-bottom:3rem;">
    <div style="max-width:900px; margin:0 auto; padding:2rem 1rem;">
        <h1 style="font-family:'Montserrat',sans-serif; font-weight:700; font-size:2rem; color:#222; margin-bottom:2rem;">Messages</h1>

        <?php if(isset($_SESSION['message_success'])): ?>
            <div style="background:#d1fae5; color:#065f46; border:1px solid #a7f3d0; border-radius:6px; padding:1rem; margin-bottom:1rem;">
                <?php echo $_SESSION['message_success']; unset($_SESSION['message_success']); ?>
            </div>
        <?php endif; ?>
        <?php if(isset($_SESSION['message_error'])): ?>
            <div style="background:#fee2e2; color:#b91c1c; border:1px solid #fecaca; border-radius:6px; padding:1rem; margin-bottom:1rem;">
                <?php echo $_SESSION['message_error']; unset($_SESSION['message_error']); ?>
            </div>
        <?php endif; ?>

        <?php if(!empty($data['messages'])): ?>
            <?php foreach($data['messages'] as $msg): ?>
                <div style="background:#fff; border-radius:16px; box-shadow:0 4px 16px rgba(0,0,0,0.07); margin-bottom:2rem; padding:1.5rem 2rem; display:flex; flex-direction:column; gap:0.5rem;">
                    <div style="display:flex; align-items:center; gap:1.25rem;">
                        <img src="<?php echo URLROOT; ?>/img/avatar-default.jpg" alt="Avatar" style="width:60px; height:60px; border-radius:50%; object-fit:cover;">
                        <div>
                            <div style="font-weight:700; font-size:1.15rem; color:#222;"><?php echo htmlspecialchars($msg->username); ?></div>
                            <div style="color:#7b7b7b; font-size:1rem;">
                                <?php echo htmlspecialchars($msg->Organization ? $msg->Organization : 'Event Organizer'); ?>
                            </div>
                            <div style="color:#b0b0b0; font-size:0.95rem;"><?php echo htmlspecialchars($msg->email); ?></div>
                        </div>
                    </div>
                    <div style="margin:1rem 0 0.3rem 0; color:#222; font-size:1.08rem;">
                        <?php echo htmlspecialchars($msg->message); ?>
                    </div>
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-top:0.5rem;">
                        <div style="color:#7b7b7b; font-size:0.97rem; display:flex; align-items:center; gap:0.4rem;">
                            <i class="far fa-clock"></i>
                            <?php
                                $requestDate = new DateTime($msg->request_date);
                                $now = new DateTime();
                                $interval = $requestDate->diff($now);
                                if($interval->d > 0)      echo $interval->d . ' day' . ($interval->d>1?'s':'') . ' ago';
                                elseif($interval->h > 0) echo $interval->h . ' hour' . ($interval->h>1?'s':'') . ' ago';
                                else                     echo $interval->i . ' minute' . ($interval->i>1?'s':'') . ' ago';
                            ?>
                        </div>
                        <form action="<?php echo URLROOT; ?>/messages/respond" method="POST" style="display:flex; gap:0.7rem;">
                            <input type="hidden" name="message_id" value="<?php echo $msg->id; ?>">
                            <button type="submit" name="response" value="accepted" style="background:#10b981; color:white; border:none; border-radius:6px; font-weight:600; padding:0.5rem 1.2rem; font-size:1rem; display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
                                <i class="fas fa-check"></i> Accept
                            </button>
                            <button type="submit" name="response" value="rejected" style="background:#ef4444; color:white; border:none; border-radius:6px; font-weight:600; padding:0.5rem 1.2rem; font-size:1rem; display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="text-align:center; color:#888; font-size:1.1rem; padding:3rem 0;">
                No pending messages at this time.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
