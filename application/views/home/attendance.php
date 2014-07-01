<div id="attendance-header" class="page-header">
    <h1>考勤情况</h1>
</div> <!-- /attendance-header -->
<div id="attendance-content" class="section">
    <?php
        if ( $extra['user_groups'] == 'Study-Monitors' ||
             $extra['user_groups'] == 'Sports-Monitors' ) {
            echo '<div class="tabbable">';
            echo '<ul class="nav nav-tabs">';
            echo '<li id="view-attendance-nav" class="active"><a href="javascript:void(0)">查看</a></li>';
            echo '<li id="edit-attendance-nav"><a href="javascript:void(0)">编辑</a></li>';
            echo '</ul>';
            echo '<div class="tab-content">';
            echo '<div id="view-attendance-tab" class="tab-pane active">';
            require_once(APPPATH.'views/home/attendance-view.php');
            echo '</div>';
            echo '<div id="edit-attendance-tab" class="tab-pane">';
            require_once(APPPATH.'views/home/attendance-edit.php');
            echo '</div>';
            echo '</div>';
            echo '</div>';
        } else {
            require_once(APPPATH.'views/home/attendance-view.php');
        }
    ?>
</div> <!-- /attendance-section -->

<script type="text/javascript">
    $('#edit-attendance-nav').click(function(){
        $('#view-attendance-nav').removeClass('active');
        $('#view-attendance-tab').removeClass('active');
        $('#edit-attendance-nav').addClass('active');
        $('#edit-attendance-tab').addClass('active');
        set_footer_position();
    });
    $('#view-attendance-nav').click(function(){
        $('#edit-attendance-nav').removeClass('active');
        $('#edit-attendance-tab').removeClass('active');
        $('#view-attendance-nav').addClass('active');
        $('#view-attendance-tab').addClass('active');
        set_footer_position();
    });
</script>

