<?php

include "Booker.php";

function OauthBooker(){
	?>
<div class="container">
<div class="col-md-6">
 <form method="post" action="<?php  register_activation_hook( __FILE__, 'INSERT_booker' ); ?>">
  <label> Nom Utilisateur : <input type="text" name="login">
</label>
<label>
	Mot de passe <input type="password" name="password">
</label>
<label>  type : <select name="type"> 
<option value="AirBNB"> AirBNB</option>
<option value="Booking.com"> Booking.com  </option>
<option value="tripadviser">tripadviser</option>
</select></label>
<input type="submit" name="">
	</form>
</div>
</div>
	<?php
}
add_shortcode('AuthBooker','OauthBooker'); 


function INSERT_booker()
{
global $wpdb;
$booker= new Booker($_POST['login'],$_POST['password'],$_POST['type']);
if(isset($_POST['password']))	
{	$table_name ="wpio_booker";
	
	$wpdb->INSERT( 
		$table_name, 
		array( 
			
			'login' => $booker->getLogin(), 
			'password'=>$booker->getPassword(),
			'type'=>$booker->getType()
			
			
		) 
		
	);
}
} 
add_action( 'plugins_loaded', 'INSERT_booker' );









?>