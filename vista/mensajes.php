		<?php
			if(
				is_array($GLOBALS['SigecostErrors']['general']) && count($GLOBALS['SigecostErrors']['general']) > 0
				|| is_array($GLOBALS['SigecostInfo']['general']) && count($GLOBALS['SigecostInfo']['general']) > 0
			){
				echo '
		<div class="container" style="padding-top: 20px;">
			<a name="mensajes"></a>
				';
			
				if(is_array($GLOBALS['SigecostErrors']['general']) && count($GLOBALS['SigecostErrors']['general']) > 0){
		?>
			<div class="alert alert-danger alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<ul>
				<?php 	
					foreach($GLOBALS['SigecostErrors']['general'] as $error){
						echo '<li>' .$error . '</li>';
					}
				?>
				</ul>
			</div>
			<?php
				}
			
				if(is_array($GLOBALS['SigecostInfo']['general']) && count($GLOBALS['SigecostInfo']['general']) > 0){
			?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert">
						<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
					</button>
					<ul>
					<?php 	
						foreach($GLOBALS['SigecostInfo']['general'] as $error){
							echo '<li>' .$error . '</li>';
						}
					?>
					</ul>
				</div>
			<?php
				}
				
				echo '
		</div>
				';
			}
			?>