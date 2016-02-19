<?php
if(!defined('sntmedia_TSUNG_UI')){die();}

//temporary to be rolled into the "tests" page later

?>
<main> 
<div class="flash-messages-placeholder container page ">
	<div class="clearfix">
		<h2 class="pull-left">New test</h2>
	</div>
	<form accept-charset="UTF-8" action="?" method="post">
	<input name="action" type="hidden" value="new_test" /><input name="_method" type="hidden" value="patch" />
	<div class="row">
		<label class="col-md-1">Test Settings</label> 
		<div class="col-md-7">
			<div class="form-panel">
				<div class="form-group string template-name">
					<label class="string control-label" for="template-name">Name</label>
					<div>
						<input class="string form-control" id="template-name" name="template-name" placeholder="Test Name" type="text" value="" />
					</div>
				</div>
				<div class="form-group string template-profile">
					<label class="string control-label" for="template-profile">Profile</label>
					<div>
						<input class="string form-control" id="template-profile" name="template-profile" placeholder="Short description of the test" type="text" value="" />
					</div>
				</div>

	<!-- end of the endpoint type -->
				<div class="advanced-settings">
					<a class="add-config-button">Advanced settings</a> 
					<div class="inner-panel featured hidden">
						<h4>Advanced settings</h4>
						<div class="row mbm">
							<div class="form-group string optional error_threshold col-xs-3">
								<label class="string optional control-label" for="error_threshold">Error (%)</label>
								<div>
									<input class="string optional xsmall form-control" id="error_threshold" name="error_threshold" type="text" value="50" />
								</div>
							</div>
							<div class="form-group string optional timeout col-xs-3">
								<label class="string optional control-label" for="timeout">Timeout</label>
								<div>
									<input class="string optional form-control" id="timeout" name="timeout" type="text" value="10" />
								</div>
							</div>
							<div class="form-group select optional timeout_uom pull-left">
								<div>
									<select class="select optional small form-control" id="timeout_uom" name="timeout_uom" value="ms">
										<option selected="selected" value="Sec">Sec</option>
										<option value="ms">ms</option>
									</select>
								</div>
							</div>
							<div class="panel-controls">
								<a href="#" class="action-icon resize" data-toggle="tooltip" data-container="body" title="Resize"></a> 
							</div>
						</div>
						<h4>
							Basic authentication <a data-toggle="popover" role="button" class="hint-badge" data-placement="right" data-trigger="hover" data-title="Please Note" data-content="We don't encrypt these fields, so please don't use sensitive credentials here!">?</a> 
						</h4>
						<div class="row">
							<div class="form-group string optional user_login col-xs-4">
								<div>
									<input autocomplete="off" class="string optional form-control" id="user_login" name="user_login" placeholder="Username" type="text" />
								</div>
							</div>
							<div class="form-group string optional user_password col-xs-4">
								<div>
									<input autocomplete="off" class="string optional form-control" id="user_password" name="user_password" placeholder="Password" type="text" />
								</div>
							</div>
						</div>
					</div>
				</div>

	<!-- end of the advanced options -->
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-panel featured">
				<div class="notes form-group">
					<div class="form-group text optional notes">
						<label class="text optional control-label" for="notes" ">Notes</label>
						<div><textarea class="text optional form-control" id="notes" name="notes" placeholder="Notes are stored with each instance of a test, not with the test template."  rows="6"></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<!-- end of the test settings form section -->









		<div class="row">
			<label class="col-md-1 mtm">Load</label> 
			<div class="url-fields col-md-10">
				<div class="url-field form-panel" data-index="0">
					<div class="panel-controls">
						<a href="#" class="action-icon move-up disabled" title="Move this request up" data-toggle="tooltip" data-container="body"></a> <a href="#" class="action-icon move-down disabled" title="Move this request down" data-toggle="tooltip" data-container="body"></a> <a href="#" class="action-icon copy" title="Copy target" data-toggle="tooltip" data-container="body"></a> <a href="#" class="remove-url action-icon delete disabled" title="Delete" data-toggle="tooltip" data-container="body"></a> 
					</div>


	<!-- Connection options-->


				<div class="connections connection-fields row">
					<div class="form-group string optional disabled min_processes_count col-xs-3 hidden min-clients">
						<label class="string optional control-label" for="min_processes_count">Clients from</label>
						<div>
							<input class="string optional disabled form-control" disabled="disabled" id="min_processes_count" name="min_processes_count" type="text" value="0" />
						</div>
					</div>
					<div class="form-group string optional max_processes_count col-xs-3 max-clients">
						<label class="string optional control-label" for="max_processes_count">Clients</label>
						<div>
							<input class="string optional form-control" id="max_processes_count" name="max_processes_count" type="text" value="10" />
						</div>
					</div>
					<div class="form-group string optional duration col-xs-3">
						<label class="string optional control-label" for="duration">Duration</label>
						<div>
							<input class="string optional form-control" id="duration" name="duration" type="text" value="1" />
						</div>
					</div>
					<div class="form-group select optional duration_uom col-xs-3">
						<label class="select optional control-label" for="duration_uom">&nbsp;</label>
						<div>
							<select class="select optional form-control" id="duration_uom" name="duration_uom" value="Sec">
								<option value="Sec">Sec</option>
								<option selected="selected" value="Min">Min</option>
							</select>
						</div>
					</div>


				</div>

				<div class="session-setup">
					<a class="add-config-button"> Session Setup </a> 
					<div class="inner-panel hidden">
						<h4>
							Session Setup
						</h4>
						<div class="session-setup-list">
						</div>
						<a href="#" class="btn-add">Session Setup</a> 
					</div>
				</div>

