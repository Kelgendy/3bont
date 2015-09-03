<?= $this->load->view('header'); ?>
        <div class="grid_24">
            <div class="page_title"><?=lang('forgot_password_page_name'); ?></div>
        </div>
        <div class="clear"></div>
        <div class="grid_24" id="sign_in_page">
            <?= form_open(uri_string(), array('id' => 'form1')); ?>
            <?= form_fieldset(); ?>
            <p id="forgot_instructions"><?= lang('forgot_password_instructions'); ?></p>
            <div class="clear"></div>
            <br/>
            <div class="grid_5 alpha">
                <?= form_label(lang('forgot_password_username_email'), 'forgot_password_username_email'); ?>
            </div>
            
                <?= form_input(array(
                        'name' => 'forgot_password_username_email',
                        'id' => 'forgot_password_username_email',
                        'value' => set_value('forgot_password_username_email') ? set_value('forgot_password_username_email') : (isset($account) ? $account->username : ''),
                        'maxlength' => '80',
                        'class' => 'grid_8 omega validate[required,custom[email]]'
                    )); ?>
                <?= form_error('forgot_password_username_email'); ?>
                <?php if (isset($forgot_password_username_email_error)) : ?>
                <div class="prefix_5 grid_12 alpha">
                <span class="field_error"><?= $forgot_password_username_email_error; ?></span>
                </div>
                <?php endif; ?>
           		<br/><br/>
            <div class="clear"></div>
            <?php if (isset($recaptcha)) : ?>
            <div class="prefix_5 grid_16 alpha">
                <?= $recaptcha; ?>
            </div>
            <?php if (isset($forgot_password_recaptcha_error)) : ?>
            <div class="prefix_5 grid_12 alpha">
                <span class="field_error"><?= $forgot_password_recaptcha_error; ?></span>
            </div>
            <?php endif; ?>
            <div class="clear"></div>
            <?php endif; ?>
            <div class="prefix_5 grid_12 alpha">
                <?= form_button(array(
                        'type' => 'submit',
                        'class' => 'button',
                        'content' => lang('forgot_password_send_instructions')
                    )); ?>
            </div>
            <?= form_fieldset_close(); ?>
            <?= form_close(); ?>
        </div>
<?= $this->load->view('footer'); ?>