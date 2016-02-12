<?php
if(!defined('sntmedia_TSUNG_UI')){die();}

$testplan_list = $tsungUI->getTestplanList();
$testplan_count = count($testplan_list);

switch($action) {
	case 'archive':

	break;
	case 'trash':

	break;
	case 're_run':
	break;
	case 'edit':
	case 'copy':
	case 'new':

	$tables = $tsungUI->getTableNames();
	//print_r($tables);

	foreach ($tables as $table)
	{
		$vars = $tsungUI->getColumnNames($table);
		if (! isset($$table))
		{
			$$table = array();
		}
		foreach ($vars as $var)
		{
			if (! isset($$table[$var]))
			{
				$$table[$var] = '';
			}
		}
	}
	

?>
<main class="main"> 
<div class="flash-messages-placeholder container page ">
	<h2 class="mbm">
		New test
	</h2>
	<form accept-charset="UTF-8" action="?page=tests" class="simple_form edit_endpoint" id="endpointForm" method="post">
		<div style="margin:0;padding:0;display:inline">
			<input name="utf8" type="hidden" value="&#x2713;" />
			<input name="_method" type="hidden" value="patch" />
		</div>
		<div class="row">
			<label class="col-md-2">Test Settings</label> 
			<div class="endpoint-settings col-md-6">
				<div class="form-panel">
					<div class="form-group string optional tsung_config_templates-name">
						<label class="string optional control-label" for="tsung_config_templates-name">Name</label>
						<div>
							<input class="string optional form-control" id="tsung_config_templates-name" name="tsung_config_templates[name]" placeholder="Test Name" type="text" value="<?php $tsung_config_templates['name'] ?>" />
						</div>
					</div>
					<div class="endpoint-type row">
						<div class="form-group select optional endpoint_test_type col-xs-6">
							<label for="endpoint_test_type" class="select optional control-label">Test type</label> <a class="hint-badge" data-placement="top" data-toggle="tooltip" href="" target="_blank" title="Click for more info">?</a> 
							<div>
								<div class="form-group select optional endpoint_test_type">
									<div>
										<select class="select optional form-control" id="endpoint_test_type" name="endpoint[test_type]" value="persecond">
											<option value="total">Clients per test</option>
											<option selected="selected" value="persecond">Clients per second</option>
											<option value="concurrent">Maintain client load</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xs-6">
							<p class="endpoint-note  hidden" data-test-type="concurrent">
								A constant client count will be maintained throughout the test duration. <a data-toggle="popover" title="Example:" data-content='"How does my server perform over a 5 minute period when there is a constant load of 100 to 200 clients connected?"' role="button" class="hint-badge" data-placement="top" data-trigger="hover">?</a> 
							</p>
							<p class="endpoint-note  hidden" data-test-type="total">
								Clients will be distributed evenly throughout the test duration. <a data-toggle="popover" title="Example:" data-content='"How does my server perform when 5000 users connect over the course of 5 minutes?"' role="button" class="hint-badge" data-placement="top" data-trigger="hover">?</a> 
							</p>
							<p class="endpoint-note " data-test-type="persecond">
								Client requests made each second. <a data-toggle="popover" title="Example:" data-content='"How does my server perform when 5 users connect every second over a 5 minute period?"' role="button" class="hint-badge" data-placement="top" data-trigger="hover">?</a> 
							</p>
						</div>
					</div>

<!-- end of the endpoint type -->
<!-- Connection options-->
					<div class="connections connection-fields row">
						<div class="form-group string optional disabled endpoint_min_processes_count col-xs-3 hidden min-clients">
							<label class="string optional control-label" for="endpoint_min_processes_count">Clients from</label>
							<div>
								<input class="string optional disabled form-control" disabled="disabled" id="endpoint_min_processes_count" name="endpoint[min_processes_count]" type="text" value="0" />
							</div>
						</div>
						<div class="form-group string optional endpoint_max_processes_count col-xs-3 max-clients">
							<label class="string optional control-label" for="endpoint_max_processes_count">Clients</label>
							<div>
								<input class="string optional form-control" id="endpoint_max_processes_count" name="endpoint[max_processes_count]" type="text" value="10" />
							</div>
						</div>
						<div class="form-group string optional endpoint_duration col-xs-3">
							<label class="string optional control-label" for="endpoint_duration">Duration</label>
							<div>
								<input class="string optional form-control" id="endpoint_duration" name="endpoint[duration]" type="text" value="1" />
							</div>
						</div>
						<div class="form-group select optional endpoint_duration_uom col-xs-3">
							<label class="select optional control-label" for="endpoint_duration_uom">&nbsp;</label>
							<div>
								<select class="select optional form-control" id="endpoint_duration_uom" name="endpoint[duration_uom]" value="Sec">
									<option value="Sec">Sec</option>
									<option selected="selected" value="Min">Min</option>
								</select>
							</div>
						</div>
					</div>

<!-- end connection options-->
					<div class="advanced-settings">
						<a class="add-config-button">Advanced settings</a> 
						<div class="inner-panel featured hidden">
							<h4>
								Advanced settings
							</h4>
							<div class="row mbm">
								<div class="form-group string optional endpoint_error_threshold col-xs-3">
									<label class="string optional control-label" for="endpoint_error_threshold">Error (%)</label>
									<div>
										<input class="string optional xsmall form-control" id="endpoint_error_threshold" name="endpoint[error_threshold]" type="text" value="50" />
									</div>
								</div>
								<div class="form-group string optional endpoint_timeout col-xs-3">
									<label class="string optional control-label" for="endpoint_timeout">Timeout</label>
									<div>
										<input class="string optional form-control" id="endpoint_timeout" name="endpoint[timeout]" type="text" value="10" />
									</div>
								</div>
								<div class="form-group select optional endpoint_timeout_uom pull-left">
									<div>
										<select class="select optional small form-control" id="endpoint_timeout_uom" name="endpoint[timeout_uom]" value="ms">
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
								<div class="form-group string optional endpoint_user_login col-xs-4">
									<div>
										<input autocomplete="off" class="string optional form-control" id="endpoint_user_login" name="endpoint[user_login]" placeholder="Username" type="text" />
									</div>
								</div>
								<div class="form-group string optional endpoint_user_password col-xs-4">
									<div>
										<input autocomplete="off" class="string optional form-control" id="endpoint_user_password" name="endpoint[user_password]" placeholder="Password" type="text" />
									</div>
								</div>
							</div>
						</div>
					</div>

<!-- end of the advanced options -->
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-panel featured">
					<div class="endpoint-notes form-group">
						<div class="form-group text optional endpoint_notes">
							<label class="text optional control-label" for="endpoint_notes">Notes</label>
							<div>
								<textarea class="text optional form-control" id="endpoint_notes" name="endpoint[notes]" placeholder="Enter notes about this test" rows="7">
								</textarea>
							</div>
						</div>
					</div>
					<div class="tags-form form-group clearfix">
						<input id='tag_names' type="text" class="form-control col-sm-6" placeholder="Add tag" autocomplete="off" data-provide="typeahead" data-source="[&quot;memcached&quot;]"> 
					</div>
					<div class="endpoint-tags">
					</div>
				</div>
			</div>
		</div>

<!-- end of the test settings form section -->
		<div class="row">
			<label class="col-md-2 mtm">Client Requests</label> 
			<div class="endpoint-url-fields col-md-10">
				<div class="endpoint-url-field form-panel" data-index="0">
					<div class="panel-controls">
						<a href="#" class="action-icon move-up disabled" title="Move this request up" data-toggle="tooltip" data-container="body"></a> <a href="#" class="action-icon move-down disabled" title="Move this request down" data-toggle="tooltip" data-container="body"></a> <a href="#" class="action-icon copy" title="Copy target" data-toggle="tooltip" data-container="body"></a> <a href="#" class="remove-url action-icon delete disabled" title="Delete" data-toggle="tooltip" data-container="body"></a> 
					</div>
					<div class="endpoint-url row" id="endpoint_url_0">
						<div class="col-sm-3">
							<div class="row">
								<div class="form-group select optional endpoint_endpoint_urls_request_type col-xs-6 request_method">
									<label class="select optional control-label" for="endpoint_endpoint_urls_attributes_0_request_type">Method</label>
									<div>
										<select class="select optional form-control" id="endpoint_endpoint_urls_attributes_0_request_type" name="endpoint[endpoint_urls_attributes][0][request_type]" value="GET">
											<option selected="selected" value="GET">GET</option>
											<option value="POST">POST</option>
											<option value="PUT">PUT</option>
											<option value="PATCH">PATCH</option>
											<option value="DELETE">DELETE</option>
										</select>
									</div>
								</div>
								<div class="form-group select optional endpoint_endpoint_urls_protocol col-xs-6">
									<label class="select optional control-label" for="endpoint_endpoint_urls_attributes_0_protocol">Protocol</label>
									<div>
										<select class="select optional form-control" id="endpoint_endpoint_urls_attributes_0_protocol" name="endpoint[endpoint_urls_attributes][0][protocol]" value="http">
											<option selected="selected" value="HTTP">HTTP</option>
											<option value="HTTPS">HTTPS</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group select optional endpoint_endpoint_urls_host host col-sm-3">
							<label for="endpoint_endpoint_urls_attributes_0_host" class="select optional control-label">Host</label> <a class="hint-badge" data-toggle="popover" title="Add a New Target Host" data-content="If you want to add a new host, click the <strong>Target Hosts</strong> link at the top of the page" data-html="true" data-placement="bottom" data-trigger="click">?</a> 
							<div>
								<div class="form-group select optional endpoint_endpoint_urls_host">
									<div>
										<select class="select optional form-control" id="endpoint_endpoint_urls_attributes_0_host" name="endpoint[endpoint_urls_attributes][0][host]" value="209.188.25.147">
											<option value="198.246.190.107">198.246.190.107</option>
											<option selected="selected" value="209.188.25.147">209.188.25.147</option>
											<option value="209.188.25.155:8181">209.188.25.155:8181</option>
											<option value="209.188.25.155:8282">209.188.25.155:8282 (unverified)</option>
											<option value="apifin.investkit.com">apifin.investkit.com</option>
											<option value="apireal.synapsys.us">apireal.synapsys.us</option>
											<option value="apirt.synapsys.us">apirt.synapsys.us</option>
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
											<option value="sports-ai.synapsys.us:91">sports-ai.synapsys.us:91 (unverified)</option>
											<option value="testapi.investkit.com:90">testapi.investkit.com:90</option>
											<option value="test.content.synapsys.us">test.content.synapsys.us</option>
											<option value="w1.synapsys.us">w1.synapsys.us (unverified)</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group string optional endpoint_endpoint_urls_path_without_leading_slash col-sm-6">
							<label class="string optional control-label" for="endpoint_endpoint_urls_attributes_0_path_without_leading_slash">Path</label>
							<div>
								<input class="string optional form-control" id="endpoint_endpoint_urls_attributes_0_path_without_leading_slash" name="endpoint[endpoint_urls_attributes][0][path_without_leading_slash]" type="text" value="index.html" />
							</div>
						</div>
					</div>

<!-- endpoint-url -->
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
								<input type="radio" name="endpoint[endpoint_urls_attributes][0][paramType]" value="keyval" class="keyval" checked="checked"> Key/ Value </label> <label class="radio-inline"> 
								<input type="radio" name="endpoint[endpoint_urls_attributes][0][paramType]" value="rawbody" class="rawbody" disabled> Raw Body </label> 
							</div>
							<h4>
								Parameters &amp; Request Body <a class="hint-badge" data-toggle="popover" title="Parameters by request method:" data-content='<p>For <code>GET</code> and <code>DELETE</code> methods, keys and values are converted to <strong>URL query</strong> parameters.</p>
         <p>For <code>POST/ PUT/ PATCH</code>, parameters are converted into a <code>form-urlencoded</code> request body.</p>
         To use the "Raw Body," select the <code>PUT</code> <code>POST</code> or <code>PATCH</code> request type and add a <code>Content-Type</code> header.' data-html="true" data-placement="top" data-trigger="click">?</a> 
							</h4>
							<div class="row raw_post_body hidden">
								<div class="col-xs-12">
									<div class="config-field">
										<div class="form-group text optional disabled endpoint_endpoint_urls_raw_post_body col-xs-12">
											<div>
												<textarea class="text optional disabled form-control" disabled="disabled" id="endpoint_endpoint_urls_attributes_0_raw_post_body" name="endpoint[endpoint_urls_attributes][0][raw_post_body]" placeholder="Enter raw request body">
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
											<div class="form-group url optional endpoint_endpoint_urls_endpoint_params_file_url col-xs-4 ">
												<input placeholder="Payload file URL" class="string url optional form-control params-file-url" name="endpoint[endpoint_urls_attributes][0][endpoint_params_file_attributes][url]" size="50" type="text" value=""> 
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
					<input class="numeric integer optional id" id="endpoint_endpoint_urls_attributes_0_id" name="endpoint[endpoint_urls_attributes][0][id]" step="1" type="hidden" value="1347767" />
				</div>
			</div>
		</div>
<!-- end of the endpoint urls form section -->
		<div class="add-target clearfix mbm">
			<a href="#" class="pull-right btn btn-default add-new-url" data-url-limit="10"><i class="glyphicon glyphicon-plus"></i> Add Request</a> <a href="/billing_addons" target="_blank" class="pull-right btn btn-primary increase-max-urls-limit mrm hidden">Use add-ons to add more URLs</a> 
		</div>

<!-- end of the add target block -->
		<div class="trash">
<!-- Trashed urls end up here -->
		</div>
		<div class="form-group hidden endpoint_copied">
			<div>
				<input class="hidden form-control" id="endpoint_copied" name="endpoint[copied]" type="hidden" />
			</div>
		</div>
		<div class="form-submit row">
			<div class="col-sm-2">
				<a class="btn btn-default btn-outsize" href="/tests/fcc4d82da6712948bd6166d849d760d3">Back</a> 
			</div>
			<div class="col-sm-10">
				<div class="start-test-block row ">
					<div class="col-md-8">
						<input class="btn-main btn-outsize mrs" name="commit" type="submit" value="Run test" />
						<span class="mrs">or</span> 
						<input class="btn btn-info btn-outsize mrs" name="commit" type="submit" value="Save for later" />
						<span class="mrs">or</span> <a class="switch-schedule-option btn btn-default btn-outsize" href="#">Schedule this test</a> 
					</div>
					<div class="col-md-4">
					</div>
				</div>
				<div id="schedule" class="panel-block hidden schedule-test-block">
					<div class="row">
						<div class="col-sm-8 form-horizontal scheduler">
							<div class="">
								<div class="form-group hidden endpoint_next_run_at">
									<div>
										<input class="hidden form-control" id="endpoint_next_run_at" name="endpoint[next_run_at]" type="hidden" />
									</div>
								</div>
								<div class="form-group hidden endpoint_stop_schedule_at">
									<div>
										<input class="hidden form-control" id="endpoint_stop_schedule_at" name="endpoint[stop_schedule_at]" type="hidden" />
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
													<input type="checkbox" id="inlineCheckbox1" name="endpoint[schedule_settings][sunday]" value="1">Sun </label> <label class="checkbox-inline"> 
													<input type="checkbox" id="inlineCheckbox2" name="endpoint[schedule_settings][monday]" value="1">Mon </label> <label class="checkbox-inline"> 
													<input type="checkbox" id="inlineCheckbox3" name="endpoint[schedule_settings][tuesday]" value="1">Tue </label> <label class="checkbox-inline"> 
													<input type="checkbox" id="inlineCheckbox4" name="endpoint[schedule_settings][wednesday]" value="1">Wed </label> <label class="checkbox-inline"> 
													<input type="checkbox" id="inlineCheckbox5" name="endpoint[schedule_settings][thursday]" value="1">Thu </label> <label class="checkbox-inline"> 
													<input type="checkbox" id="inlineCheckbox6" name="endpoint[schedule_settings][friday]" value="1">Fri </label> <label class="checkbox-inline"> 
													<input type="checkbox" id="inlineCheckbox7" name="endpoint[schedule_settings][saturday]" value="1">Sat </label> 
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

<?php
	break; //end edit, copy or new
}



