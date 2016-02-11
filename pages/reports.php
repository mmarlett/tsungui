<?php
if(!defined('sntmedia_TSUNG_UI')){die();}
if (isset ($_GET['config'])){
	$dir = $_GET['config'];
}else{
	$dir = '';
}
if (! isset ($_GET['starttime'])){
	$_GET['starttime'] = '';
}
$subdir = $_GET['starttime'];
$path = $tsungUI->path2url(sntmedia_PATH_TEMPLATES.$dir.'/log/'.$subdir.'/report.html');
$order = 'DESC';// ASC or DESC
if (isset ($_GET['order']))
{
	if (($_GET['order']=='ASC') || ($_GET['order']=='DESC'))
	{
		$order = $_GET['order'];
	}
}

$type = '';// completed, active, archived
if (isset ($_GET['type']))
{
	if (($_GET['type']=='active') || ($_GET['type']=='archived'))
	{
		$type = $_GET['type'];
	}
}



if ($subdir && $dir){
?>
<div style="font-size: 16px; padding: 5px;">
	<?php
		$info = $tsungUI->getTestInfoByPath($dir, $subdir);
		echo "<span style='float: right;'>".date('D, M. j Y g:i a',strtotime($_GET['starttime'])).'</span>';
		echo "<b>Test: </b>".$info['template'];
		echo "&nbsp;&nbsp;&nbsp;<span style='color: #999;'>".$info['comment'].'</span>';
	?>  
</div>
<div style="position: fixed; top: 135px; left: 0px; right: 0px; bottom: 50px;">
	<iframe src="<?php echo $path; ?>" style="width: 100%; height: 100%;"></iframe>
</div>

<?php } else { ?>
  <main >
    <div class="flash-messages-placeholder page ">
  <div class="clearfix">
      <h2 class="pull-left">All results</h2>
    <a href="?page=tests&amp;action=new" class="pull-right btn-main btn-big"><span class="glyphicon glyphicon-plus"></span> New Test</a>
  </div>

  <div class="filter">
    <div class="filter-panel">
      <div class="section"><label>Show:</label>
    	<div class="btn-group btn-group-sm">
    	
    	<a class="btn btn-default<?php if ($type == ''){echo ' active';} ?>" href="?page=reports&amp;type=completed<?php if ($order){echo '&amp;order='.$order;} ?>">Completed <span class="completed-count">(<?php
    	echo $tsungUI->getReportCount('finished');// echo $completed_count;
    	?>)</span></a>
	<a class="btn btn-default<?php
	if ($type == 'active'){echo ' active';}
	?>" href="?page=reports&amp;type=active<?php
	if ($order){echo '&amp;order='.$order;}
	?>">Active <span class="active-count">(<?php
	echo $tsungUI->getReportCount('active'); // echo $testplan_count;
	?>)</span></a>
    	<a class="btn btn-default<?php
    	if ($type == 'archived'){echo ' active';}
    	?>" href="?page=reports&amp;type=archived<?php
    	if ($order){echo '&amp;order='.$order;}
    	?>">Archived <span class="archived-count">(<?php
    	 echo $tsungUI->getReportCount('archived');// echo $archived_count;
    	 ?>)</span></a>
    	</div>
    </div>
	<div class="section"><label>Sort:</label>
		<div class="btn-group btn-group-sm">
			<a class="btn btn-default<?php if ($order == 'DESC'){echo ' active';} ?>" href="?page=reports<?php if ($type){echo '&amp;type='.$type;} ?>&amp;order=DESC">Newest</a>
			<a class="btn btn-default<?php if ($order == 'ASC'){echo ' active';} ?>" href="?page=reports<?php if ($type){echo '&amp;type='.$type;} ?>&amp;order=ASC">Oldest</a>
		</div>
	</div>
      <div class="section search">
        <label>Search: </label>
        <div class="input-wrapper">
          <form action="?page=tests" method="get" id="search-box">
            <div class="form-group has-feedback">
              <input type="hidden" name="type" value="">
              <div class="input-group">
                 <div class="input-group-btn">
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-filter"></i></button>
                    <ul id='filter-dropdown' class="dropdown-menu dropdown-menu-form" role="menu">
                      <li role="presentation" class="dropdown-header">Filter search by:</li>
                      <li>
                        <div class="radio"><label><input checked="checked" id="search_filter_all" name="search_filter" type="radio" value="all" />All</label></div>
                      </li>
                      <li>
                        <div class="radio"><label><input id="search_filter_tests" name="search_filter" type="radio" value="tests" />Name</label></div>
                      </li>
                      <li>
                        <div class="radio"><label><input id="search_filter_urls" name="search_filter" type="radio" value="urls" />URL</label></div>
                      </li>
                      <li>
                        <div class="radio"><label><input id="search_filter_tags" name="search_filter" type="radio" value="tags" />Tag</label></div>
                      </li>
                      <li>
                        <div class="radio"><label><input id="search_filter_notes" name="search_filter" type="radio" value="notes" />Notes</label></div>
                      </li>
                    </ul>
                  </div><!-- /btn-group -->
                <input type="text" id="search-box" class="form-control typeahead first" name="search" placeholder="Search for tests, urls, tags, and notes" value="">
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

