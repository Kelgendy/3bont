<?php echo $this->load->view('header'); ?>
        <div class="grid_24">
            <h3><?php echo lang('sign_out_successful'); ?></h2>
            <p><?php echo anchor('', lang('sign_out_go_to_home'), array('class'=>'button')); ?></p>
        </div>
        <div class="clear"></div>
<?php echo $this->load->view('footer'); ?>