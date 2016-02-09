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
      <div class="section"><label>Show:</label><div class="btn-group btn-group-sm"><a class="btn btn-default active" href="?page=tests">Active <span class="active-count">(<?php // echo $testplan_count; ?>)</span></a><a class="btn btn-default " href="?page=tests&amp;type=completed">Completed <span class="completed-count">(<?php // echo $completed_count; ?>)</span></a><a class="btn btn-default " href="?page=tests&amp;type=draft">Draft (<?php // echo $draft_count; ?>)<span class="draft-count"></span></a><a class="btn btn-default " href="?page=tests&amp;type=scheduled">Scheduled <span class="scheduled-count"></span></a><a class="btn btn-default " href="?page=tests&amp;type=archived">Archived <span class="archived-count">(3)</span></a></div></div>
      <div class="section"><label>Sort:</label><div class="btn-group btn-group-sm"><a class="btn btn-default active" href="?page=tests&amp;order=desc">Newest</a><a class="btn btn-default " href="?page=tests&amp;order=asc">Oldest</a></div></div>
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
			$testplan_list = $tsungUI->getTestplanList();
			foreach ($testplan_list as $dir){
				if ($dir<>'.' && $dir<>'..'){
					$subdirs = sntmedia_PATH_TEMPLATES.$dir.'/log/';
					if (is_dir($subdirs))
					{
						foreach (scandir($subdirs, SCANDIR_SORT_DESCENDING) as $subdir)
						{
							if ($subdir<>'.' && $subdir<>'..' && is_dir($subdirs.$subdir))
							{
								$info = $tsungUI->getTestInfoByPath($dir, $subdir);
								if (file_exists($subdirs.$subdir.'/report.html'))
								{
									echo "<tr><td><a href='?page=reports&config=$dir&starttime=$subdir'><b class='title'>".(str_replace('_',' ',$dir))."</b></a></td>";
									if (isset ($info['started_at'])){
										$labeldate = date('D, M. j Y g:i a', strtotime($info['started_at']));
									}else{
										$datetimes = explode ('-', $subdir);
										$labeldate = date('D, M. j Y', strtotime($datetimes[0])).date(' g:i a', strtotime($datetimes[1]));
									}
									echo '<td>'.$labeldate.'</td>';
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
									if ($info['comment']){
										echo '<td>'.$info['comment'].'</td>';
									}else{
										echo '<td></td>';
									}
									echo '<td class="actions">
<a class="action-icon repeat" data-container="body" data-method="put" data-toggle="tooltip" href="?page=tests&amp;action=re_run&amp;id='.$subdir.'" id="re-run-test" rel="nofollow" title="Re-run"></a>
<a class="action-icon schedule" data-container="body" data-toggle="tooltip" href="?page=tests&amp;action=schedule&amp;id='.$subdir.'" title="Schedule"></a>
<a class="action-icon edit" data-container="body" data-toggle="tooltip" href="?page=tests&amp;action=edit&amp;id='.$subdir.'" title="Edit"></a>
<a class="action-icon copy" data-container="body" data-id="'.$subdir.'" data-toggle="tooltip" href="?page=tests&amp;action=copy&amp;id='.$subdir.'" title="Copy"></a>
<a class="action-icon archive" data-container="body" data-method="put" data-toggle="tooltip" href="?page=tests&amp;action=archive&amp;id='.$subdir.'" rel="nofollow" title="Archive"></a>
    </td>';
									echo "</tr>\n";
								}
							}
						}
					}

				}
			}
		?>
	</ul>
	
	</tbody>
	</table>
	
</div>

<?php }; ?>