<div class='page'>
	<h2>Results</h2>
      <table class="list-table tests-results">
        <thead>
          <tr>
            <th class="name">Name, URL(s)</th>
            <th>Run Date</th>
            <th>Page Mean</th>
            <th>Throughput</th>
            <th>Comment</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="endpointsList">

	
		<?php
			$report_list = $tsungUI->getTestplanReports($order, $type);
//			echo '<pre>';
//			print_r($report_list);
//				echo '</pre>';
		foreach ($report_list as $report){
				$info = $tsungUI->getTestInfoByPath($report['template'], $report['starttime']);
				echo '<tr><td><a href="?page=reports&config=';
				echo urlencode($report['template']);
				echo '&starttime=';
				$report['starttime'] = urlencode($report['starttime']);
				echo $report['starttime'];
				echo '><b class="title">';
				echo (str_replace('_',' ',$report['template']));
				echo '</b></a></td>';
				echo '<td>';
				echo date('D, M. j Y g:i a', strtotime($report['started_at']));
				echo '</td>';
				if ($info['page_mean']){
					echo $info['page_mean'];
				}else{
					echo '<td></td>';
				}
				if ($info['page_throughput']){
					echo $info['page_throughput'];
				}else{
					echo '<td></td>';
				}
				echo '<td>';
				echo htmlentities($report['comment']);
				echo '</td>';
				echo '<td class="actions">
<a class="action-icon repeat" data-container="body" data-method="put" data-toggle="tooltip" href="?page=status&amp;action=re_run&amp;id=';
				echo $report['starttime'];
				echo '" id="re-run-test" rel="nofollow" title="Re-run"></a>
<a class="action-icon schedule" data-container="body" data-toggle="tooltip" href="?page=tests&amp;action=schedule&amp;id=';
				echo $report['starttime'];
				echo '" title="Schedule"></a>
<a class="action-icon edit" data-container="body" data-toggle="tooltip" href="?page=tests&amp;action=edit&amp;id=';
				echo $report['starttime'];
				echo '" title="Edit"></a>
<a class="action-icon copy" data-container="body" data-id="';
				echo $report['starttime'];
				echo '" data-toggle="tooltip" href="?page=tests&amp;action=copy&amp;id=';
				echo $report['starttime'];
				echo '" title="Copy"></a>
				';
				if ($type == 'archived'){
					echo '<a class="action-icon trash" data-container="body" data-method="put" data-toggle="tooltip" href="?page=reports&amp;action=trash&amp;id=';
					echo $report['id'];
					if ($order){echo '&amp;order='; echo $order;} 
					if ($type){echo '&amp;type='; echo $type;}
					echo '" rel="nofollow" title="Delete"></a></td>';
				}else{
					echo '<a class="action-icon archive" data-container="body" data-method="put" data-toggle="tooltip" href="?page=reports&amp;action=archive&amp;id=';
					echo $report['id'];
					if ($order){echo '&amp;order='; echo $order;} 
					if ($type){echo '&amp;type='; echo $type;}
					echo '" rel="nofollow" title="Archive"></a></td>';
				}
				echo '</tr>
';

			}
		?>
	
	</tbody>
	</table>
	
</div>

<?php }; ?>
