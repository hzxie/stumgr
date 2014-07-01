<div id="hygiene-header" class="page-header">
	<h1>卫生情况</h1>
</div> <!-- /hygiene-header -->
<div id="hygiene-content" class="section">
	<?php
        if ( $extra['user_groups'] == 'Sanitation-Monitors' ) {
            echo '<div class="tabbable">';
            echo '<ul class="nav nav-tabs">';
            echo '<li id="view-hygiene-nav"><a href="javascript:void(0)">查看</a></li>';
            echo '<li id="edit-hygiene-nav" class="active"><a href="javascript:void(0)">编辑</a></li>';
            echo '</ul>';
            echo '<div class="tab-content">';
            echo '<div id="view-hygiene-tab" class="tab-pane">';
            require_once(APPPATH.'views/home/hygiene-view.php');
            echo '</div>';
            echo '<div id="edit-hygiene-tab" class="tab-pane active">';
            require_once(APPPATH.'views/home/hygiene-edit.php');
            echo '</div>';
            echo '</div>';
            echo '</div>';
        } else {
            require_once(APPPATH.'views/home/hygiene-view.php');
        }
    ?>
</div> <!-- /hygiene-section -->

<script type="text/javascript">
    $('#edit-hygiene-nav').click(function(){
        $('#view-hygiene-nav').removeClass('active');
        $('#view-hygiene-tab').removeClass('active');
        $('#edit-hygiene-nav').addClass('active');
        $('#edit-hygiene-tab').addClass('active');
        set_footer_position();
    });
    $('#view-hygiene-nav').click(function(){
        $('#edit-hygiene-nav').removeClass('active');
        $('#edit-hygiene-tab').removeClass('active');
        $('#view-hygiene-nav').addClass('active');
        $('#view-hygiene-tab').addClass('active');
        set_footer_position();
    });
</script>