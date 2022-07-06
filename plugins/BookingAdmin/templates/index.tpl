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
