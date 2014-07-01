<div id="transcripts-header" class="page-header">
    <h1>成绩分析</h1>
</div> <!-- /transcripts-header -->
<div id="transcripts-content" class="section">
	<div id="selector">
		<select id="available-years" class="span2">
			<?php
				foreach ( $available_years as $available_year ) {
					$year = $available_year['school_year'];
					echo '<option value="'.$year.'">'.$year.'-'.($year + 1).'学年</option>';
				}
			?>
		</select>
		<select id="available-grades" class="span2">

		</select>
	</div> <!-- /selector -->
</div> <!-- /transcripts-content -->