<!-- end of session setup -->

				<div class="user">
					<a class="add-config-button"> User </a> 
					<div class="inner-panel hidden">
						<h4>
							User
						</h4>
						<div class="user-list">
						</div>
						<a href="#" class="btn-add">Session Setup</a> 
					</div>
				</div>

<!-- end of session setup -->

	<!-- end connection options-->



					<div class="trash">
<!-- Trashed url's attributes end up here -->
					</div>
					<input class="numeric integer optional id" id="urls_attributes_0_id" name="urls_attributes[0][id]" step="1" type="hidden" value="1347767" />
			<a href="#" class="pull-right btn btn-default add-new-url" data-url-limit="10"><i class="glyphicon glyphicon-plus"></i> Add Phase</a> <a href="/billing_addons" target="_blank" class="pull-right btn btn-primary increase-max-urls-limit mrm hidden">Use add-ons to add more URLs</a> 
				</div>
			</div>
		</div>
<!-- end of the endpoint urls form section -->


		<div class="row">
			<label class="col-md-1 mtm">Sessions</label> 
			<div class="url-fields col-md-10">
				<div class="url-field form-panel" data-index="0">
					<div class="panel-controls">
						<a href="#" class="action-icon move-up disabled" title="Move this request up" data-toggle="tooltip" data-container="body"></a> <a href="#" class="action-icon move-down disabled" title="Move this request down" data-toggle="tooltip" data-container="body"></a> <a href="#" class="action-icon copy" title="Copy target" data-toggle="tooltip" data-container="body"></a> <a href="#" class="remove-url action-icon delete disabled" title="Delete" data-toggle="tooltip" data-container="body"></a> 
					</div>
					<div class="url row" id="url_0">
						<div class="col-sm-10">
							<div class="row">
								<div class="form-group select optional col-xs-8 ">
									<div class="form-group string session-name">
										<label class="string control-label" for="session-name">Name</label>
										<div>
											<input class="string form-control" id="session-name" name="session-name" placeholder="Session Name" type="text" value="" />
										</div>
									</div>
								</div>
								<div class="form-group select optional col-xs-2 ">
									<div class="form-group string control-label">
										<label class="select optional control-label" for="urls_attributes_0_protocol">Protocol</label>
										<div>
											<select class="select optional form-control" id="urls_attributes_0_protocol" name="urls_attributes[0][protocol]" onchange="setSessionType(this.value)">
												<option selected="selected" value="ts_http">HTTP(S)</option>
												<option value="ts_mysql">MySQL</option>
												<option value="ts_websocket">Websocket</option>
												<option value="ts_pgsql">PostgresSQL</option>
												<option value="ts_mqtt">MQTT</option>
												<option value="ts_amqp">AMQP</option>
												<option value="ts_jabber">Jabber</option>
												<option value="ts_ldap">LDAP</option>
											</select>
										</div>
									</div>
								</div>
								<div class="form-group select optional col-xs-2 ">
									<div class="form-group string control-label">
										<label class="select optional control-label" for="probability">Probability</label>
										<div>
											<select class="select optional form-control" id="probability" name="probability">
												<option selected="selected" value="100">100%</option>
												<option value="90">90%</option>
												<option value="80">80%</option>
											</select>
										</div>
									</div>
								
								</div>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="row">
								<div class="form-group select optional urls_request_type col-xs-6 request_method">
								<label class="select optional control-label" for="urls_attributes_0_request_type">Method</label>
									<div>
										<select class="select optional form-control" id="urls_attributes_0_request_type" name="urls_attributes[0][request_type]" value="GET">
											<option selected="selected" value="GET">GET</option>
											<option value="POST">POST</option>
											<option value="PUT">PUT</option>
											<option value="PATCH">PATCH</option>
											<option value="DELETE">DELETE</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group select optional urls_host host col-sm-3">
							<label for="urls_attributes_0_host" class="select optional control-label">Host</label> <a class="hint-badge" data-toggle="popover" title="Add a New Target Host" data-content="If you want to add a new host, click the <strong>Target Hosts</strong> link at the top of the page" data-html="true" data-placement="bottom" data-trigger="click">?</a> 
							<div>
								<div class="form-group select optional urls_host">
									<div>
										<select class="select optional form-control" id="urls_attributes_0_host" name="urls_attributes[0][host]">
											<option value="198.246.190.107">198.246.190.107</option>
											<option value="209.188.25.147">209.188.25.147</option>
											<option value="209.188.25.155:8181">209.188.25.155:8181</option>
											<option value="209.188.25.155:8282">209.188.25.155:8282</option>
											<option value="apifin.investkit.com">apifin.investkit.com</option>
											<option value="apireal.synapsys.us">apireal.synapsys.us</option>
											<option selected="selected" value="apirt.synapsys.us">apirt.synapsys.us</option>
											<option value="apisports.synapsys.us:91">apisports.synapsys.us:91</option>
											<option value="api.synapsys.us">api.synapsys.us</option>
											<option value="content.synapsys.us">content.synapsys.us</option>
											<option value="devapirt.synapsys.us">devapirt.synapsys.us</option>
											<option value="devops-test.synapsys.us:8282">devops-test.synapsys.us:8282</option>
											<option value="dev-sports-ai.synapsys.us:280">dev-sports-ai.synapsys.us:280</option>
											<option value="newsapi.synapsys.us">newsapi.synapsys.us</option>
											<option value="powa.tdw.io">powa.tdw.io</option>
											<option value="prod-sports-ai.synapsys.us">prod-sports-ai.synapsys.us</option>
											<option value="prod-sports-api.synapsys.us">prod-sports-api.synapsys.us</option>
											<option value="sports-ai.synapsys.us:91">sports-ai.synapsys.us:91</option>
											<option value="testapi.investkit.com:90">testapi.investkit.com:90</option>
											<option value="test.content.synapsys.us">test.content.synapsys.us</option>
											<option value="w1.synapsys.us">w1.synapsys.us</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group string optional urls_path_without_leading_slash col-sm-6">
							<label class="string optional control-label" for="urls_attributes_0_path_without_leading_slash">Path</label>
							<div>
								<input class="string optional form-control" id="urls_attributes_0_path_without_leading_slash" name="urls_attributes[0][path_without_leading_slash]" type="text" value="index.html" />
							</div>
						</div>
					</div>

