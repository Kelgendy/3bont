<?php echo $this->load->view('header'); ?>
        <div class="grid_24">
            <div class="page_title"><?=lang('profile_page_name'); ?></div>
        </div>
		<?php echo $this->load->view('account/account_menu', array('current' => 'account_profile')); ?>
        <div class="clear"></div>
        <div class="grid_16" id="sign_in_page">
            <?php echo form_open_multipart(uri_string()); ?>
            <?php echo form_fieldset(); ?>
            <?php if (isset($profile_info)) : ?>
            <div class="grid_11 alpha omega">
                <div class="form_info"><?php echo $profile_info; ?></div>
            </div>
            <div class="clear"></div>
            <?php endif; ?>
            <p><?php echo lang('profile_instructions'); ?></p>
            <br/>
            <div class="grid_3 alpha">
                <?php echo form_label(lang('profile_username'), 'profile_username'); ?>
            </div>
                <?php echo form_input(array(
                        'name' => 'profile_username',
                        'id' => 'profile_username',
                        'value' => set_value('profile_username') ? set_value('profile_username') : (isset($account->username) ? $account->username : ''),
                        'maxlength' => '24',
                        'class' => 'grid_6 omega'
                    )); ?>
                <?php echo form_error('profile_username'); ?>
                <?php if (isset($profile_username_error)) : ?>
                <span class="field_error"><?php echo $profile_username_error; ?></span>
                <?php endif; ?>
            
            <div class="clear"></div>
            <div class="grid_3 alpha">
                <?php echo form_label(lang('profile_picture'), 'profile_picture'); ?>
            </div>
            <div class="grid_6 omega">
                <p>
                    <?php if (isset($account_details->picture)) : ?>
                    <img src="<?php echo $account_details->picture; ?>?t=<?php echo md5(time()); ?>" alt="" /> <?php echo anchor('account/account_profile/index/delete', lang('profile_delete_picture')); ?>
                    <?php else : ?>
                    <img style="width:100px; height:100px" src="resource/img/profile-img.jpg" alt="" />
                    <?php endif; ?>
                </p>
            <div class="clear"></div>
                <?php echo form_upload(array(
                    'name' => 'account_picture_upload',
                    'id' => 'account_picture_upload'
                )); ?>
                <p><small><?php echo lang('profile_picture_guidelines'); ?></small></p>
                <?php if (isset($profile_picture_error)) : ?>
                <span class="field_error"><?php echo $profile_picture_error; ?></span>
                <?php endif; ?>
            </div>
            <div class="clear"></div>
            <div class="prefix_3 grid_6 alpha omega">
                <?php echo form_button(array(
                        'type' => 'submit',
                        'class' => 'button',
                        'content' => lang('profile_save')
                    )); ?>
            </div>
            <?php echo form_fieldset_close(); ?>
            <?php echo form_close(); ?>
        </div>
<?php echo $this->load->view('footer'); ?>