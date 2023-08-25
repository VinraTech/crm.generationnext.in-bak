<!-- 1,2,3,4 -->
<?php use App\Http\Controllers\ModuleController; 
$getallModules =  ModuleController::getModules(); 
?>
<div class="page-sidebar-wrapper">
	<div class="page-sidebar navbar-collapse collapse">
		<ul class="page-sidebar-menu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
			<?php if(Session::get('active')=='dashboard'){?>
            <li class="start active ">
            <?php }else{ ?> 
            <li>
            <?php } ?>
				<a href="{{ url('s/admin/dashboard') }}">
				<i class="icon-home"></i>
				<span class="title">Dashboard</span>
				</a>
			</li>
			<?php foreach($getallModules as $module) { 
				if(empty($module['undermodules'])) { 
					if($module['parent_id'] !="ROOT"){?>
						<li <?php if(Session::get('active')== $module['session_value'] ) { ?> class="start active"<?php } ?> >
							<a href="{{ url($module['view_route'])}}">
								<i class="{{ $module['icon']}}"></i>
								<span class="title">{{ $module['name'] }}</span>
							</a>
						</li>
				<?php } } else{ 
				$sessionvalues = explode(',',$module['session_value']);
				?>
				<?php if(in_array(Session::get('active'),$sessionvalues)) {?>
            <li class="start active ">
            <?php }else{ ?> 
            <li>
            <?php } ?>
				<a href="javascript:;">
				<i class="{{ $module['icon']}}"></i>
				<span class="title">{{ $module['name'] }}</span>
				<span class="arrow "></span>
				</a>
				<ul class="sub-menu">
					@foreach($module['undermodules'] as $key => $undermodule)
					<?php if(Session::get('active')== $undermodule['session_value']){?>
		            <li class="start active ">
		            <?php }else{ ?> 
		            <li>
		            <?php } ?>
						<a href="{{ url($undermodule['view_route']) }}">
							{{ $undermodule['name'] }}
						</a>
					</li>
					@endforeach
				</ul>
			</li>	
			<?php }
			} ?>
		</ul>
	</div>
</div>