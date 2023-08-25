<div class="portlet light profile-sidebar-portlet">
	<div class="profile-userpic">
		<?php if(!empty(Session::get('empSession')['image'])): ?>
		<img style="height:150px;" src="<?php echo e(asset('images/AdminImages/'.$admindata['image'])); ?>" class="img-responsive"/>
		<?php else: ?>
			<img style="height:150px;" src="<?php echo e(asset('images/user.png')); ?>"/ class="img-responsive"/>
		<?php endif; ?>
	</div>
	<div class="profile-usertitle">
		<div class="profile-usertitle-name">
				<?php echo $admindata['name']?>
		</div>
		<div class="profile-usertitle-job">
		</div>
	</div>
	<div class="profile-usermenu">
		<ul class="nav">
			<?php if(Session::get('active')==3)
            {?>
            <li class="active ">
            <?php }
            else
            { ?> <li>
            <?php } ?>
				<a href="<?php echo e(action('AdminController@profile')); ?>">
				<i class="icon-home"></i>
				Overview </a>
			</li>
			<?php if(Session::get('active')==4)
            {?>
            <li class="active ">
            <?php }
            else
            { ?> <li>
            <?php } ?>
				<a  href="<?php echo e(action('AdminController@settings')); ?>">
				<i class="icon-settings"></i>
				Account Settings </a>
			</li>
		</ul>
	</div>
</div>