<!-- url -->
					<div class="headers">
						<a class="add-config-button"> Headers </a> 
						<div class="inner-panel hidden">
							<h4>
								Headers
							</h4>
							<div class="headers-list">
							</div>
							<a href="#" class="btn-add">Header</a> 
						</div>
					</div>

<!-- end of headers -->
					<div class="params">
						<a href="#" class="add-config-button"> Parameters &amp; Body </a> 
						<div class="inner-panel hidden">
							<div class="pull-right mrs param-type-options" title="Raw body cannot be used with GET or DELETE">
								<label class="radio-inline"> 
								<input type="radio" name="urls_attributes[0][paramType]" value="keyval" class="keyval" checked="checked"> Key/ Value </label> <label class="radio-inline"> 
								<input type="radio" name="urls_attributes[0][paramType]" value="rawbody" class="rawbody" disabled> Raw Body </label> 
							</div>
							<h4>
								Parameters &amp; Request Body <a class="hint-badge" data-toggle="popover" title="Parameters by request method:" data-content='<p>For <code>GET</code> and <code>DELETE</code> methods, keys and values are converted to <strong>URL query</strong> parameters.</p>
         <p>For <code>POST/ PUT/ PATCH</code>, parameters are converted into a <code>form-urlencoded</code> request body.</p>
         To use the "Raw Body," select the <code>PUT</code> <code>POST</code> or <code>PATCH</code> request type and add a <code>Content-Type</code> header.' data-html="true" data-placement="top" data-trigger="click">?</a> 
							</h4>
							<div class="row raw_post_body hidden">
								<div class="col-xs-12">
									<div class="config-field">
										<div class="form-group text optional disabled urls_raw_post_body col-xs-12">
											<div>
												<textarea class="text optional disabled form-control" disabled="disabled" id="urls_attributes_0_raw_post_body" name="urls_attributes[0][raw_post_body]" placeholder="Enter raw request body">
												</textarea>
											</div>
										</div>
										<div class="field-control">
											<a href="#delete" class="action-icon delete" title="Delete" data-toggle="tooltip"></a> 
										</div>
									</div>
								</div>
							</div>
							<div class="params-list">
							</div>
							<div class="row">
								<div class="form-group">
									<div class="col-xs-2 btn-add-wrapper">
										<a href="#" class="btn-add">Parameter</a> 
									</div>
									<div class="m-params-form payload-options">
										<div>
											<div class="form-group url optional urls_params_file_url col-xs-4 ">
												<input placeholder="Payload file URL" class="string url optional form-control params-file-url" name="urls_attributes[0][params_file_attributes][url]" size="50" type="text" value=""> 
											</div>
											<a href="http://support.loader.io/article/17-payload-files" target="_blank" class="hint-badge mts" title="Click for more info" data-toggle="tooltip">?</a> 
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

