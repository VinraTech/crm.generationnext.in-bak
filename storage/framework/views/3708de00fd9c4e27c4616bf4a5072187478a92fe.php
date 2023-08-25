<style>
.navbar-brand {
  font-size: 36px;
}
.logo-default{max-width: 175px; max-height: 50px; width: 100%; margin-top: -3px !important; min-height: 75px;}
.logo-default img{max-width: 100%; max-height: 100%; display: inline-block; float: none;}
.jquerySelectbox{
	margin-left: -12px;width: 467px;
}
</style>
<div class="page-header navbar navbar-fixed-top">
	<div class="page-header-inner">
		<div class="page-logo">
			<a href="<?php echo e(url('s/admin/dashboard')); ?>" class="navbar-brand logo-default" style=" margin-left: 5px;">
				<img src="<?php echo e(asset('images/logo.png')); ?>" />
			</a>
			<div class="menu-toggler sidebar-toggler">
			</div>
		</div>
		<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
		</a>
		<div class="page-top">
			<div class="top-menu">
				<ul class="nav navbar-nav pull-right">
					<li class="dropdown dropdown-user dropdown-dark">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
						<span class="username username-hide-on-mobile">
						<?php echo e(Session::get('empSession')['name']); ?> </span>
						<?php if(!empty(Session::get('empSession')['image'])): ?>
							<img alt="" class="img-circle" src="<?php echo e(asset('images/AdminImages/'.Session::get('empSession')['image'])); ?>"/>
						<?php else: ?>
							<img alt="" class="img-circle" src="<?php echo e(asset('images/user.png')); ?>"/>
						<?php endif; ?>
						</a>
						<ul class="dropdown-menu dropdown-menu-default">
							<li>
								<a href="<?php echo e(action('AdminController@profile')); ?>">
									<i class="icon-user"></i> My Profile 
								</a>
							</li>
							<li>
								<a href="<?php echo e(action('AdminController@logout')); ?>">
								<i class="icon-key"></i> Log Out </a>
							</li>
						</ul>
					</li>
				</ul>
			</div>
			<?php use App\NotificationEmployee;
			$notifications = NotificationEmployee::getNotifications(Session::get('empSession')['id']); ?>
			<div class="top-menu">
				<ul class="nav navbar-nav pull-right">
					<li class="separator hide">
					</li>
					<li class="dropdown dropdown-extended dropdown-notification dropdown-dark" id="header_notification_bar">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
						<i class="icon-bell"></i>
						<span class="badge badge-success"><?php echo e($notifications['count']); ?></span>
						</a>
						<ul class="dropdown-menu">
							<li class="external">
								<h3><span class="bold">Notifications</span></h3>
							</li>
							<li>
								<ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283">
									<?php if(!empty($notifications['notifications'])): ?>
										<?php $__currentLoopData = $notifications['notifications']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notify): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<li>
												<a href="<?php echo e(url('/s/admin/view-notification/'.$notify['notification_id'])); ?>">
												<?php $timestring = NotificationEmployee::time_elapsed_string($notify['created_at']) ?>
												<span class="time"><?php echo e($timestring); ?></span>
												<span class="details">
												<span class="label label-sm label-icon label-warning">
												<i class="fa fa-bell-o"></i>
												</span><?php echo strlen($notify['notificationdetails']['title']) > 20 ? substr($notify['notificationdetails']['title'],0,20)."..." : $notify['notificationdetails']['title'];?></span>
												</a>
											</li>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									<?php else: ?>
										<li>
											<a href="javascript:;">
											<span class="details">
											</span>Nothing found.
											</a>
										</li>
									<?php endif; ?>
								</ul>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>