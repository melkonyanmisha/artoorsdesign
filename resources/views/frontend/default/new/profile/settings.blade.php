<div class="for_settings">
    <h5>Settings</h5>
    <div class="setting_section sto_">
        <div class="change_notifs d_flex">
            <span>Email notification preferences</span>
            <div class="save_adress_btn_switch">
                <label class="save_adress_btn_switch_lab" for="myToggle">
                    <input class="toggle_input" name="" @if(auth()->user()->notification_send)checked
                           @endif type="checkbox" id="myToggle">
                    <div class="toggle_fill"></div>
                </label>
            </div>
        </div>
        <form class="change_notifs">
            <div class="d_flex sto_">
                <span>Change your account password</span>
                <p class="change_pass">Edit</p>
            </div>
            <div class="new_pass_confirm">
                <div class="d_flex sto_ inp_cols">
                    <div class="d_flex inps_labs">
                        <label for="newpass">New password</label>
                        <input type="password" id="newpass">
                    </div>
                    <div class="d_flex inps_labs">
                        <label for="confirmpass">Confirm new password</label>
                        <input type="password" id="confirmpass">
                    </div>
                </div>
                <div class="d_flex sto_ inp_cols">
                    <button class="save_edits_btn d_flex change_password">Save</button>
                    <a class="d_flex close_edit">Close</a>
                </div>
            </div>
        </form>
        <form class="change_notifs">
            <div class="d_flex sto_">
                <span>Set or change your account's email address</span>
                <p class="change_pass">Edit</p>
            </div>
            <div class="new_pass_confirm">
                <div class="d_flex sto_ inp_cols">
                    <div class="d_flex inps_labs">
                        <label for="email_">Email</label>
                        <input type="email" id="email_">
                    </div>
                    <div class="d_flex inps_labs">
                        <label for="mailpass">Password</label>
                        <input type="password" id="mailpass">
                    </div>
                </div>
                <div class="d_flex sto_ inp_cols">
                    <button class="d_flex save_mails  change_email">Save</button>
                    <a class="d_flex close_edit">Close</a>
                </div>
            </div>
        </form>
    </div>
</div>