<!-- end of params -->
					<div class="response">
						<a class="add-config-button">Response variables </a> 
						<div class="inner-panel hidden">
							<h4>
								Response variables
							</h4>
							<div class="vars-list">
							</div>
							<a href="#" class="btn-add">Response variable</a> 
						</div>
					</div>

<!-- end of response -->
					<div class="trash">
<!-- Trashed url's attributes end up here -->
					</div>
					<input class="numeric integer optional id" id="urls_attributes_0_id" name="urls_attributes[0][id]" step="1" type="hidden" value="1347767" />
		<div class="add-target clearfix mbm">
			<a href="#" class="pull-right btn btn-default add-new-url" data-url-limit="10"><i class="glyphicon glyphicon-plus"></i> Add session</a> <a href="/billing_addons" target="_blank" class="pull-right btn btn-primary increase-max-urls-limit mrm hidden">Use add-ons to add more URLs</a> 
		</div>
				</div>
			</div>
		</div>
<!-- end of the endpoint urls form section -->




<!-- end of the add target block -->
		<div class="trash">
<!-- Trashed urls end up here -->
		</div>
		<div class="form-group hidden copied">
			<div>
				<input class="hidden form-control" id="copied" name="copied" type="hidden" />
			</div>
		</div>
		<div class="form-submit row">
			<div class="col-sm-2">
				<a class="btn btn-default btn-outsize" href="/?page=tests">Back</a> 
			</div>
			<div class="col-sm-10">
				<div class="start-test-block row ">
					<div class="col-md-2">
<input class="btn" name="commit" type="submit" value="Save Draft" />
					</div>
					<div class="col-md-2">
					<input class="btn" name="commit" type="submit" value="Schedule" /> 
					</div>
					<div class="col-md-4">
					</div>
					<div class="col-md-2">
					<input class="btn-main" name="commit" type="submit" value="Run Test" />
					</div>
				</div>
				<div id="schedule" class="panel-block hidden schedule-test-block">
					<div class="row">
						<div class="col-sm-8 form-horizontal scheduler">
							<div class="">
								<div class="form-group hidden next_run_at">
									<div>
										<input class="hidden form-control" id="next_run_at" name="next_run_at" type="hidden" />
									</div>
								</div>
								<div class="form-group hidden stop_schedule_at">
									<div>
										<input class="hidden form-control" id="stop_schedule_at" name="stop_schedule_at" type="hidden" />
									</div>
								</div>
								<div class="control-group string optional">

