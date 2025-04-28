<div class="register-container">
        <h2>Register</h2>
        <form>
            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" required>
            </div>

            <div class="form-group">
                <label for="userType">Register as</label>
                <select id="userType" required>
                    <option value="member">Member</option>
                    <option value="artist">Artist</option>
                    <option value="organizer">Event Organizer</option>
                    <option value="supplier">Event Equipment Supplier</option>
                    <option value="merchandise_vendor">Merchandise Vendor</option>

                </select>
            </div>

            <button type="submit">Register</button>
        </form>
    </div>