////////////////////////////// NOT EDIT OR NEW ////////////////////////////
if ($action!='new' && $action!='edit' && $action!='copy'){
//print_r($_POST);
//print_r($_GET);

	$completed = 0;
	$sql = "SELECT count(DISTINCT `template`) as `Completed`  FROM `tsung_statusinfo` WHERE `finished_at` IS NOT NULL";
	if ($res = $mysqli->query($sql)){
		while ($row = $res->fetch_array(MYSQLI_ASSOC)){
			$completed_count = $row['Completed'];
		}
	}

	$draft_count = $testplan_count - $completed_count;


foreach ($testplan_list as $template){
// URL(s)	Profile	Created	Run count	Last run

	$templates[$template]['urls'][] = 'http://testtargeturlhere.com/foo';
	$templates[$template]['urls'][] = 'http://testtargeturlhere.com/bar';
	$templates[$template]['urls'][] = 'http://testtargeturlhere.com/wow';

	$templates[$template]['profile'] = '      <p>DUMMY DATA - Clients per second</p>
      <p class="text-muted">(<span class="number">100</span> over <span class="number">1</span> min)</p>
';

	//Created
	$sql = "SELECT `started_at` FROM `tsung_statusinfo` WHERE `template` = '{$template}' ORDER BY `started_at` DESC LIMIT 1";

	$templates[$template]['created'] = '';
	if ($res = $mysqli->query($sql)){
		while ($row = $res->fetch_array(MYSQLI_ASSOC)){
			$templates[$template]['created'] = $row['started_at'];
		}
	}

	//Run count
	$sql = "SELECT count(`started_at`) as `run_count`  FROM `tsung_statusinfo` WHERE `template` = '{$template}'";

	$templates[$template]['run_count'] = '';
	if ($res = $mysqli->query($sql)){
		while ($row = $res->fetch_array(MYSQLI_ASSOC)){
			$templates[$template]['run_count'] = $row['run_count'];
		}
	}
	

	//Last run
	$sql = "SELECT `started_at` FROM `tsung_statusinfo` WHERE `template` = '{$template}' ORDER BY `started_at` ASC LIMIT 1";

	$templates[$template]['last_run'] = '';
	if ($res = $mysqli->query($sql)){
		while ($row = $res->fetch_array(MYSQLI_ASSOC)){
			$templates[$template]['last_run'] = $row['started_at'];
		}
	}

	//echo "<li><b class='title'>".(str_replace('_',' ',$template))."</b></a></li>";
}

?>
<main >
	<div class="page">
		<div class="clearfix">
			<h2 class="pull-left">All tests</h2>
			<a href="?page=tests&amp;action=new" class="pull-right btn-main btn-big"><span class="glyphicon glyphicon-plus"></span> New Test</a>
		</div>
  <div class="filter">
    <div class="filter-panel">
      <div class="section"><label>Show:</label>
    	<div class="btn-group btn-group-sm">
    	<?php 
    	$buttons = array('Active','Completed','Draft','Scheduled','Archived');
    	if (! $type){ $type = 'active'; }
    	foreach ($buttons as $button){
			echo '<a class="btn btn-default';
			if ($type == strtolower($button)){echo ' active';}
			echo '" href="?page=tests&amp;type=';
			echo strtolower($button);
			if ($order)
			{
				echo '&amp;order='.$order;
			}
			if ($search)
			{
				echo '&amp;search='.urlencode($search);
			}
			if ($search_filter)
			{
				echo '&amp;search_filter='.urlencode($search_filter);
			}
			echo'">';
			echo $button;
			$button = strtolower($button);
			echo ' (';
			echo $tsungUI->getTestCount($button);
			echo ')</span></a>
';
}
?>
    	</div>
    </div>
	<div class="section"><label>Sort:</label>
		<div class="btn-group btn-group-sm">
			<a class="btn btn-default<?php if ($order == 'DESC'){echo ' active';} ?>" href="?page=tests<?php 
		if ($type){
			echo '&amp;type='.$type;
		}
		if ($search){
			echo '&amp;search='.urlencode($search);
		}
		if ($search_filter){
			echo '&amp;search_filter='.urlencode($search_filter);
		}
		?>&amp;order=DESC">Newest</a>
			<a class="btn btn-default<?php if ($order == 'ASC'){echo ' active';} ?>" href="?page=tests<?php 
		if ($type){
			echo '&amp;type='.$type;
		}
		if ($search){
			echo '&amp;search='.urlencode($search);
		}
		if ($search_filter){
			echo '&amp;search_filter='.urlencode($search_filter);
		}
			?>&amp;order=ASC">Oldest</a>
		</div>
	</div>
      <div class="section search">
        <label>Search: </label>
        <div class="input-wrapper">
          <form method="get" id="search-box">
            <div class="form-group has-feedback">
			<input type="hidden" name="page" value="reports">
              <div class="input-group">
                 <div class="input-group-btn">
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-filter"></i></button>
                    <ul id='filter-dropdown' class="dropdown-menu dropdown-menu-form" role="menu">
                      <li role="presentation" class="dropdown-header">Filter search by:</li>
                      <li>
                        <div class="radio"><label><input <?php if (($search_filter == 'all') || ($search_filter == '')){ echo ' checked="checked"';} ?> id="search_filter_all" name="search_filter" type="radio" value="all" />All</label></div>
                      </li>
                      <li>
                        <div class="radio"><label><input <?php if ($search_filter == 'names'){ echo ' checked="checked"';} ?> id="search_filter_tests" name="search_filter" type="radio" value="names" />Names</label></div>
                      </li>
                      <li>
                        <div class="radio"><label><input <?php if ($search_filter == 'urls'){ echo ' checked="checked"';} ?> id="search_filter_urls" name="search_filter" type="radio" value="urls" />URLs</label></div>
                      </li>
                      <li>
                        <div class="radio"><label><input <?php if ($search_filter == 'profile'){ echo ' checked="checked"';} ?> id="search_filter_tags" name="search_filter" type="radio" value="profile" />Profiles</label></div>
                      </li>
                      <li>
                        <div class="radio"><label><input <?php if ($search_filter == 'notes'){ echo ' checked="checked"';} ?> id="search_filter_notes" name="search_filter" type="radio" value="notes" />Notes</label></div>
                      </li>
                    </ul>
                  </div><!-- /btn-group -->
                <input type="text" id="search-box" class="form-control typeahead first" name="search" placeholder="Search for tests, urls, tags, and notes" value="<?php echo $search; ?>">
			<input type="hidden" name="type" value="<?php echo $type; ?>">
			<input type="hidden" name="order" value="<?php echo $order; ?>">
                <span class="input-group-btn go-search-buttom">
                  <button class="btn btn-default" type="submit">Go!</button>
                </span>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

		<div >
			<div>
			  <table class="list-table">
				<thead>
				  <tr>
					<th class="name">Name, URL(s)</th>
					<th>Profile</th>
					<th>Created</th>
					<th>Run count</th>
					<th>Last run</th>
					<th>Actions</th>
				  </tr>
				</thead>
				<tbody id="endpointsList">
			<?php
	foreach ($testplan_list as $template){

	echo '<tr><td class="name">	<span class="item-name"><a href="?page=tests&amp;template=';
	echo urlencode($template);
	echo '">';
	echo str_replace('_',' ',$template);
	echo '</a></span>
	<div class="short-urls-list">
	<a class="text-muted" href="?page=tests&amp;template=';
	echo urlencode($template);
	echo '">'; 
	echo $templates[$template]['urls'][0];
	echo '</a>';
	if (count($templates[$template]['urls']) > 1){
		echo '<a href="#more" class="show-all-urls">(';
		echo count($templates[$template]['urls']);
		echo ' more <span class="caret"></span>)</a>';
	}
	echo '
	</div>';
	echo '      <div class="all-urls-list hidden">
		  <div class="endpoint-list-url">
	';
	foreach ($templates[$template]['urls'] as $url){
		echo '	<a class="text-muted" href="?page=tests&amp;template=';
		echo urlencode($template);
		echo '">';
		echo $url;
		echo '</a>
	';
	}
	echo '
			  <a href="#hide" class="hide-all-urls">(less <span class="caret-up"></span>)</a>
		  </div>
	  </div>
	</td>
	';
	echo '<td class="profile">';
	echo $templates[$template]['profile'];
	echo '</td>';
	echo '
	<td class="created-date">
	  <p class="number">';
		if ($templates[$template]['created'] != ''){
			if (strtotime($templates[$template]['created']) > strtotime('24 hours ago')){
				echo date('D, M. j Y', strtotime($templates[$template]['created']));
			}else{
				echo date('g:i a', strtotime($templates[$template]['created']));
			}
		}else{
			echo '—';
		}
	echo '</p>
	</td>';
	echo '    <td class="count">
	  <p class="number">';
		echo $templates[$template]['run_count'];
	echo '</p>
	</td>';
	echo '    <td class="run-date">
		<p class="number">';
		if ($templates[$template]['last_run'] != ''){
			if (strtotime($templates[$template]['last_run']) > strtotime('24 hours ago')){
				echo date('D, M. j Y', strtotime($templates[$template]['last_run']));
			}else{
				echo date('g:i a', strtotime($templates[$template]['last_run']));
			}
		}else{
			echo '—';
		}
	echo '</p>
	</td>
	';
	echo '<td class="actions">
	<a class="action-icon repeat" data-container="body" data-method="put" data-toggle="tooltip" href="?page=status&amp;action=re_run&amp;template='.urlencode($template).'" id="re-run-test" rel="nofollow" title="Re-run"></a>
	<a class="action-icon schedule" data-container="body" data-toggle="tooltip" href="?page=tests&amp;action=schedule&amp;template='.urlencode($template).'" title="Schedule"></a>
	<a class="action-icon edit" data-container="body" data-toggle="tooltip" href="?page=tests&amp;action=edit&amp;template='.urlencode($template).'" title="Edit"></a>
	<a class="action-icon copy" data-container="body" data-toggle="tooltip" href="?page=tests&amp;action=copy&amp;template='.urlencode($template).'" title="Copy"></a>
	<a class="action-icon archive" data-container="body" data-method="put" data-toggle="tooltip" href="?page=tests&amp;action=archive&amp;template='.urlencode($template).'" rel="nofollow" title="Archive"></a>
	</td>';
	echo '  </tr>
	';
	}
			?>
				</tbody>
			  </table>
			</div><!-- /table-responsive -->
		</div><!-- /endpointsListBox -->


    </div>
  </main>
<?php
}//end if not action

/*
$tsung_config = array();
$tsung_config['tsung']['servers']['server']['host']="www.synapsys.us";
$tsung_config['tsung']['servers']['server']['port']="90";
echo "<pre>";
echo htmlentities(($tsungUI->create_test($tsung_config)));
echo "</pre>";
*/

?>

  <footer class="footer">
    <div class="container">

    </div>
  </footer>


</body>
</html>