<!---label class="string optional control-label"> Time</label-->
									<div class="controls j-ui">
										<div class="fieldset">
											<div class="form-group">
												<label class="col-sm-2 control-label">Start</label> 
												<div class="controls col-sm-10">
													<div class="date-control">
														<input type="text" name="schedule_test_date" class="form-control schedule_test_date" />
														<span class="help-block"></span> 
													</div>
													<div class="time-control">
														<select class="form-control schedule_test_time schedule_test_hours">
															<option value="0">00</option>
															<option value="1">01</option>
															<option value="2">02</option>
															<option value="3">03</option>
															<option value="4">04</option>
															<option value="5">05</option>
															<option value="6">06</option>
															<option value="7">07</option>
															<option value="8">08</option>
															<option value="9">09</option>
															<option value="10">10</option>
															<option value="11">11</option>
															<option value="12">12</option>
															<option value="13">13</option>
															<option value="14">14</option>
															<option value="15">15</option>
															<option value="16">16</option>
															<option value="17">17</option>
															<option value="18">18</option>
															<option value="19">19</option>
															<option value="20">20</option>
															<option value="21">21</option>
															<option value="22">22</option>
															<option value="23">23</option>
														</select>
													</div>
													<div class="time-control">
														<select class="form-control schedule_test_time schedule_test_minutes">
															<option value="0">00</option>
															<option value="15">15</option>
															<option value="30">30</option>
															<option value="45">45</option>
														</select>
													</div>
												</div>
											</div>
										</div>
										<div class="fieldset">
											<div class="form-group">
												<label class="col-sm-2 control-label">Repeat</label> 
												<div class="col-sm-10 mbs">
													<label class="checkbox-inline"> 
													<input type="checkbox" id="inlineCheckbox1" name="schedule_settings[sunday]" value="1">Sun </label> <label class="checkbox-inline"> 
													<input type="checkbox" id="inlineCheckbox2" name="schedule_settings[monday]" value="1">Mon </label> <label class="checkbox-inline"> 
													<input type="checkbox" id="inlineCheckbox3" name="schedule_settings[tuesday]" value="1">Tue </label> <label class="checkbox-inline"> 
													<input type="checkbox" id="inlineCheckbox4" name="schedule_settings[wednesday]" value="1">Wed </label> <label class="checkbox-inline"> 
													<input type="checkbox" id="inlineCheckbox5" name="schedule_settings[thursday]" value="1">Thu </label> <label class="checkbox-inline"> 
													<input type="checkbox" id="inlineCheckbox6" name="schedule_settings[friday]" value="1">Fri </label> <label class="checkbox-inline"> 
													<input type="checkbox" id="inlineCheckbox7" name="schedule_settings[saturday]" value="1">Sat </label> 
												</div>
											</div>
										</div>
										<div class="fieldset">
											<div class="form-group">
												<label class="col-sm-2 control-label">End</label> 
												<div class="controls col-sm-10">
													<div class="date-control">
														<input type="text" class="form-control end_schedule_test_date"> <span class="help-block"></span> 
													</div>
													<span class="text-tip">Leave blank to repeat indefinitely</span> 
												</div>
											</div>
										</div>
									</div>

<!--
            <div class="inline mlm valign">
              <span class="inline"></span>
              <span class="inline"></span>
            </div>
            -->
								</div>
							</div>
							<div class="mtm">
								<input class="btn-main btn-outsize" name="commit" type="submit" value="Schedule this test" />
								<span class="mhs">or</span><a class="switch-schedule-option btn btn-default btn-outsize" href="#">Let me run it now</a> 
							</div>
						</div>
						<div class="col-sm-4 mtm">
						</div>
					</div>
				</div>
			</div>
		</div>

<!-- end of the form submit -->
	</form>
</div>
</main>
<?php
/*
$tsung_config = array();
$tsung_config['tsung']['servers']['server']['host']="www.synapsys.us";
$tsung_config['tsung']['servers']['server']['port']="90";
echo "<pre>";
echo htmlentities(($tsungUI->create_test($tsung_config)));
echo "</pre>";
*/

?>
<script>
function setSessionType(str) {
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET","getuser.php?q="+str,true);
        xmlhttp.send();
    }
}
</script>

</body>
</html>

