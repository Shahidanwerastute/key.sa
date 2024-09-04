@extends('frontend.layouts.template')

@section('content')
<style>
    .gm-style-iw {
        background-color: transparent;
        box-shadow: none;
    }

	.get_directions {
		margin-<?php echo ($lang == 'eng' ? 'left' : 'right'); ?>: 5px !important;
	}
	.actionBTNs {
		width: 240px;
		margin-bottom: 4px;
	}
	.btn-booking {
		margin-left: 5px !important;
	}
</style>
	<div id="wrapper">
		<div class="w1">
			<div class="content">
				<div class="canvasGmap_outer" style="overflow:hidden;height:406px;width:100%;">
					<div class="canvasGmap" style="height:440px;width:100%;">
						<div id="map_canvas" style="width: 100%; height: 440px; position: relative; overflow: hidden;">
							<div style="height: 100%; width: 100%; position: absolute; top: 0px; left: 0px; background-color: rgb(229, 227, 223);">
								<div class="gm-style" style="position: absolute; left: 0px; top: 0px; overflow: hidden; width: 100%; height: 100%; z-index: 0;">
									<div style="position: absolute; left: 0px; top: 0px; overflow: hidden; width: 100%; height: 100%; z-index: 0; cursor: url(&quot;http://maps.gstatic.com/mapfiles/openhand_8_8.cur&quot;) 8 8, default;">
										<div style="position: absolute; left: 0px; top: 0px; z-index: 1; width: 100%; transform-origin: 0px 0px 0px; transform: matrix(1, 0, 0, 1, -1012, -128);">
											<div style="position: absolute; left: 0px; top: 0px; z-index: 100; width: 100%;">
												<div style="position: absolute; left: 0px; top: 0px; z-index: 0;">
													<div aria-hidden="true" style="position: absolute; left: 0px; top: 0px; z-index: 1; visibility: inherit;">
														<div style="width: 256px; height: 256px; position: absolute; left: 1496px; top: 164px;"></div>
														<div style="width: 256px; height: 256px; position: absolute; left: 1240px; top: 164px;"></div>
														<div style="width: 256px; height: 256px; position: absolute; left: 1496px; top: -92px;"></div>
														<div style="width: 256px; height: 256px; position: absolute; left: 1496px; top: 420px;"></div>
														<div style="width: 256px; height: 256px; position: absolute; left: 1752px; top: 164px;"></div>
														<div style="width: 256px; height: 256px; position: absolute; left: 1240px; top: 420px;"></div>
														<div style="width: 256px; height: 256px; position: absolute; left: 1240px; top: -92px;"></div>
														<div style="width: 256px; height: 256px; position: absolute; left: 1752px; top: -92px;"></div>
														<div style="width: 256px; height: 256px; position: absolute; left: 1752px; top: 420px;"></div>
														<div style="width: 256px; height: 256px; position: absolute; left: 2008px; top: 164px;"></div>
														<div style="width: 256px; height: 256px; position: absolute; left: 984px; top: 164px;"></div>
														<div style="width: 256px; height: 256px; position: absolute; left: 984px; top: -92px;"></div>
														<div style="width: 256px; height: 256px; position: absolute; left: 2008px; top: -92px;"></div>
														<div style="width: 256px; height: 256px; position: absolute; left: 984px; top: 420px;"></div>
														<div style="width: 256px; height: 256px; position: absolute; left: 2008px; top: 420px;"></div>
													</div>
												</div>
											</div>
											<div style="position: absolute; left: 0px; top: 0px; z-index: 101; width: 100%;"></div>
											<div style="position: absolute; left: 0px; top: 0px; z-index: 102; width: 100%;"></div>
											<div style="position: absolute; left: 0px; top: 0px; z-index: 103; width: 100%;">
												<div style="position: absolute; left: 0px; top: 0px; z-index: -1;">
													<div aria-hidden="true" style="position: absolute; left: 0px; top: 0px; z-index: 1; visibility: inherit;">
														<div style="width: 256px; height: 256px; overflow: hidden; position: absolute; left: 1496px; top: 164px;"></div>
														<div style="width: 256px; height: 256px; overflow: hidden; position: absolute; left: 1240px; top: 164px;">
															<canvas draggable="false" height="256" width="256" style="user-select: none; position: absolute; left: 0px; top: 0px; height: 256px; width: 256px;"></canvas>
														</div>
														<div style="width: 256px; height: 256px; overflow: hidden; position: absolute; left: 1496px; top: -92px;"></div>
														<div style="width: 256px; height: 256px; overflow: hidden; position: absolute; left: 1496px; top: 420px;"></div>
														<div style="width: 256px; height: 256px; overflow: hidden; position: absolute; left: 1752px; top: 164px;">
															<canvas draggable="false" height="256" width="256" style="user-select: none; position: absolute; left: 0px; top: 0px; height: 256px; width: 256px;"></canvas>
														</div>
														<div style="width: 256px; height: 256px; overflow: hidden; position: absolute; left: 1240px; top: 420px;">
															<canvas draggable="false" height="256" width="256" style="user-select: none; position: absolute; left: 0px; top: 0px; height: 256px; width: 256px;"></canvas>
														</div>
														<div style="width: 256px; height: 256px; overflow: hidden; position: absolute; left: 1240px; top: -92px;">
															<canvas draggable="false" height="256" width="256" style="user-select: none; position: absolute; left: 0px; top: 0px; height: 256px; width: 256px;"></canvas>
														</div>
														<div style="width: 256px; height: 256px; overflow: hidden; position: absolute; left: 1752px; top: -92px;"></div>
														<div style="width: 256px; height: 256px; overflow: hidden; position: absolute; left: 1752px; top: 420px;">
															<canvas draggable="false" height="256" width="256" style="user-select: none; position: absolute; left: 0px; top: 0px; height: 256px; width: 256px;"></canvas>
														</div>
														<div style="width: 256px; height: 256px; overflow: hidden; position: absolute; left: 2008px; top: 164px;"></div>
														<div style="width: 256px; height: 256px; overflow: hidden; position: absolute; left: 984px; top: 164px;">
															<canvas draggable="false" height="256" width="256" style="user-select: none; position: absolute; left: 0px; top: 0px; height: 256px; width: 256px;"></canvas>
														</div>
														<div style="width: 256px; height: 256px; overflow: hidden; position: absolute; left: 984px; top: -92px;">
															<canvas draggable="false" height="256" width="256" style="user-select: none; position: absolute; left: 0px; top: 0px; height: 256px; width: 256px;"></canvas>
														</div>
														<div style="width: 256px; height: 256px; overflow: hidden; position: absolute; left: 2008px; top: -92px;"></div>
														<div style="width: 256px; height: 256px; overflow: hidden; position: absolute; left: 984px; top: 420px;"></div>
														<div style="width: 256px; height: 256px; overflow: hidden; position: absolute; left: 2008px; top: 420px;">
															<canvas draggable="false" height="256" width="256" style="user-select: none; position: absolute; left: 0px; top: 0px; height: 256px; width: 256px;"></canvas>
														</div>
													</div>
												</div>
											</div>

										</div>
										<div style="position: absolute; left: 0px; top: 0px; z-index: 2; width: 100%; height: 100%;"></div>
										<div style="position: absolute; left: 0px; top: 0px; z-index: 3; width: 100%; height: 100%;"></div>
										<div style="position: absolute; left: 0px; top: 0px; z-index: 4; width: 100%; transform-origin: 0px 0px 0px; transform: matrix(1, 0, 0, 1, -1012, -128);">
											<div style="position: absolute; left: 0px; top: 0px; z-index: 104; width: 100%;"></div>
											<div style="position: absolute; left: 0px; top: 0px; z-index: 105; width: 100%;"></div>
											<div style="position: absolute; left: 0px; top: 0px; z-index: 106; width: 100%;"></div>
											<div style="position: absolute; left: 0px; top: 0px; z-index: 107; width: 100%;"></div>
										</div>
									</div>
									<div style="margin-left: 5px; margin-right: 5px; z-index: 1000000; position: absolute; left: 0px; bottom: 0px;"><a target="_blank" href="https://maps.google.com/maps?ll=21.581456,39.182251&amp;z=14&amp;t=m&amp;hl=en-US&amp;gl=US&amp;mapclient=apiv3" title="Click to see this area on Google Maps" style="position: static; overflow: visible; float: none; display: inline;">
											<div style="width: 66px; height: 26px; cursor: pointer;"><img src="{{custom::baseurl('public/frontend/images/google4.png')}}" draggable="false" style="position: absolute; left: 0px; top: 0px; width: 66px; height: 26px; user-select: none; border: 0px; padding: 0px; margin: 0px;"></div>
										</a></div>
									<div style="background-color: white; padding: 15px 21px; border: 1px solid rgb(171, 171, 171); font-family: Roboto, Arial, sans-serif; color: rgb(34, 34, 34); box-shadow: rgba(0, 0, 0, 0.2) 0px 4px 16px; z-index: 10000002; display: none; width: 256px; height: 148px; position: absolute; left: 392px; top: 130px;">
										<div style="padding: 0px 0px 10px; font-size: 16px;">Map Data</div>
										<div style="font-size: 13px;">Map data ©2017 Google</div>
										<div style="width: 13px; height: 13px; overflow: hidden; position: absolute; opacity: 0.7; right: 12px; top: 12px; z-index: 10000; cursor: pointer;"><img src="{{custom::baseurl('public/frontend/images/mapcnt6.png')}}" draggable="false" style="position: absolute; left: -2px; top: -336px; width: 59px; height: 492px; user-select: none; border: 0px; padding: 0px; margin: 0px; max-width: none;"></div>
									</div>
									<div class="gmnoprint" style="z-index: 1000001; position: absolute; right: 71px; bottom: 0px; width: 121px;">
										<div draggable="false" class="gm-style-cc" style="user-select: none; height: 14px; line-height: 14px;">
											<div style="opacity: 0.7; width: 100%; height: 100%; position: absolute;">
												<div style="width: 1px;"></div>
												<div style="background-color: rgb(245, 245, 245); width: auto; height: 100%; margin-left: 1px;"></div>
											</div>
											<div style="position: relative; padding-right: 6px; padding-left: 6px; font-family: Roboto, Arial, sans-serif; font-size: 10px; color: rgb(68, 68, 68); white-space: nowrap; direction: ltr; text-align: right; vertical-align: middle; display: inline-block;"><a style="color: rgb(68, 68, 68); text-decoration: none; cursor: pointer; display: none;">Map Data</a><span style="">Map data ©2017 Google</span></div>
										</div>
									</div>
									<div class="gmnoscreen" style="position: absolute; right: 0px; bottom: 0px;">
										<div style="font-family: Roboto, Arial, sans-serif; font-size: 11px; color: rgb(68, 68, 68); direction: ltr; text-align: right; background-color: rgb(245, 245, 245);">Map data ©2017 Google</div>
									</div>
									<div class="gmnoprint gm-style-cc" draggable="false" style="z-index: 1000001; user-select: none; height: 14px; line-height: 14px; position: absolute; right: 0px; bottom: 0px;">
										<div style="opacity: 0.7; width: 100%; height: 100%; position: absolute;">
											<div style="width: 1px;"></div>
											<div style="background-color: rgb(245, 245, 245); width: auto; height: 100%; margin-left: 1px;"></div>
										</div>
										<div style="position: relative; padding-right: 6px; padding-left: 6px; font-family: Roboto, Arial, sans-serif; font-size: 10px; color: rgb(68, 68, 68); white-space: nowrap; direction: ltr; text-align: right; vertical-align: middle; display: inline-block;"><a href="https://www.google.com/intl/en-US_US/help/terms_maps.html" target="_blank" style="text-decoration: none; cursor: pointer; color: rgb(68, 68, 68);">Terms of Use</a></div>
									</div>
									<div style="cursor: pointer; width: 25px; height: 25px; overflow: hidden; display: none; margin: 10px 14px; position: absolute; top: 0px; right: 0px;"><img src="{{custom::baseurl('public/frontend/images/sv9.png')}}" draggable="false" class="gm-fullscreen-control" style="position: absolute; left: -52px; top: -86px; width: 164px; height: 175px; user-select: none; border: 0px; padding: 0px; margin: 0px;"></div>
									<div draggable="false" class="gm-style-cc" style="user-select: none; height: 14px; line-height: 14px; display: none; position: absolute; right: 0px; bottom: 0px;">
										<div style="opacity: 0.7; width: 100%; height: 100%; position: absolute;">
											<div style="width: 1px;"></div>
											<div style="background-color: rgb(245, 245, 245); width: auto; height: 100%; margin-left: 1px;"></div>
										</div>
										<div style="position: relative; padding-right: 6px; padding-left: 6px; font-family: Roboto, Arial, sans-serif; font-size: 10px; color: rgb(68, 68, 68); white-space: nowrap; direction: ltr; text-align: right; vertical-align: middle; display: inline-block;"><a target="_new" title="Report errors in the road map or imagery to Google" href="https://www.google.com/maps/@21.5814559,39.1822508,14z/data=!10m1!1e1!12b1?source=apiv3&amp;rapsrc=apiv3" style="font-family: Roboto, Arial, sans-serif; font-size: 10px; color: rgb(68, 68, 68); text-decoration: none; position: relative;">Report a map error</a></div>
									</div>
									<div class="gmnoprint gm-bundled-control gm-bundled-control-on-bottom" draggable="false" controlwidth="28" controlheight="93" style="margin: 10px; user-select: none; position: absolute; bottom: 107px; right: 28px;">
										<div class="gmnoprint" controlwidth="28" controlheight="55" style="position: absolute; left: 0px; top: 38px;">
											<div draggable="false" style="user-select: none; box-shadow: rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px; border-radius: 2px; cursor: pointer; background-color: rgb(255, 255, 255); width: 28px; height: 55px;">
												<div title="Zoom in" style="position: relative; width: 28px; height: 27px; left: 0px; top: 0px;">
													<div style="overflow: hidden; position: absolute; width: 15px; height: 15px; left: 7px; top: 6px;"><img src="{{custom::baseurl('public/frontend/images/tmapctrl.png')}}" draggable="false" style="position: absolute; left: 0px; top: 0px; user-select: none; border: 0px; padding: 0px; margin: 0px; max-width: none; width: 120px; height: 54px;"></div>
												</div>
												<div style="position: relative; overflow: hidden; width: 67%; height: 1px; left: 16%; background-color: rgb(230, 230, 230); top: 0px;"></div>
												<div title="Zoom out" style="position: relative; width: 28px; height: 27px; left: 0px; top: 0px;">
													<div style="overflow: hidden; position: absolute; width: 15px; height: 15px; left: 7px; top: 6px;"><img src="{{custom::baseurl('public/frontend/images/tmapctrl.png')}}" draggable="false" style="position: absolute; left: 0px; top: -15px; user-select: none; border: 0px; padding: 0px; margin: 0px; max-width: none; width: 120px; height: 54px;"></div>
												</div>
											</div>
										</div>
										<div class="gm-svpc" controlwidth="28" controlheight="28" style="background-color: rgb(255, 255, 255); box-shadow: rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px; border-radius: 2px; width: 28px; height: 28px; cursor: url(&quot;http://maps.gstatic.com/mapfiles/openhand_8_8.cur&quot;) 8 8, default; position: absolute; left: 0px; top: 0px;">
											<div style="position: absolute; left: 1px; top: 1px;"></div>
											<div style="position: absolute; left: 1px; top: 1px;">
												<div aria-label="Street View Pegman Control" style="width: 26px; height: 26px; overflow: hidden; position: absolute; left: 0px; top: 0px;"><img src="{{custom::baseurl('public/frontend/images/cb_scout5.png')}}" draggable="false" style="position: absolute; left: -147px; top: -26px; width: 215px; height: 835px; user-select: none; border: 0px; padding: 0px; margin: 0px; max-width: none;"></div>
												<div aria-label="Pegman is on top of the Map" style="width: 26px; height: 26px; overflow: hidden; position: absolute; left: 0px; top: 0px; visibility: hidden;"><img src="{{custom::baseurl('public/frontend/images/cb_scout5.png')}}" draggable="false" style="position: absolute; left: -147px; top: -52px; width: 215px; height: 835px; user-select: none; border: 0px; padding: 0px; margin: 0px; max-width: none;"></div>
												<div aria-label="Street View Pegman Control" style="width: 26px; height: 26px; overflow: hidden; position: absolute; left: 0px; top: 0px; visibility: hidden;"><img src="{{custom::baseurl('public/frontend/images/cb_scout5.png')}}" draggable="false" style="position: absolute; left: -147px; top: -78px; width: 215px; height: 835px; user-select: none; border: 0px; padding: 0px; margin: 0px; max-width: none;"></div>
											</div>
										</div>
										<div class="gmnoprint" controlwidth="28" controlheight="0" style="display: none; position: absolute;">
											<div title="Rotate map 90 degrees" style="width: 28px; height: 28px; overflow: hidden; position: absolute; border-radius: 2px; box-shadow: rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px; cursor: pointer; background-color: rgb(255, 255, 255); display: none;"><img src="{{custom::baseurl('public/frontend/images/tmapctrl4.png')}}" draggable="false" style="position: absolute; left: -141px; top: 6px; width: 170px; height: 54px; user-select: none; border: 0px; padding: 0px; margin: 0px; max-width: none;"></div>
											<div class="gm-tilt" style="width: 28px; height: 28px; overflow: hidden; position: absolute; border-radius: 2px; box-shadow: rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px; top: 0px; cursor: pointer; background-color: rgb(255, 255, 255);"><img src="{{custom::baseurl('public/frontend/images/tmapctrl4.png')}}" draggable="false" style="position: absolute; left: -141px; top: -13px; width: 170px; height: 54px; user-select: none; border: 0px; padding: 0px; margin: 0px; max-width: none;"></div>
										</div>
									</div>
									<div class="gmnoprint" style="margin: 10px; z-index: 0; position: absolute; cursor: pointer; left: 0px; top: 0px;">
										<div class="gm-style-mtc" style="float: left;">
											<div draggable="false" title="Show street map" style="direction: ltr; overflow: hidden; text-align: center; position: relative; color: rgb(0, 0, 0); font-family: Roboto, Arial, sans-serif; user-select: none; font-size: 11px; background-color: rgb(255, 255, 255); padding: 8px; border-bottom-left-radius: 2px; border-top-left-radius: 2px; -webkit-background-clip: padding-box; background-clip: padding-box; box-shadow: rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px; min-width: 22px; font-weight: 500;">Map</div>
											<div style="background-color: white; z-index: -1; padding: 2px; border-bottom-left-radius: 2px; border-bottom-right-radius: 2px; box-shadow: rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px; position: absolute; left: 0px; top: 31px; text-align: left; display: none;">
												<div draggable="false" title="Show street map with terrain" style="color: rgb(0, 0, 0); font-family: Roboto, Arial, sans-serif; user-select: none; font-size: 11px; background-color: rgb(255, 255, 255); padding: 6px 8px 6px 6px; direction: ltr; text-align: left; white-space: nowrap;"><span role="checkbox" style="box-sizing: border-box; position: relative; line-height: 0; font-size: 0px; margin: 0px 5px 0px 0px; display: inline-block; background-color: rgb(255, 255, 255); border: 1px solid rgb(198, 198, 198); border-radius: 1px; width: 13px; height: 13px; vertical-align: middle;">
                      <div style="position: absolute; left: 1px; top: -2px; width: 13px; height: 11px; overflow: hidden; display: none;"><img src="{{custom::baseurl('public/frontend/images/imgs8.png')}}" draggable="false" style="position: absolute; left: -52px; top: -44px; user-select: none; border: 0px; padding: 0px; margin: 0px; max-width: none; width: 68px; height: 67px;"></div>
                      </span>
													<label style="vertical-align: middle; cursor: pointer;">Terrain</label>
												</div>
											</div>
										</div>
										<div class="gm-style-mtc" style="float: left;">
											<div draggable="false" title="Show satellite imagery" style="direction: ltr; overflow: hidden; text-align: center; position: relative; color: rgb(86, 86, 86); font-family: Roboto, Arial, sans-serif; user-select: none; font-size: 11px; background-color: rgb(255, 255, 255); padding: 8px; border-bottom-right-radius: 2px; border-top-right-radius: 2px; -webkit-background-clip: padding-box; background-clip: padding-box; box-shadow: rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px; border-left: 0px; min-width: 40px;">Satellite</div>
											<div style="background-color: white; z-index: -1; padding: 2px; border-bottom-left-radius: 2px; border-bottom-right-radius: 2px; box-shadow: rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px; position: absolute; right: 0px; top: 31px; text-align: left; display: none;">
												<div draggable="false" title="Show imagery with street names" style="color: rgb(0, 0, 0); font-family: Roboto, Arial, sans-serif; user-select: none; font-size: 11px; background-color: rgb(255, 255, 255); padding: 6px 8px 6px 6px; direction: ltr; text-align: left; white-space: nowrap;"><span role="checkbox" style="box-sizing: border-box; position: relative; line-height: 0; font-size: 0px; margin: 0px 5px 0px 0px; display: inline-block; background-color: rgb(255, 255, 255); border: 1px solid rgb(198, 198, 198); border-radius: 1px; width: 13px; height: 13px; vertical-align: middle;">
                      <div style="position: absolute; left: 1px; top: -2px; width: 13px; height: 11px; overflow: hidden;"><img src="{{custom::baseurl('public/frontend/images/imgs8.png')}}" draggable="false" style="position: absolute; left: -52px; top: -44px; user-select: none; border: 0px; padding: 0px; margin: 0px; max-width: none; width: 68px; height: 67px;"></div>
                      </span>
													<label style="vertical-align: middle; cursor: pointer;">Labels</label>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="location-form">
					<div class="form-holder" id="form">
						<div class="row">
							<div class="col-md-4 col-sm-6 ">
								<label>@lang('labels.city'):</label>
								<div class="fake-select">
									<select id="select_city">
										<option value="0">@lang('labels.select')</option>
                                        <?php $arrayCity = array();
                                        foreach($locations as $location){
                                            if(!in_array($location->c_eng_title,$arrayCity)){
                                            ?>
											<option value="<?php echo ($lang == "eng" ? $location->c_eng_title : $location->c_arb_title); ?>"><?php echo ($lang == "eng" ? $location->c_eng_title : $location->c_arb_title); ?></option>
										<?php }
										$arrayCity[] = $location->c_eng_title;
                                        }
                                        ?>

									</select>
								</div>
							</div>
							<div class="col-md-4 col-sm-6" style="display: none;">
								<label><?php echo ($lang == 'eng' ? 'Branches' : 'الفروع'); ?>:</label>
								<div class="fake-select">
									<select id="select_branch">
										<option value="0">@lang('labels.select')</option>

										<?php foreach($branches as $branch){
										$latLongArr = explode(',',$branch->map_latlng);
										if($branch->map_latlng != '' && count($latLongArr) > 0){
											$lat = $latLongArr[0];
											$long = $latLongArr[1];
										}else{
											$lat = "24.941739";
											$long = "46.711239";
										}
										?>
										<option data-lat="<?php echo $lat; ?>" data-lng="<?php echo $long; ?>" value="<?php echo ($lang == "eng" ? $branch->eng_title : $branch->arb_title); ?>"><?php echo ($lang == "eng" ? $branch->eng_title : $branch->arb_title); ?></option>
										<?php
										}?>

									</select>
								</div>
							</div>
							<div class="col-md-4 col-sm-6">
								<label>@lang('labels.airport'):</label>
								<div class="fake-select">
									<select id="select_airport">
										<option value="0">@lang('labels.select')</option>

                                        <?php foreach($airports as $airport){
												$latLongArr = explode(',',$airport->map_latlng);
												if($airport->map_latlng != '' && count($latLongArr) > 0){
													$lat = $latLongArr[0];
													$long = $latLongArr[1];
												}else{
													$lat = "24.941739";
													$long = "46.711239";
												}
                                            ?>
											<option data-lat="<?php echo $lat; ?>" data-lng="<?php echo $long; ?>" value="<?php echo ($lang == "eng" ? $airport->eng_title : $airport->arb_title); ?>"><?php echo ($lang == "eng" ? $airport->eng_title : $airport->arb_title); ?></option>
										<?php
										}?>

									</select>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="locationBoxes">
					<div class="container-lg">
						<main id="main">
							<header class="head">
								<h1><?php echo $content[$lang.'_title']; ?></h1>
								<?php echo $content[$lang.'_desc']; ?>
							</header>
							<div class="locations">
								<div class="box-holder">

									<div id="locs">
										<div class="simplePagerContainer">
											<ul class="pagi boxes"></ul>
										</div>
									</div>
								</div>
							</div>
						</main>
					</div>
				</div>
			</div>
		</div>
	</div>


	<script type="text/javascript">
        var map;
        var markers = [];
        var lastinfowindow;
        var locIndex;
        var values;
        var getFilter;
        var cityFilter;
        var airportFilter;
        var sideH;

        //Credit: MDN
        if ( !Array.prototype.forEach ) {
            Array.prototype.forEach = function(fn, scope) {
                for(var i = 0, len = this.length; i < len; ++i) {
                    fn.call(scope, this[i], i, this);
                }
            }
        }

		/*
		 This is the data as a JS array. It could be generated by CF of course. This
		 drives the map and the div on the side.
		 */

        var data = [

           <?php foreach($branches as $branch){
               $latLongArr = explode(',',$branch->map_latlng);
               if(count($latLongArr) === 2){
                   $lat = $latLongArr[0];
                   $long = $latLongArr[1];
			   }else{
                   $lat = "24.941739";
                   $long = "46.711239";
			   }
			   if($branch->is_airport == 1){
                   $type = "Airport";
			   }else{
                   $type = "Branch";
			   }
			   $phoneLbl = Lang::get('labels.phone');
			   $working_time = Lang::get('labels.working_time');
			   $map = Lang::get('labels.map');
               ?>
            {
               active_status:'<?php echo $branch->active_status; ?>',
                city:"<?php echo ($lang == "eng" ? $branch->c_eng_title : $branch->c_arb_title); ?>",
                name:'<b><?php echo ($lang == "eng" ? $branch->eng_title : $branch->arb_title); ?> </b>',
                address:'<?php echo ($lang == "eng" ? str_replace("'","\'",$branch->address_line_1) : str_replace("'","\'",$branch->address_line_2)); ?>',
              <?php if($branch->phone1 != ""){ ?>
                phone:'<span><?php echo $phoneLbl; ?>:<?php echo $branch->phone1; ?></span>',
               <?php }else{ ?>
                phone:'',
               <?php } if($branch->mobile != ""){ ?>
                mobile:'<span>Mobile:<?php echo $branch->mobile; ?></span>',
               <?php }else{ ?>
                mobile:'',
               <?php } ?>
		   		email:'<span style="display:none;">Email:<?php echo $branch->email; ?></span>',
		   		id:'<?php echo $branch->branch_id; ?>',
               <?php if($branch->opening_hours != ""){ ?>
                worktiming:'<?php echo $working_time;?>: <?php echo $branch->opening_hours; ?>',
               <?php }else{ ?>
               worktiming:'',
               <?php } ?>
               locating:'<?php echo $map; ?>: <a href="#" class="underline">View Location</a>',
                type:'<?php echo $type; ?>',
                lat:<?php echo $lat; ?>,
                lng:<?php echo $long; ?>
            },

            <?php } ?>

        ];



        //A utility function that translates a given type to an icon
        function getIcon(type) {

            var get_data_type = type.toLowerCase();
            var types = get_data_type.replace(/\s+/g,"");

            switch(types) {
				case "branch": return "<?php echo $base_url; ?>/public/frontend/images/main-pointer.png?v=0.1";
				case "airport": return "<?php echo $base_url; ?>/public/frontend/images/map-pointer2.png";
				case "headoffice": return "<?php echo $base_url; ?>/public/frontend/images/map-pointer.png";
				default: return "<?php echo $base_url; ?>/public/frontend/images/main-pointer.png?v=0.1";
            }
        }




        function initialize() {

            var latlng = new google.maps.LatLng(21.938789,43.6308741);
            var myOptions = {
                zoom: 5,
                center: latlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
				gestureHandling: 'greedy'
            };

            map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);

            var all_checkboxes = $('input[type="checkbox"]');

            var selTypes = [];
            for(var i=0, len=all_checkboxes.length; i<len; i++) {
                selTypes.push($(all_checkboxes[i]).val());
            }

            data.forEach(function(mapData,idx) {


                var marker = new google.maps.Marker({
                    map: map,
                    position: new google.maps.LatLng(mapData.lat,mapData.lng),
                    title: mapData.city,
                    icon: getIcon(mapData.type)
                });


                // This is content for marker when we click on any of them
               // var contentHtml = "<div class='popupmap' style='width:260px;height:120px'><h4 class='abcd'>"+mapData.city+"</h4>"+mapData.name+"<br>"+mapData.address +"</div>";

				var contentHtml = getMarkerPopup(mapData);

				var infowindow = new google.maps.InfoWindow({
                    content: contentHtml
                });

                google.maps.event.addListener(marker, 'click', function() {
                    $(".mapPopUp").parent().parent().parent().parent().hide();
                    infowindow.open(map,marker);
                });

                marker.locid = idx+1;
                marker.infowindow = infowindow;
                markers[markers.length] = marker;

                // Creating block to display the sections of city etc
				var bookBtnMainHtml = '';
				bookBtnMainHtml += '<a href="javascript:void(0);" data-lat="'+mapData.lat+'" data-lng="'+mapData.lng+'" class="printBtn get_directions"><?php echo ($lang == 'eng' ? 'Directions' : 'الاتجاهات'); ?></a>';

				if (mapData.active_status == '1')
				{
					bookBtnMainHtml += '<a href="javascript:void(0);" data-branch_id="'+mapData.id+'" class="btn btn-booking"><?php echo ($lang == 'eng' ? 'Book Now' : 'أحجز الآن'); ?></a>';
				}

				console.log(1);
				console.log(mapData);

				var branch_id = mapData.id;
				var city_slug = createSlug(mapData.city);
				var branch_slug = createSlug(mapData.name);
				var url_slug = branch_id + '/' + city_slug + '/' + branch_slug

                var sideHtml = '<li class="box"><div class="actionBTNs"><a href="<?php echo $lang_base_url.'/share/';?>'+url_slug+'" class="printBtn" target="_blank"><?php echo ($lang == 'eng' ? 'Share' : 'مشاركة'); ?></a>'+bookBtnMainHtml+'</div><address class="loc stores 411" data-locid="'+marker.locid+'"><h2><a href="#">'+mapData.city+'</a></h2>';
                sideHtml += '<strong>'+mapData.name+'</strong>';
                sideHtml += '<span>'+mapData.address+'</span>';
                sideHtml += '<span>'+mapData.phone+'</span>';
                sideHtml += '<span>'+mapData.worktiming+'</span>';
                sideHtml += '<span>'+mapData.locating + '</span></address></li>';

                $("#locs ul.pagi").append(sideHtml);


            });

        }


        // Display the city in map when we click on city title from discription
        $(document).on("click",".loc",function() {
            var thisloc = $(this).data("locid");
            for(var i=0; i<markers.length; i++) {
                if(markers[i].locid == thisloc) {

                    //get the latlong
                    if(lastinfowindow instanceof google.maps.InfoWindow) lastinfowindow.close();

                    map.panTo(markers[i].getPosition());
                    map.setZoom(15);
                    markers[i].infowindow.open(map, markers[i]);

                    lastinfowindow = markers[i].infowindow;
                }
            }
        });

        $(document).on("click",".loc",function() {
            var thisloc = $(this).data("locid");
            for(var i=0; i<markers.length; i++) {
                if(markers[i].locid == thisloc) {

                    //get the latlong
                    if(lastinfowindow instanceof google.maps.InfoWindow) lastinfowindow.close();

                    map.panTo(markers[i].getPosition());
                    map.setZoom(15);
                    markers[i].infowindow.open(map, markers[i]);

                    lastinfowindow = markers[i].infowindow;
                }
            }
        });

		/*
		 Run on every change to the checkboxes. If you add more checkboxes to the page,
		 we should use a class to make this more specific.
		 */

        function doFilter() {
            $("#select_airport").prop('selectedIndex',0);
			$("#select_branch").prop('selectedIndex',0);

            if(!locIndex) {
                locIndex = {};
                //I reverse index markers to figure out the position of loc to marker index
                for(var x=0, len=markers.length; x<len; x++) {
                    locIndex[markers[x].locid] = x;
                }
            }

            var checkboxes_values = $("input[type=checkbox]:checked");
            var if_city_already_there = $("#select_city option:selected").val();


            var selTypes = [];
            for(var i=0, len=checkboxes_values.length; i<len; i++) {
                selTypes.push($(checkboxes_values[i]).val());
            }



            if (if_city_already_there == false) {

                var latilng = new google.maps.LatLng(24.73435301041049,46.735990047454834);

                var myOptions = {
                    zoom: 5,
                    center: latilng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
					gestureHandling: 'greedy'
                };

                map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);

                sideH = '';
                data.forEach(function(getFilter,idx) {

                    if($.inArray(getFilter.type, selTypes) > -1) {


                        var marker_new = new google.maps.Marker({
                            map: map,
                            position: new google.maps.LatLng(getFilter.lat,getFilter.lng),
                            title: getFilter.city,
                            icon: getIcon(getFilter.type)
                        });


                        // This is content for marker when we click on any of them
                       // var contentH = "<div style='width:260px;height:120px'><h4>"+getFilter.city+"</h4>"+getFilter.name+"<br>"+getFilter.address +"</div>";
                        var contentH = getMarkerPopup(getFilter);

						var infowindow = new google.maps.InfoWindow({
                            content: contentH
                        });

                        google.maps.event.addListener(marker_new, 'click', function() {
                            $(".mapPopUp").parent().parent().parent().parent().hide();
                            infowindow.open(map,marker_new);
                        });

                        marker_new.locid = idx+1;
                        marker_new.infowindow = infowindow;
                        markers[markers.length] = marker_new;


                        // Creating block to display the sections of city etc
						var bookBtnFilterHtml = '';
						bookBtnFilterHtml += '<a href="javascript:void(0);" data-lat="'+getFilter.lat+'" data-lng="'+getFilter.lng+'" class="printBtn get_directions"><?php echo ($lang == 'eng' ? 'Directions' : 'الاتجاهات'); ?></a>';

						if (getFilter.active_status == '1')
						{
							bookBtnFilterHtml += '<a href="javascript:void(0);" data-branch_id="'+getFilter.id+'" class="btn btn-booking"><?php echo ($lang == 'eng' ? 'Book Now' : 'أحجز الآن'); ?></a>';
						}

						console.log(2);
						console.log(getFilter);

						var branch_id = getFilter.id;
						var city_slug = createSlug(getFilter.city);
						var branch_slug = createSlug(getFilter.name);
						var url_slug = branch_id + '/' + city_slug + '/' + branch_slug

                         sideH += '<li class="box"><div class="actionBTNs"><a href="<?php echo $lang_base_url.'/share/';?>'+url_slug+'" class="printBtn" target="_blank"><?php echo ($lang == 'eng' ? 'Share' : 'مشاركة'); ?></a>'+bookBtnFilterHtml+'</div><address class="loc stores 538" data-locid="'+marker_new.locid+'"><h2><a href="#">'+getFilter.city+'</a></h2>';
                        sideH += '<strong>'+getFilter.name+'</strong>';
                        sideH += '<span>'+getFilter.address+'</span>';
                        sideH += '<span>'+getFilter.phone+'</span>';
                        sideH += '<span>'+getFilter.worktiming+'</span>';
                        sideH += '<span>'+getFilter.locating + '</span></address></li>';

                    }

                });

                $("#locs ul.pagi").html(sideH);

            }
            else { //if City is already selected

                selTypes = [];
                for(var i=0, len=checkboxes_values.length; i<len; i++) {
                    selTypes.push($(checkboxes_values[i]).val());
                }
                console.log(selTypes.length);
                if (selTypes != '') { // Display City data as per selected checkbox

                    var myOptions = {
                        zoom: 8,
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
						gestureHandling: 'greedy'
                    };

                    var bounds = new google.maps.LatLngBounds();
                    map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);


                    sideH = '';
                    data.forEach(function(getFilter,idx) {

                        if(if_city_already_there == getFilter.city){

                            // Find if selected
                            if($.inArray(getFilter.type, selTypes) > -1) {

                                // This will get all the coordinates of different type like Airport, branches and Home office etc
                                var latlng = new google.maps.LatLng(getFilter.lat, getFilter.lng);
                                // Increase the bounds to take this point
                                bounds.extend(latlng);

                                var marker_new = new google.maps.Marker({
                                    map: map,
                                    position: new google.maps.LatLng(getFilter.lat,getFilter.lng),
                                    title: getFilter.city,
                                    icon: getIcon(getFilter.type)
                                });


                                // This is content for marker when we click on any of them
                                //var contentH = "<div style='width:260px;height:120px'><h4>"+getFilter.city+"</h4>"+getFilter.name+"<br>"+getFilter.address +"</div>";

                                var contentH = getMarkerPopup(getFilter);

								var infowindow = new google.maps.InfoWindow({
                                    content: contentH
                                });

                                google.maps.event.addListener(marker_new, 'click', function() {
                                    $(".mapPopUp").parent().parent().parent().parent().hide();
                                    infowindow.open(map,marker_new);
                                });

                                marker_new.locid = idx+1;
                                marker_new.infowindow = infowindow;
                                markers[markers.length] = marker_new;

                                // Creating block to display the sections of city etc
								var bookBtnFilHtml = '';
								bookBtnFilHtml += '<a href="javascript:void(0);" data-lat="'+getFilter.lat+'" data-lng="'+getFilter.lng+'" class="printBtn get_directions"><?php echo ($lang == 'eng' ? 'Directions' : 'الاتجاهات'); ?></a>';

								if (getFilter.active_status == '1')
								{
									bookBtnFilHtml += '<a href="javascript:void(0);" data-branch_id="'+getFilter.id+'" class="btn btn-booking"><?php echo ($lang == 'eng' ? 'Book Now' : 'أحجز الآن'); ?></a>';
								}

								console.log(3);
								console.log(getFilter);

								var branch_id = getFilter.id;
								var city_slug = createSlug(getFilter.city);
								var branch_slug = createSlug(getFilter.name);
								var url_slug = branch_id + '/' + city_slug + '/' + branch_slug

                                sideH += '<li class="box"><div class="actionBTNs"><a href="<?php echo $lang_base_url.'/share/';?>'+url_slug+'" class="printBtn" target="_blank"><?php echo ($lang == 'eng' ? 'Share' : 'مشاركة'); ?></a>'+bookBtnFilHtml+'</div><address class="loc stores 615" data-locid="'+marker_new.locid+'"><h2><a href="#">'+getFilter.city+'</a></h2>';
                                sideH += '<strong>'+getFilter.name+'</strong>';
                                sideH += '<span>'+getFilter.address+'</span>';
                                sideH += '<span>'+getFilter.phone+'</span>';
                                sideH += '<span>'+getFilter.worktiming+'</span>';
                                sideH += '<span>'+getFilter.locating + '</span></address></li>';

                            }

                        }

                    });

                    //  Fit these bounds to the map
                    map.fitBounds(bounds);
                    $("#locs ul.pagi").html(sideH);


                }else{ //Display data when checkboxes aren't checked or unchecked but city is there in select box


                    var myOptions = {
                        zoom: 5,
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
						gestureHandling: 'greedy'
                    };

                    // Create a new viewpoint bound
                    var bounds = new google.maps.LatLngBounds();
                    map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);


                    var selected_city = $("#select_city").val();


                    var sideHCity = '';

                    data.forEach(function(cityFilter,idx) {

                        if( cityFilter.city === selected_city ) {

                            // This will get all the coordinates of different type like Airport, branches and Home office etc
                            var latlng = new google.maps.LatLng(cityFilter.lat, cityFilter.lng);
                            // Increase the bounds to take this point
                            bounds.extend(latlng);

                            var marker_new = new google.maps.Marker({
                                map: map,
                                position: new google.maps.LatLng(cityFilter.lat,cityFilter.lng),
                                title: cityFilter.city,
                                icon: getIcon(cityFilter.type)
                            });

                            // This is content for marker when we click on any of them
                           // var contentH = "<div style='width:260px;height:120px'><h4>"+cityFilter.city+"</h4>"+cityFilter.name+"<br>"+cityFilter.address +"</div>";

                            var contentH = getMarkerPopup(cityFilter);

							var infowindow = new google.maps.InfoWindow({
                                content: contentH
                            });

                            google.maps.event.addListener(marker_new, 'click', function() {
                                $(".mapPopUp").parent().parent().parent().parent().hide();
                                infowindow.open(map,marker_new);
                            });

                            marker_new.locid = idx+1;
                            marker_new.infowindow = infowindow;
                            markers[markers.length] = marker_new;

                            // Creating block to display the data into Store section under Map
							var bookBtnCityFilterHtml = '';
							bookBtnCityFilterHtml += '<a href="javascript:void(0);" data-lat="'+cityFilter.lat+'" data-lng="'+cityFilter.lng+'" class="printBtn get_directions"><?php echo ($lang == 'eng' ? 'Directions' : 'الاتجاهات'); ?></a>';

							if (cityFilter.active_status == '1')
							{
								bookBtnCityFilterHtml += '<a href="javascript:void(0);" data-branch_id="'+cityFilter.id+'" class="btn btn-booking"><?php echo ($lang == 'eng' ? 'Book Now' : 'أحجز الآن'); ?></a>';
							}

							console.log(4);
							console.log(cityFilter);

							var branch_id = cityFilter.id;
							var city_slug = createSlug(cityFilter.city);
							var branch_slug = createSlug(cityFilter.name);
							var url_slug = branch_id + '/' + city_slug + '/' + branch_slug

                            sideHCity += '<li class="box"><div class="actionBTNs"><a href="<?php echo $lang_base_url.'/share/';?>'+url_slug+'" class="printBtn" target="_blank"><?php echo ($lang == 'eng' ? 'Share' : 'مشاركة'); ?></a>'+bookBtnCityFilterHtml+'</div><address class="loc stores 691" data-locid="'+marker_new.locid+'"><h2><a href="#">'+cityFilter.city+'</a></h2>';
                            sideHCity += '<strong>'+cityFilter.name+'</strong>';
                            sideHCity += '<span>'+cityFilter.address+'</span>';
                            sideHCity += '<span>'+cityFilter.phone+'</span>';
                            sideHCity += '<span>'+cityFilter.worktiming+'</span>';
                            sideHCity += '<span>'+cityFilter.locating + '</span></address></li>';

                        }

                    });

                    //  Fit these bounds to the map
                    map.fitBounds(bounds);

                    $("#locs ul.pagi").html(sideHCity);



                }


            }

        }



        function quickPagination(options){


            var defaults={
                pageSize:10,
                currentPage:1,
                holder:null,
                pagerLocation:"after"
            };

            var options=$.extend(defaults,options);

            return $("ul.pagi").each(function(){
                var selector=$(this);
                var pageCounter=1;

                selector.wrap("");
                selector.parents(".simplePagerContainer").find("ul.simplePagerNav").remove();

                selector.children().each(function(i){
                    if(i<pageCounter*options.pageSize&&i>=(pageCounter-1)*options.pageSize){
                        $(this).addClass("simplePagerPage"+pageCounter);
                    }
                    else{
                        $(this).addClass("simplePagerPage"+(pageCounter+1));pageCounter++;}});
                selector.children().hide();
                selector.children(".simplePagerPage"+options.currentPage).show();

                if(pageCounter<=1){return;}
                var pageNav="<ul class='simplePagerNav pagger pagination'>";

                for(i=1;i<=pageCounter;i++){
                    if(i==options.currentPage){
                        pageNav+="<li class='page-item currentPage simplePageNav"+i+"'><a rel='"+i+"' href='#' class='page-link'>"+i+"</a></li>";
                    }
                    else{
                        pageNav+="<li class='page-item simplePageNav"+i+"'><a rel='"+i+"' href='#' class='page-link'>"+i+"</a></li>";
                    }
                }

                pageNav+="</ul>";

                if(!options.holder){
                    switch(options.pagerLocation)
                    {
                        case"before":selector.before(pageNav);
                            break;
                        case"both":selector.before(pageNav);selector.after(pageNav);
                            break;
                        default:selector.after(pageNav);
                    }
                }
                else{
                    $(options.holder).append(pageNav);
                }

                selector.parent().find(".simplePagerNav a").click(function(){
                    var clickedLink=$(this).attr("rel");
                    options.currentPage=clickedLink;

                    if(options.holder){
                        //alert('here');
                        $(this).parent("li").parent("ul").parent(options.holder).find("li.currentPage").removeClass("currentPage");
                        $(this).parent("li").parent("ul").parent(options.holder).find("a[rel='"+clickedLink+"']").parent("li").addClass("currentPage");
                    }
                    else{
                        $(this).parent("li").parent("ul").parent(".simplePagerContainer").find("li.currentPage").removeClass("currentPage");
                        $(this).parent("li").parent("ul").parent(".simplePagerContainer").find("a[rel='"+clickedLink+"']").parent("li").addClass("currentPage");
                    }

                    selector.children().hide();selector.find(".simplePagerPage"+clickedLink).show();

                    return false;
                });
            });
        }


        $("input[type=checkbox]").on("click", function(){
            doFilter();
            quickPagination({pageSize:"8"});
        });

        $("input[type=checkbox]").on("click", function(){
            noFilter();
            quickPagination({pageSize:"8"});
        });

        // This function get called when none of the checkboxes are selected
        function noFilter() {


            var checkboxes_values = $("input[type=checkbox]:checked");
            var selected_city = $("#select_city option:selected").val();


            selTypes = [];
            for(var i=0, len=checkboxes_values.length; i<len; i++) {
                selTypes.push($(checkboxes_values[i]).val());
            }


            if(selTypes.length === 0 && selected_city == '0'){
                console.log("Nothing is selected");
                data.forEach(function(getFilter,idx) {

                    var marker_new = new google.maps.Marker({
                        map: map,
                        position: new google.maps.LatLng(getFilter.lat,getFilter.lng),
                        title: getFilter.city,
                        icon: getIcon(getFilter.type)
                    });

                    // This is content for marker when we click on any of them
                    //var contentH = "<div style='width:260px;height:120px'><h4>"+getFilter.city+"</h4>"+getFilter.name+"<br>"+getFilter.address +"</div>";

                    var contentH = getMarkerPopup(getFilter);

					var infowindow = new google.maps.InfoWindow({
                        content: contentH
                    });

                    google.maps.event.addListener(marker_new, 'click', function() {
                        $(".mapPopUp").parent().parent().parent().parent().hide();
                        infowindow.open(map,marker_new);
                    });

                    marker_new.locid = idx+1;
                    marker_new.infowindow = infowindow;
                    markers[markers.length] = marker_new;

                    // Creating block to display the data into Store section under Map
					var bookBtnFiltHtml = '';
					bookBtnFiltHtml += '<a href="javascript:void(0);" data-lat="'+getFilter.lat+'" data-lng="'+getFilter.lng+'" class="printBtn get_directions"><?php echo ($lang == 'eng' ? 'Directions' : 'الاتجاهات'); ?></a>';

					if (getFilter.active_status == '1')
					{
						bookBtnFiltHtml += '<a href="javascript:void(0);" data-branch_id="'+getFilter.id+'" class="btn btn-booking"><?php echo ($lang == 'eng' ? 'Book Now' : 'أحجز الآن'); ?></a>';
					}

					console.log(5);
					console.log(getFilter);

					var branch_id = getFilter.id;
					var city_slug = createSlug(getFilter.city);
					var branch_slug = createSlug(getFilter.name);
					var url_slug = branch_id + '/' + city_slug + '/' + branch_slug

                    var sideHC = '<li class="box"><div class="actionBTNs"><a href="<?php echo $lang_base_url.'/share/';?>'+url_slug+'" class="printBtn" target="_blank"><?php echo ($lang == 'eng' ? 'Share' : 'مشاركة'); ?></a>'+bookBtnFiltHtml+'</div><address class="loc stores 855" data-locid="'+marker_new.locid+'"><h2><a href="#">'+getFilter.city+'</a></h2>';
                    sideHC += '<strong>'+getFilter.name+'</strong>';
                    sideHC += '<span>'+getFilter.address+'</span>';
                    sideHC += '<span>'+getFilter.phone+'</span>';
                    sideHC += '<span>'+getFilter.worktiming+'</span>';
                    sideHC += '<span>'+getFilter.locating + '</span></address></li>';

                    $("#locs ul.pagi").append(sideHC);
                });

            }



        }

        // City Filter - On selecting city, filter the results
        function  doDropdownFilter(){

            $("input[type=checkbox]").removeAttr("checked", "");
            $("#select_airport").prop('selectedIndex',0);
            $("#select_branch").prop('selectedIndex',0);

            var myOptions = {
                zoom: 5,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
				gestureHandling: 'greedy'
            };

            // Create a new viewpoint bound
            var bounds = new google.maps.LatLngBounds();
            map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);


            var selected_city = $("#select_city").val();


            var sideHCity = '';

            data.forEach(function(cityFilter,idx) {

                if( cityFilter.city === selected_city ) {

                    // This will get all the coordinates of different type like Airport, branches and Home office etc
                    var latlng = new google.maps.LatLng(cityFilter.lat, cityFilter.lng);
                    // Increase the bounds to take this point
                    bounds.extend(latlng);

                    var marker_new = new google.maps.Marker({
                        map: map,
                        position: new google.maps.LatLng(cityFilter.lat,cityFilter.lng),
                        title: cityFilter.city,
                        icon: getIcon(cityFilter.type)
                    });

                    // This is content for marker when we click on any of them
                    //var contentH = "<div style='width:260px;height:120px'><h4>"+cityFilter.city+"</h4>"+cityFilter.name+"<br>"+cityFilter.address +"</div>";

                    var contentH = getMarkerPopup(cityFilter);

					var infowindow = new google.maps.InfoWindow({
                        content: contentH
                    });

                    google.maps.event.addListener(marker_new, 'click', function() {
                        $(".mapPopUp").parent().parent().parent().parent().hide();
                        infowindow.open(map,marker_new);
                    });

                    marker_new.locid = idx+1;
                    marker_new.infowindow = infowindow;
                    markers[markers.length] = marker_new;

                    // Creating block to display the data into Store section under Map
					var bookBtnCFilterHtml = '';
					bookBtnCFilterHtml += '<a href="javascript:void(0);" data-lat="'+cityFilter.lat+'" data-lng="'+cityFilter.lng+'" class="printBtn get_directions"><?php echo ($lang == 'eng' ? 'Directions' : 'الاتجاهات'); ?></a>';

					if (cityFilter.active_status == '1')
					{
						bookBtnCFilterHtml += '<a href="javascript:void(0);" data-branch_id="'+cityFilter.id+'" class="btn btn-booking"><?php echo ($lang == 'eng' ? 'Book Now' : 'أحجز الآن'); ?></a>';
					}

					console.log(6);
					console.log(cityFilter);

					var branch_id = cityFilter.id;
					var city_slug = createSlug(cityFilter.city);
					var branch_slug = createSlug(cityFilter.name);
					var url_slug = branch_id + '/' + city_slug + '/' + branch_slug

                     sideHCity += '<li class="box"><div class="actionBTNs"><a href="<?php echo $lang_base_url.'/share/';?>'+url_slug+'" class="printBtn" target="_blank"><?php echo ($lang == 'eng' ? 'Share' : 'مشاركة'); ?></a>'+bookBtnCFilterHtml+'</div><address class="loc stores 932" data-locid="'+marker_new.locid+'"><h2><a href="#">'+cityFilter.city+'</a></h2>';
                    sideHCity += '<strong>'+cityFilter.name+'</strong>';
                    sideHCity += '<span>'+cityFilter.address+'</span>';
                    sideHCity += '<span>'+cityFilter.phone+'</span>';
                    sideHCity += '<span>'+cityFilter.worktiming+'</span>';
                    sideHCity += '<span>'+cityFilter.locating + '</span></address></li>';


                }

            });

            //  Fit these bounds to the map
            map.fitBounds(bounds);

            $("#locs ul.pagi").html(sideHCity);

        }


        function noCityFilter() {


            var latilng = new google.maps.LatLng(24.73435301041049,46.735990047454834);
            var myOptions = {
                zoom: 5,
                center: latilng,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
				gestureHandling: 'greedy'
            };

            map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);

            var sideHC = '';
            data.forEach(function(getFilter,idx) {

                var marker_new = new google.maps.Marker({
                    map: map,
                    position: new google.maps.LatLng(getFilter.lat,getFilter.lng),
                    title: getFilter.city,
                    icon: getIcon(getFilter.type)
                });

                // This is content for marker when we click on any of them
                //var contentH = "<div style='width:260px;height:120px'><h4>"+getFilter.city+"</h4>"+getFilter.name+"<br>"+getFilter.address +"</div>";

                var contentH = getMarkerPopup(getFilter);

				var infowindow = new google.maps.InfoWindow({
                    content: contentH
                });

                google.maps.event.addListener(marker_new, 'click', function() {
                    $(".mapPopUp").parent().parent().parent().parent().hide();
                    infowindow.open(map,marker_new);
                });

                marker_new.locid = idx+1;
                marker_new.infowindow = infowindow;
                markers[markers.length] = marker_new;

                // Creating block to display the data into Store section under Map
				var bookBtnHtmlFilter = '';
				bookBtnHtmlFilter += '<a href="javascript:void(0);" data-lat="'+getFilter.lat+'" data-lng="'+getFilter.lng+'" class="printBtn get_directions"><?php echo ($lang == 'eng' ? 'Directions' : 'الاتجاهات'); ?></a>';

				if (getFilter.active_status == '1')
				{
					bookBtnHtmlFilter += '<a href="javascript:void(0);" data-branch_id="'+getFilter.id+'" class="btn btn-booking"><?php echo ($lang == 'eng' ? 'Book Now' : 'أحجز الآن'); ?></a>';
				}

				console.log(7);
				console.log(getFilter);

				var branch_id = getFilter.id;
				var city_slug = createSlug(getFilter.city);
				var branch_slug = createSlug(getFilter.name);
				var url_slug = branch_id + '/' + city_slug + '/' + branch_slug

                sideHC += '<li class="box"><div class="actionBTNs"><a href="<?php echo $lang_base_url.'/share/';?>'+url_slug+'" class="printBtn" target="_blank"><?php echo ($lang == 'eng' ? 'Share' : 'مشاركة'); ?></a>'+bookBtnHtmlFilter+'</div><address class="loc stores 998" data-locid="'+marker_new.locid+'"><h2><a href="#">'+getFilter.city+'</a></h2>';
                sideHC += '<strong>'+getFilter.name+'</strong>';
                sideHC += '<span>'+getFilter.address+'</span>';
                sideHC += '<span>'+getFilter.phone+'</span>';
                sideHC += '<span>'+getFilter.worktiming+'</span>';
                sideHC += '<span>'+getFilter.locating + '</span></address></li>';

            });

            $("#locs ul.pagi").html(sideHC);
        }


        $("#select_city").on("change",function(){
            var if_value_is_there = $("#select_city").val();

            if(if_value_is_there == 0){
                noCityFilter();
            }
            else{
                doDropdownFilter();
            }

            quickPagination({pageSize:"8"});
        });

        $("#select_airport").on("change",function(){
            var if_airport_is_there = $("#select_airport").val();

            if(if_airport_is_there == 0 ){
                noCityFilter();
            }
            else{
                airportFilter();
            }

            quickPagination({pageSize:"8"});
        });



        // Airport Filter - On selecting airport filter the results
        function airportFilter(){

            $("input[type=checkbox]").removeAttr("checked", "");
            $("#select_city").prop('selectedIndex',0);
            $("#select_branch").prop('selectedIndex',0);

            var airport_lat = $('#select_airport option:selected').attr('data-lat');
            var airport_lng = $('#select_airport option:selected').attr('data-lng');
            var selected_airport = $("#select_airport").val();
            console.log(selected_airport);

            var latilngi = new google.maps.LatLng(airport_lat,airport_lng);
            var myOptions = {
                zoom: 13,
                center: latilngi,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
				gestureHandling: 'greedy'
            };

            map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);

            if ( selected_airport !== false ) {
                var sideHAirport = '';
                data.forEach(function(airportFilter,idx) {
                	console.log(airportFilter);

                    if( airportFilter.type === "Airport" && createSlug(airportFilter.name) === createSlug(selected_airport)) {


                        var marker_new = new google.maps.Marker({
                            map: map,
                            position: new google.maps.LatLng(airportFilter.lat,airportFilter.lng),
                            title: airportFilter.name,
                            icon: getIcon(airportFilter.type)
                        });



                        // This is content for marker when we click on any of them
                        //var contentH = "<div style='width:260px;height:120px'><h4>"+airportFilter.name+"</h4>"+airportFilter.name+"<br>"+airportFilter.address +"</div>";

                        var contentH = getMarkerPopup(airportFilter);

						var infowindow = new google.maps.InfoWindow({
                            content: contentH
                        });

                        google.maps.event.addListener(marker_new, 'click', function() {
                            $(".mapPopUp").parent().parent().parent().parent().hide();
                            infowindow.open(map,marker_new);
                        });

                        marker_new.locid = idx+1;
                        marker_new.infowindow = infowindow;
                        markers[markers.length] = marker_new;


                        // Creating block to display the data into Store section under Map
						var bookBtnHtmlAirport = '';
						bookBtnHtmlAirport += '<a href="javascript:void(0);" data-lat="'+airportFilter.lat+'" data-lng="'+airportFilter.lng+'" class="printBtn get_directions"><?php echo ($lang == 'eng' ? 'Directions' : 'الاتجاهات'); ?></a>';

						if (airportFilter.active_status == '1')
						{
							bookBtnHtmlAirport += '<a href="javascript:void(0);" data-branch_id="'+airportFilter.id+'" class="btn btn-booking"><?php echo ($lang == 'eng' ? 'Book Now' : 'أحجز الآن'); ?></a>';
						}

						console.log(8);
						console.log(airportFilter);

						var branch_id = airportFilter.id;
						var city_slug = createSlug(airportFilter.city);
						var branch_slug = createSlug(airportFilter.name);
						var url_slug = branch_id + '/' + city_slug + '/' + branch_slug

                        sideHAirport += '<li class="box"><div class="actionBTNs"><a href="<?php echo $lang_base_url.'/share/';?>'+url_slug+'" class="printBtn" target="_blank"><?php echo ($lang == 'eng' ? 'Share' : 'مشاركة'); ?></a>'+bookBtnHtmlAirport+'</div><address class="loc stores 1100" data-locid="'+marker_new.locid+'"><h2><a href="#">'+airportFilter.city+'</a></h2>';
                        sideHAirport += '<strong>'+airportFilter.name+'</strong>';
                        sideHAirport += '<span>'+airportFilter.address+'</span>';
                        sideHAirport += '<span>'+airportFilter.phone+'</span>';
                        sideHAirport += '<span>'+airportFilter.worktiming+'</span>';
                        sideHAirport += '<span>'+airportFilter.locating + '</span></address></li>';
                    }

                });

                $("#locs ul.pagi").html(sideHAirport);

            }
        }



		$("#select_branch").on("change",function(){
			var if_branch_is_there = $("#select_branch").val();

			if(if_branch_is_there == 0 ){
				noCityFilter();
			}
			else{
				branchFilter();
			}

			quickPagination({pageSize:"8"});
		});

		function branchFilter(){

			$("input[type=checkbox]").removeAttr("checked", "");
			$("#select_city").prop('selectedIndex',0);
			$("#select_airport").prop('selectedIndex',0);

			var airport_lat = $('#select_branch option:selected').attr('data-lat');
			var airport_lng = $('#select_branch option:selected').attr('data-lng');
			var selected_airport = $("#select_branch").val();
			console.log(selected_airport);

			var latilngi = new google.maps.LatLng(airport_lat,airport_lng);
			var myOptions = {
				zoom: 13,
				center: latilngi,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				gestureHandling: 'greedy'
			};

			map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);

			if ( selected_airport !== false ) {
				var sideHAirport = '';
				data.forEach(function(airportFilter,idx) {
					console.log(airportFilter);

					if(createSlug(airportFilter.name) === createSlug(selected_airport)) {


						var marker_new = new google.maps.Marker({
							map: map,
							position: new google.maps.LatLng(airportFilter.lat,airportFilter.lng),
							title: airportFilter.name,
							icon: getIcon(airportFilter.type)
						});



						// This is content for marker when we click on any of them
						//var contentH = "<div style='width:260px;height:120px'><h4>"+airportFilter.name+"</h4>"+airportFilter.name+"<br>"+airportFilter.address +"</div>";

						var contentH = getMarkerPopup(airportFilter);

						var infowindow = new google.maps.InfoWindow({
							content: contentH
						});

						google.maps.event.addListener(marker_new, 'click', function() {
							$(".mapPopUp").parent().parent().parent().parent().hide();
							infowindow.open(map,marker_new);
						});

						marker_new.locid = idx+1;
						marker_new.infowindow = infowindow;
						markers[markers.length] = marker_new;


						// Creating block to display the data into Store section under Map
						var bookBtnHtmlAirport = '';
						bookBtnHtmlAirport += '<a href="javascript:void(0);" data-lat="'+airportFilter.lat+'" data-lng="'+airportFilter.lng+'" class="printBtn get_directions"><?php echo ($lang == 'eng' ? 'Directions' : 'الاتجاهات'); ?></a>';

						if (airportFilter.active_status == '1')
						{
							bookBtnHtmlAirport += '<a href="javascript:void(0);" data-branch_id="'+airportFilter.id+'" class="btn btn-booking"><?php echo ($lang == 'eng' ? 'Book Now' : 'أحجز الآن'); ?></a>';
						}

						console.log(8);
						console.log(airportFilter);

						var branch_id = airportFilter.id;
						var city_slug = createSlug(airportFilter.city);
						var branch_slug = createSlug(airportFilter.name);
						var url_slug = branch_id + '/' + city_slug + '/' + branch_slug

						sideHAirport += '<li class="box"><div class="actionBTNs"><a href="<?php echo $lang_base_url.'/share/';?>'+url_slug+'" class="printBtn" target="_blank"><?php echo ($lang == 'eng' ? 'Share' : 'مشاركة'); ?></a>'+bookBtnHtmlAirport+'</div><address class="loc stores 1100" data-locid="'+marker_new.locid+'"><h2><a href="#">'+airportFilter.city+'</a></h2>';
						sideHAirport += '<strong>'+airportFilter.name+'</strong>';
						sideHAirport += '<span>'+airportFilter.address+'</span>';
						sideHAirport += '<span>'+airportFilter.phone+'</span>';
						sideHAirport += '<span>'+airportFilter.worktiming+'</span>';
						sideHAirport += '<span>'+airportFilter.locating + '</span></address></li>';
					}

				});

				$("#locs ul.pagi").html(sideHAirport);

			}
		}


        //jQuery.noConflict();
        (function($){
            $(document).ready(function(){

                initialize();
                quickPagination({pageSize:"8"});

            });

        })(jQuery);

		function getMarkerPopup(mapData) {

                <?php if($lang == "eng"){ ?>
            		var opningLable = "OPENING HOURS:";
                <?php } else { ?>
            		var opningLable = "ساعات الدوام:";
                <?php } ?>

            var contentHtml = '<div class="mapPopUp" id="iw-container">' +
                '<div class="leftPU"> ' +
                '<h2>'+mapData.city+'</h2>' +
                '<p>'+mapData.address+'</p>' +
                '<p>&nbsp;</p>';

            if(typeof  mapData.phone != 'undefined' && mapData.phone != "") {
                var phoneText = $(mapData.phone).text();
                var phone = phoneText.split(":");
                contentHtml += '<p><strong>T:</strong> ' +phone[1] + ' </p>';
            }
            if( typeof  mapData.mobile != 'undefined' && mapData.mobile != "") {
                var mobileText = $(mapData.mobile).text();
                var mobile = mobileText.split(":");
                contentHtml += '<p><strong>M:</strong> ' +mobile[1] + ' </p>';
            }
            if(typeof  mapData.email != 'undefined' && mapData.email != '<span style="display:none;">Email:</span>') {
                var emailText = $(mapData.email).text();
                var email = emailText.split(":");
                contentHtml += '<p><strong>E:</strong> ' + email[1] + '</p>';
            }
            contentHtml += '</div>' +
                '<div class="rightPU"> ' +
                '<h3>'+opningLable+'</h3>';
            if( typeof  mapData.worktiming != 'undefined' && mapData.worktiming != "") {
                contentHtml += '<p>' + mapData.worktiming + '</p>';
            }

			var bookBtnHtmlForMap = '';
			bookBtnHtmlForMap += '<a href="javascript:void(0);" data-lat="'+mapData.lat+'" data-lng="'+mapData.lng+'" class="printBtn get_directions"><?php echo ($lang == 'eng' ? 'Directions' : 'الاتجاهات'); ?></a>';

			if (mapData.active_status == '1')
			{
				bookBtnHtmlForMap += '<a href="javascript:void(0);" data-branch_id="'+mapData.id+'" class="btn btn-booking"><?php echo ($lang == 'eng' ? 'Book Now' : 'أحجز الآن'); ?></a>';
			}

			console.log(9);
			console.log(mapData);

			var branch_id = mapData.id;
			var city_slug = createSlug(mapData.city);
			var branch_slug = createSlug(mapData.name);
			var url_slug = branch_id + '/' + city_slug + '/' + branch_slug

            contentHtml += '<a href="<?php echo $lang_base_url.'/share/';?>'+url_slug+'" class="printBtn" target="_blank"><?php echo ($lang == 'eng' ? 'Share' : 'مشاركة'); ?></a>'+bookBtnHtmlForMap+
                '</div>' +
                '<div class="clearfix"></div> ' +
                '</div>';

			return contentHtml;


        }
	</script>

