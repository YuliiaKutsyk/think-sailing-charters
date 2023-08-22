<div class="booking-inputs" style="display: none;">
	<input type="hidden" name="mvvwb_start" value="<?php echo isset($_GET['start']) ? $_GET['start'] : 'false'; ?>">
	<input type="text" name="mvvwb_duration" value="<?php echo isset($_GET['duration']) ? $_GET['duration'] : ''; ?>">
	<input type="hidden" name="mvvwb_timeStart" value="0">
	<input class="mvvwb_input" type="number" name="mvvwb_adult">
	<input class="mvvwb_input" type="number" name="mvvwb_children">
	<input class="mvvwb_input" type="number" name="mvvwb_infants">
</div>