<?=$this -> load -> view('header'); ?>
        <div class="grid_24">
            <div class="page_title"><?=lang('password_page_name'); ?></div>
        </div>
        <?php echo $this->load->view('account/account_menu', array('current' => 'account_password')); ?>
        <div class="clear"></div>
        <div class="grid_24" id="sign_in_page">
            <?php echo form_open(uri_string(), array('id'=>'form1')); ?>
            <?php echo form_fieldset(); ?>
            <?php if ($this->session->flashdata('password_info')) : ?>
            <div class="grid_11 alpha omega">
                <div class="form_info"><?php echo $this->session->flashdata('password_info'); ?></div>
            </div>
            <div class="clear"></div>
            <?php endif; ?>
            <?php echo lang('password_safe_guard_your_account'); ?>
            <div class="grid_5 alpha">
                <?php echo form_label(lang('password_new_password'), 'password_new_password'); ?>
            </div>

                <?php echo form_password(array(
                        'name' => 'password_new_password',
                        'id' => 'password_new_password',
                        'value' => set_value('password_new_password'),
                        'autocomplete' => 'off',
                        'class' => 'grid_6 omega validate[required]'
                    )); ?>
                <?php echo form_error('password_new_password'); ?>
         
            <div class="clear"></div>
            <div class="grid_5 alpha">
                <?php echo form_label(lang('password_retype_new_password'), 'password_retype_new_password'); ?>
            </div>
                <?php echo form_password(array(
                        'name' => 'password_retype_new_password',
                        'id' => 'password_retype_new_password',
                        'value' => set_value('password_retype_new_password'),
                        'autocomplete' => 'off',
                        'class' => 'grid_6 omega validate[required,equals[password_new_password]]'
                    )); ?>
                <?php echo form_error('password_retype_new_password'); ?>
   
            <div class="clear"></div>
            <div class="prefix_5 grid_6 alpha omega">
                <?php echo form_button(array(
                        'type' => 'submit',
                        'class' => 'button',
                        'content' => lang('password_change_my_password')
                    )); ?>
            </div>
            <?php echo form_fieldset_close(); ?>
            <?php echo form_close(); ?>
        </div>
        <div class="clear"></div>
    </div>
<?php echo $this->load->view('footer'); ?>