<div style="background: transparent url('./i/header/112.jpg') center fixed;
	background-size: cover;
	margin-top:-20px !important">
    <br>
    <div class="container pad1em"
         style="background-color: rgba(70,79,96,0.75); border:1px solid
		#000;border-radius:6px;">
        <div class="row">

            <div class="col s12 xucase center white-text">


                <h3>ADMINISTRATION {$BOOKING}</h3>
                <p class="divider clearfix"></p>

            </div>
            <!-- title column -->

            <div class="col s12 xgrey xlighten-3">
                <br>
                <form id="bookingForm" name="bookingForm" action=""
                      method="POST"
                      enctype="multipart/form-data" onsubmit="return validateBookingForm();">
                    <input type="hidden" id="pleaseSelect" value="{$PLEASE_SELECT}"/>
                    <input type="hidden" id="loading" value="{$LOADING}"/>

                    <div class="col l6 s12">
                        <label for="AuthUserIDe"><i class="fa fa-globe"></i> Book as <strong>Agent</strong></label><br>
                        <div>
                            <select name="AgentID" id="AgentID" class="xchosen-select
									browser-default" value='{$AgentID}>
                            <option value="0"> ---</option>
  {section name=index loop=$agents}
                                    <option value="{$agents[index].AuthUserID}">{$agents[index].AuthUserCompany}</option>
                                {/section}
                            </select>
                        </div>
                    </div>
                    <div class="col s12 l2">
                        <label for="ReferenceNo"><i class="fa fa-book"></i> Agent Reference
                            Number</label><br>
                        <input type="text" id="ReferenceNo" class="browser-default"
                               name="ReferenceNo" value="">
                    </div>
                    <div class="col s12 l2" id=' webyblock' style="display:none">
                            <label for="wrn"><i class="fa fa-book"></i> Weby Reference Number</label>
                            <input type="text" id="weby_key" name="weby_key" value="{$weby_key}"
                                   disabled><br>
                            <select id="wref" class="browser-default" name="wref" value=''>
                            </select>
                        </div>

                        <div class="col s12 l2" id='sunblock' style="display:none">
                            <label for="srn"><i class="fa fa-book"></i> Sun Reference Number</label><br>
                            <input type="file" id="srn" class="browser-default"
                                   name="SunReferenceNo" value="">
                        </div>

                        <div class="col s12 l6">
                            <br>
                            <label for="fromSelectorValue"><i class="fa fa-map-marker"></i>
                                {$FROM}
                            </label><br>
                            <input type="hidden" id="FromID" name="FromID" value="{$fromID}"><i
                                    class="pe-7s-car pe-lg pe-va white-text"></i>
                            {$STARTING_FROM}
                            <input type="text" id="FromName" name="FromName" value="{$fromName}" class="input-lg"
                                   style="width:100%" placeholder="{$SEARCH_PLACEHOLDER}"
                                   autocomplete="off">
                            <span id="fromLoading" class="small">
												{$TYPE_SEARCH}
												</span>
                            <div id="selectFrom_options" class="list-group white"
                                 style="max-height:15em;overflow:auto">
                            </div>
                        </div>
                        <!-- from -->

                        <div class="col l6 s12">
                            <br>
                            <label for="toSelectorValue"><i class="fa fa-map-marker"></i>
                                {$TO}
                            </label> <span style='color:white' id='toname2'></span><br>
                            <input type="hidden" id="ToID" name="ToID" value="{$toID}"><i
                                    class="pe-7s-map-marker pe-lg pe-va white-text"></i>
                            {$GOING_TO}
                            <input type="text" id="ToName" name="ToName" value="{$toName}"
                                   class="input-lg"
                                   style="width:100%" placeholder="{$SEARCH_PLACEHOLDER}"
                                   autocomplete="off">
                            <span id="toLoading" class="small">
																{$TYPE_SEARCH}
																</span>
                            <div id="selectTo_options" class="list-group white"
                                 style="max-height:15em;overflow:auto"></div>
                        </div>
                        <!-- to -->
                        <div class="col l6 s12">
                            <br>
                            <label for="paxSelector">
                                <i class="fa fa-user"></i>
                                {$PASSENGERS_NO}
                            </label>
                            <select id="paxSelector" class="browser-default" name="PaxNo"
                                    value='{$PaxNo}'>
                                <option value="0"> ---</option>
                                {for $pax=1 to 54}
                                    <option value="{$pax}">{$pax}</option>
                                {/for}
                            </select>
                        </div>
                        <!-- passengers no. -->

                        <div class="col s12 l3">
                            <br>
                            <label for="transferDate"><i class="fa fa-calendar-o"></i>
                                {$PICKUP_DATE}
                            </label><br>
                            <input type="text" id="transferDate" class="browser-default"
                                   name="transferDate" readonly
                                   value="{$transferDate}" data-field="date">
                        </div>
                        <!-- pickup date -->

                        <div class="col s12 l3">
                            <br>
                            <label for="transferTime"><i class="fa fa-clock-o"></i>
                                {$PICKUP_TIME}
                            </label><br>
                            <input type="text" id="transferTime" class="browser-default
																				timepick" name="transferTime"
                                   value="{$transferTime}" data-field="time">
                        </div>
                        <!-- pickup time -->

                        <div class="col l6 s12">
                            <br>
                            <div class="switch">
                                <label for="returnTransferCheck">
                                    <i class="fa fa-undo"></i>
                                    {$RETURN_TRANSFER}
                                </label>
                                <br><br>
                                <label class="center">
                                    {$NO}
                                    <input type="checkbox" name="returnTransferCheck"
                                           id="returnTransferCheck"
                                    >
                                    <span class="lever"></span>
                                    {$YES}
                                </label>
                                <br><br>
                            </div>
                        </div>
                        <!-- return transfer switch -->

                        <div id="showReturn" style="display:none;margin:-0.75rem
																						!important" class="col s12">
                            <div class="col s12 l3">
                                <br>
                                <label for="returnDate"><i class="fa fa-calendar-o"></i>
                                    {$RETURN_DATE}
                                </label><br>
                                <input type="text" id="returnDate"
                                       class="browser-default" name="returnDate" readonly
                                       value="{$returnDate}" data-field="date">
                            </div>
                            <div class="col s12 l3">
                                <br>
                                <label for="returnTime"><i class="fa fa-clock-o"></i>
                                    {$PICKUP_TIME}
                                </label><br>
                                <input type="text" id="returnTime" name="returnTime"
                                       class="browser-default timepick"
                                       data-field="time" value="{$returnTime}">
                                <br><br>
                            </div>
                        </div>
                        <!-- show return date/time -->
                        <br>
                        <div class="col s12 pad1em white-text" style="padding:
																								1rem !important; background: rgba(0,0,0,.5)">
                            <div class="col s12 l9">
                                <p><i class="fa fa-info-circle fa-2x red-text"></i>
                                    {$AVAILABILITY_DEPENDS}
                                </p>
                            </div>
                            <div class="col s6 l3 pull">
                                <button id="selectCarAdminBtn" type="submit"
                                        class="btn blue btn-large"
                                        onclick="return false;">
                                    <i class="fa fa-chevron-down"></i>
                                    {$SELECT_CAR}
                                </button>
                                <button id='empty' type="button" class="btn
																												btn-large">
                                    Empty fields
                                </button>
                            </div>
                        </div>
                        <!-- select car button -->


                        <div class="col s12">
                            <div class="tab" id="tab_1">
                                <div id="selectCar">
                                    <div class="col s12 center-align xwhite-text">
                                        <br>
                                        <h4>
                                            {$PRICES_STARTING_FROM?}
                                        </h4>
                                    </div>


                                    <div class="col s12 ucase s center
																																					xwhite-text">
                                        {$SERVICES_DESC1}
                                        {$SERVICES_DESC2}
                                        {$SERVICES_DESC3}
                                        {$SERVICES_DESC4}
                                        {$SERVICES_DES}
                                    </div>
                                </div>
                                <div id="final" style='display: none;'>Proba</div>
                            </div>
                            <!-- tab_1 -->
                        </div>


                </form>
                <!-- booking form -->
                <br>&nbsp;

            </div>
            <!-- booking form col grey -->
            <br><br>
        </div>
        <!-- main row -->
    </div>
    <!-- main container -->
    <br>&nbsp;
</div>
<!-- background div -->