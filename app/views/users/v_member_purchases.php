<?php require APPROOT . '/views/inc/header3.php'; ?> 
<?php require APPROOT . '/views/inc/components/topnavbar_member.php'; ?>

<head>
    <title>My Purchases - MelodyLink</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="md_dashboard-container">
        <!-- Include the sidebar -->
        <aside class="md_sidebar">
        <div class="md_profile-section">
            <img src="<?php echo URLROOT; ?>/public/uploads/<?php echo $data['member_info']['profile_pic']; ?>" 
                 alt="Profile" 
                 class="md_profile-avatar">
            <h2><?php echo $data['member_info']['username']; ?></h2>
            <p><?php echo $data['member_info']['email']; ?></p>
        </div>
        <nav class="md_sidebar-nav">
            <ul>
                <li><a href="<?php echo URLROOT; ?>/users/dashboard"><i class="fa fa-home"></i> Dashboard</a></li>
                <li><a href="<?php echo URLROOT; ?>/my_tickets/mytickets"><i class="fa fa-ticket-alt"></i> My Tickets</a></li>
                <li class="md_active"><a href="<?php echo URLROOT; ?>/Member_Purchases"><i class="fa fa-shopping-cart"></i> My Purchases</a></li>
                <li><a href="<?php echo URLROOT; ?>/music_library/musiclibrary"><i class="fa fa-music"></i> Music Library</a></li>
            </ul>
        </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="md_main-content">
            <div class="md_topbar">
                <h1 class="md_topbar-title">My Purchases</h1>
                <div class="md_topbar-actions">
                    <div class="md_search-box">
                        <i class="fa fa-search"></i>
                        <input type="text" placeholder="Search purchases...">
                    </div>
                </div>
            </div>
            
            <!-- Tabs Navigation -->
            <div class="mp_tabs">
                <button class="mp_tab-btn active" data-tab="purchases">My Orders</button>
                <button class="mp_tab-btn" data-tab="cart">My Cart <span class="mp_cart-count"><?php echo count($data['cart_items']); ?></span></button>
            </div>
            
            <!-- Purchases Tab Content -->
            <div class="mp_tab-content active" id="purchases-content">
                <h2 class="mp_section-title">Order History</h2>
                
                <?php if (empty($data['orders'])): ?>
                    <div class="mp_empty-state">
                        <i class="fa fa-shopping-bag"></i>
                        <p>You haven't made any purchases yet.</p>
                        <a href="<?php echo URLROOT; ?>/merchandise" class="mp_browse-btn">Browse Merchandise</a>
                    </div>
                <?php else: ?>
                    <div class="mp_orders">
                        <?php foreach($data['orders'] as $order): ?>
                            <div class="mp_order-card">
                                <div class="mp_order-header">
                                    <div>
                                        <span class="mp_order-id">Order #<?php echo $order->order_id; ?></span>
                                        <span class="mp_order-date"><?php echo date('M d, Y', strtotime($order->created_at)); ?></span>
                                    </div>
                                    <div class="mp_order-status <?php echo strtolower($order->order_status); ?>">
                                        <?php echo $order->order_status; ?>
                                    </div>
                                </div>
                                <div class="mp_order-items">
                                    <?php foreach($order->items as $item): ?>
                                        <div class="mp_order-item">
                                            <img src="<?php echo URLROOT; ?>/public/uploads/<?php echo $item->image; ?>" alt="<?php echo $item->Name; ?>">
                                            <div class="mp_item-details">
                                                <h3><?php echo $item->Name; ?></h3>
                                                <p class="mp_item-price">$<?php echo number_format($item->Price, 2); ?></p>
                                                <p class="mp_item-quantity">Qty: <?php echo $item->quantity; ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="mp_order-footer">
                                    <div class="mp_order-total">
                                        <span>Total:</span>
                                        <span class="mp_total-price">$<?php echo number_format($order->total_amount, 2); ?></span>
                                    </div>
                                    <div class="mp_order-actions">
                                        <a href="<?php echo URLROOT; ?>/Member_Purchases/order_details/<?php echo $order->id; ?>" class="mp_view-details-btn">View Details</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Cart Tab Content -->
            <div class="mp_tab-content" id="cart-content">
                <h2 class="mp_section-title">Shopping Cart</h2>
                
                <?php if (empty($data['cart_items'])): ?>
                    <div class="mp_empty-state">
                        <i class="fa fa-shopping-cart"></i>
                        <p>Your cart is empty.</p>
                        <a href="<?php echo URLROOT; ?>/merchandise" class="mp_browse-btn">Browse Merchandise</a>
                    </div>
                <?php else: ?>
                    <div class="mp_cart">
                        <div class="mp_cart-items">
                            <?php foreach($data['cart_items'] as $item): ?>
                                <div class="mp_cart-item">
                                    <div class="mp_cart-item-img">
                                        <img src="<?php echo URLROOT; ?>/public/uploads/<?php echo $item->image; ?>" alt="<?php echo $item->Name; ?>">
                                    </div>
                                    <div class="mp_cart-item-details">
                                        <h3><?php echo $item->Name; ?></h3>
                                        <p class="mp_item-description"><?php echo substr($item->Description, 0, 100); ?>...</p>
                                    </div>
                                    <div class="mp_cart-item-quantity">
                                        <form action="<?php echo URLROOT; ?>/Member_Purchases/update_cart" method="POST">
                                            <input type="hidden" name="cart_id" value="<?php echo $item->cart_id; ?>">
                                            <button type="submit" name="action" value="decrease" class="mp_qty-btn">-</button>
                                            <input type="text" name="quantity" value="<?php echo $item->quantity; ?>" readonly>
                                            <button type="submit" name="action" value="increase" class="mp_qty-btn">+</button>
                                        </form>
                                    </div>
                                    <div class="mp_cart-item-price">
                                        $<?php echo number_format($item->Price * $item->quantity, 2); ?>
                                    </div>
                                    <div class="mp_cart-item-actions">
                                        <form action="<?php echo URLROOT; ?>/Member_Purchases/remove_from_cart" method="POST">
                                            <input type="hidden" name="cart_id" value="<?php echo $item->cart_id; ?>">
                                            <button type="submit" class="mp_remove-btn"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="mp_cart-summary">
                            <h3>Order Summary</h3>
                            <div class="mp_summary-item">
                                <span>Subtotal</span>
                                <span>$<?php echo number_format($data['cart_total'], 2); ?></span>
                            </div>
                            <div class="mp_summary-item">
                                <span>Shipping</span>
                                <span>$<?php echo number_format($data['shipping'], 2); ?></span>
                            </div>
                            <div class="mp_summary-total">
                                <span>Total</span>
                                <span>$<?php echo number_format($data['cart_total'] + $data['shipping'], 2); ?></span>
                            </div>
                            <a href="<?php echo URLROOT; ?>/Member_Purchases/checkout" class="mp_checkout-btn">Proceed to Checkout</a>
                            <a href="<?php echo URLROOT; ?>/merchandise" class="mp_continue-shopping">Continue Shopping</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        // Tab switching functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabBtns = document.querySelectorAll('.mp_tab-btn');
            const tabContents = document.querySelectorAll('.mp_tab-content');
            
            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Remove active class from all buttons and contents
                    tabBtns.forEach(b => b.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    // Show corresponding content
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(tabId + '-content').classList.add('active');
                });
            });
        });
    </script>
</body>
</html>
<?php require APPROOT . '/views/inc/footer.php'; ?> 
