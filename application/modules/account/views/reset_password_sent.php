
<?php echo $this->load->view('header'); ?>
        <div class="grid_24" id="sign_in_page">
            <?php echo sprintf(lang('reset_password_sent_instructions'), anchor('account/forgot_password', lang('reset_password_resend_the_instructions'))); ?>
        </div>
        <div class="clear"></div>
  
<?php echo $this->load->view('footer'); ?>