<script>
	$(document).on('click', '.get_directions', function() {
		var d_lat = $(this).data('lat');
		var d_lng = $(this).data('lng');

		if (navigator.geolocation) {

			var optionsPosition = {
				enableHighAccuracy: true,
				timeout: 10000,
				maximumAge: 0
			};

			navigator.geolocation.getCurrentPosition(function (position) {
				updatePosition(position, d_lat, d_lng);
			}, errorPosition, optionsPosition);

			function updatePosition(position, dLat, dLng) {
				var s_lat = position.coords.latitude;
				var s_lng = position.coords.longitude;
				window.open('https://maps.google.com/?saddr='+s_lat+','+s_lng+'&daddr='+dLat+','+dLng, '_blank');
			}

			function errorPosition(error) {
				if (err.PERMISSION_DENIED === error.code) {
					alert("Something went wrong with fetching your current location!");
				} else {
					alert("Something went wrong with fetching your current location!");
				}
			}

		} else {
			alert("Something went wrong with fetching your current location!");
		}
	});

	function createSlug(input) {
		// Function to remove HTML tags
		function removeHtmlTags(text) {
			var div = document.createElement('div');
			div.innerHTML = text;
			return div.textContent || div.innerText || "";
		}

		// Function to convert text to slug
		function convertToSlug(text) {
			return text
					.toLowerCase()          // Convert to lowercase
					.trim()                 // Trim whitespace from both ends
					.replace(/\s+/g, '-')   // Replace spaces with hyphens
					.replace(/[^\w\u0600-\u06FF\-]+/g, '') // Remove all non-word characters except hyphens and Arabic characters
					.replace(/\-\-+/g, '-');  // Replace multiple hyphens with a single hyphen
		}

		// Remove HTML tags from the input
		var cleanText = removeHtmlTags(input);

		// Convert cleaned text to slug
		var slug = convertToSlug(cleanText);

		return slug;
	}
</script>

@endsection

