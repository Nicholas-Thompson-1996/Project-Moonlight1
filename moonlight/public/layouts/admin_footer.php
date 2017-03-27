		</div>
		<div id="footer">Copyright <?php echo date("Y", time()); ?>,
		Nicholas Thompson</div>
	</body>
</html>
<?php if(isset($database)){$database->close_connection();} ?>