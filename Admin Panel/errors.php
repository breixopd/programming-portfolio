<?php  if (count($errors) > 0) : ?>
  <!-- If the amount of errors passed through from server.php is more than 0 they will be sent and displayed on the relevant page -->

  <!-- HTML code for error display -->
  <div class="error">

    <!-- Display each error until there aren't any more -->
  	<?php foreach ($errors as $error) : ?>
  	  <p style="font-size: 15px;"><?php echo $error ?></p>
  	<?php endforeach ?>
  </div>
<?php  endif ?>