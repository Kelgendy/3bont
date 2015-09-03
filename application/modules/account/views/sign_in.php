<?=$this -> load -> view('header'); ?>
    	<div class="grid_24">
	  		<div class="page_title"><?=lang('sign_in_page_name') ?></div>
	  	</div>
        <div class="grid_12" id="sign_in_page">
            <?=form_open(uri_string() . ($this -> input -> get('continue') ? '/?continue=' . urlencode($this -> input -> get('continue')) : ''), array('id' => 'form1')); ?>
            <?=form_fieldset(); ?>
            <h3><?=lang('sign_in_heading'); ?></h3>
            <?php if (isset($sign_in_error)) : ?>
            <div class="grid_12 alpha omega">
                <div class="form_error"><?=$sign_in_error; ?></div>
            </div>
            <div class="clear"></div>
            <?php endif; ?>
            <?php if (isset($sign_in_username_email_error)) : ?>
        	<div class="grid_12 alpha omega">
                	<span class="field_error"><?=$sign_in_username_email_error; ?></span>
                 </div>
            <div class="clear"></div>
                <?php endif; ?>
            <div class="grid_5 alpha">
                <?=form_label(lang('sign_in_username_email'), 'sign_in_username_email'); ?>
            </div>
           
                <?=form_input(array('name' => 'sign_in_username_email', 'id' => 'sign_in_username_email', 'value' => set_value('sign_in_username_email'), 'maxlength' => '24', 'class' => 'grid_6 omega validate[required]')); ?>
                <?=form_error('sign_in_username_email'); ?>
                
            <div class="clear"></div>
            <div class="grid_5 alpha">
                <?=form_label(lang('sign_in_password'), 'sign_in_password'); ?>
            </div>
           
                <?=form_password(array('name' => 'sign_in_password', 'id' => 'sign_in_password', 'value' => set_value('sign_in_password'), 'class' => 'grid_6 omega validate[required]')); ?>
                <?=form_error('sign_in_password'); ?>
  
            <div class="clear"></div>
            <?php if (isset($recaptcha)) : ?>
            <div class="prefix_4 grid_8 alpha omega">
                <?=$recaptcha; ?>
            </div>
            <?php if (isset($sign_in_recaptcha_error)) : ?>
            <div class="prefix_4 grid_8 alpha omega">
                <span class="field_error"><?=$sign_in_recaptcha_error; ?></span>
            </div>
            <?php endif; ?>
            <div class="clear"></div>
            <?php endif; ?>
            <div class="prefix_5 grid_8 alpha omega">
                <span>
                    <?=form_button(array('type' => 'submit', 'class' => 'button', 'content' => lang('sign_in_sign_in'))); ?>
                </span>
                <span id="remember_me">
                    <?=form_checkbox(array('name' => 'sign_in_remember', 'id' => 'sign_in_remember', 'value' => 'checked', 'checked' => $this -> input -> post('sign_in_remember'), 'class' => 'checkbox')); ?>
                    <?=form_label(lang('sign_in_remember_me'), 'sign_in_remember'); ?>
                </span>
            </div>
            <div class="clear"></div>
            <div class="prefix_5 grid_8 alpha omega">
                <p><?=anchor('account/forgot_password', lang('sign_in_forgot_your_password')); ?></p>
                <p><?=sprintf(lang('sign_in_dont_have_account'), anchor('account/sign_up', lang('sign_in_sign_up_now'))); ?></p>
            </div>
            <div class="clear"></div>
            <?=form_fieldset_close(); ?>
            <?=form_close(); ?>
        </div>
        <!-- <div class="grid_12">
            <h3><?=sprintf(lang('sign_in_third_party_heading')); ?></h3>
            <ul>
                <?php foreach($this->config->item('third_party_auth_providers') as $provider) : ?>
                <li class="third_party <?=$provider; ?>"><?=anchor('account/connect_' . $provider, lang('connect_' . $provider), array('title' => sprintf(lang('sign_in_with'), lang('connect_' . $provider)))); ?></li>
                <?php endforeach; ?>
            </ul>
            <div class="clear"></div>
        </div> -->
        <div class="clear"></div>
<?=$this -> load -> view('footer'); ?>