<?php

/**
 * Elements of form UI
 *
 * @copyright   Copyright (C) 2017, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPKB_HTML_Elements {

	private function add_defaults( array $input_array, array $custom_defaults=array() ) {

		$defaults = array(
			'id'                => '',
			'name'              => 'text',
			'value'             => '',
			'label'             => '',
			'title'             => '',
			'class'             => '',
			'main_label_class'  => '',
			'label_class'       => '',
			'input_class'       => '',
			'input_group_class' => '',
			'action_class'      => '',
			'desc'              => '',
			'info'              => '',
			'placeholder'       => '',
			'readonly'          => false,  // will not be submitted
			'required'          => '',
			'autocomplete'      => false,
			'data'              => false,
			'disabled'          => false,
			'size'              => 3,
			'max'               => 50,
			'current'           => null,
			'options'           => array()
		);
		$defaults = array_merge( $defaults, $custom_defaults );
		return array_merge( $defaults, $input_array );
	}

	private function add_common_defaults( array $input_array, array $custom_defaults=array() ) {
		$defaults = array(
			'id'                => '',
			'name'              => 'text',
			'value'             => '',
			'label'             => '',
			'title'             => '',
			'class'             => '',
			'main_label_class'  => '',
			'label_class'       => '',
			'input_class'       => '',
			'input_group_class' => '',
			'desc'              => '',
			'info'              => '',
			'placeholder'       => '',
			'readonly'          => false,  // will not be submitted
			'required'          => '',
			'autocomplete'      => false,
			'data'              => false,
			'disabled'          => false,
			'size'              => 3,
			'max'               => 50,
			'current'           => null,
			'options'           => array()
		);
		$defaults = array_merge( $defaults, $custom_defaults );
		return array_merge( $defaults, $input_array );
	}

	/**
	 * Renders an HTML Text field
	 *
	 * @param array $args Arguments for the text field
	 * @return string Text field
	 */
	public function text( $args = array() ) {

		$args = $this->add_defaults( $args );

		$id             =  esc_attr( $args['name'] );
		$autocomplete   = ( $args['autocomplete'] ? 'on' : 'off' );
		$readonly       = $args['readonly'] ? ' readonly' : '';

		$data = '';
		if ( ! empty( $args['data'] ) ) {
			foreach ( $args['data'] as $key => $value ) {
				$data .= 'data-' . $key . '="' . $value . '" ';
			}
		}		?>
		
		<li class="input_group <?php echo esc_html( $args['input_group_class'] )?>" id="<?php echo $id ?>_group" >

			<label class="<?php echo esc_html( $args['label_class'] )?>" for="<?php echo $id ?>">
				<?php echo esc_html( $args['label'] )?>
			</label>

			<div class="input_container <?php echo esc_html( $args['input_class'] ); ?>" id="">
				<input type="text"
				       name="<?php echo $id ?>"
				       id="<?php echo $id ?>"
				       autocomplete="<?php echo $autocomplete; ?>"
				       value="<?php echo esc_attr( $args['value'] ); ?>"
				       placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>"
						<?php
						echo $data . $readonly
						?>
                       maxlength="<?php echo $args['max']; ?>"
				/>
			</div>			<?php
			
			if ( ! empty( $args['info'] ) ) { ?>
				<span class='info-icon'><p class='hidden'><?php echo $args['info']; ?></p></span>			<?php 
			}			?>

		</li>
		<?php
	}

	/**
	 * Renders an HTML textarea
	 *
	 * @param array $args Arguments for the textarea
	 * @return string textarea
	 */
	public function textarea( $args = array() ) {

		$defaults = array(
			'name'        => 'textarea',
			'class'       => 'large-text',
			'rows'        => 4
		);
		$args = $this->add_defaults( $args, $defaults );

		$disabled = '';
		if( $args['disabled'] ) {
			$disabled = ' disabled="disabled"';
		}

		$id =  esc_attr( $args['name'] );		?>

		<li class="input_group <?php echo esc_html( $args['input_group_class'] )?>" id="<?php echo $id ?>_group" >

		<label class="<?php echo esc_html( $args['label_class'] )?>" for="<?php echo $id ?>">
			<?php echo esc_html( $args['label'] )?>
		</label>
			<div class="input_container <?php echo esc_html( $args['input_class'] )?>" id="">
				<textarea
					   rows="<?php echo esc_attr( $args['rows'] ); ?>"
				       name="<?php echo esc_attr( $args['name'] ); ?>"
				       id="<?php echo $id ?>"
				       value="<?php echo esc_attr( $args['value'] ); ?>"
				       placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>"
					<?php echo $disabled; ?> >
				</textarea>
			</div>

		</li>		<?php

		if ( ! empty( $args['info'] ) ) { ?>
			<span class="info-icon"><p class="hidden"><?php echo $args['info']; ?></p></span>		<?php 
		}

	}

	/**
	 * Renders an HTML Checkbox
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function checkbox( $args = array() ) {

		$defaults = array(
			'name'         => 'checkbox',
			'class'        => '',
		);
		$args = $this->add_defaults( $args, $defaults );
		$id             =  esc_attr( $args['name'] );
		$checked = checked( "on", $args['value'], false );		?>

		<li class="input_group <?php echo esc_html( $args['input_group_class'] )?>" id="<?php echo $id; ?>_group">

			<label class="<?php echo esc_html( $args['label_class'] )?>" for="<?php echo $id; ?>">
				<?php echo esc_html( $args['label'] ); ?>
			</label>

			<div class="input_container <?php echo esc_html( $args['input_class'] )?>" id="">
				<input type="checkbox"
				       name="<?php echo $id ?>"
				       id="<?php echo $id ?>"
				       value="on"
				       <?php echo $checked; ?> />
			</div>			<?php
			
			if ( ! empty( $args['info'] ) ) { ?>
				<span class='info-icon'><p class='hidden'><?php echo $args['info']; ?></p></span>			<?php 
			} ?>

		</li>		<?php
	}

	/**
	 * Renders an HTML radio button
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function radio_button( $args = array() ) {
		
		$defaults = array(
			'name'         => 'radio-buttons',
			'class'        => '',
		);
		$args = $this->add_defaults( $args, $defaults );
		$id =  esc_attr( $args['name'] );
		$checked = checked( 1, $args['value'], false );		?>

		<li class="input_group <?php echo esc_html( $args['input_group_class'] )?>" id="<?php echo $id ?>_group">

			<label class="<?php echo esc_html( $args['label_class'] )?>" for="<?php echo $id ?>">
				<?php echo esc_html( $args['label'] )?>
			</label>

			<div class="input_container <?php echo esc_html( $args['input_class'] )?>" id="">
				<input type="radio"
				       name="<?php echo $id ?>"
				       id="<?php echo $id ?>"
				       value="<?php echo esc_attr( $args['value'] ); ?>"
					<?php echo $checked; ?> />
			</div>			<?php
			
			if ( ! empty( $args['info'] ) ) { ?>
				<span class='info-icon'><p class='hidden'><?php echo $args['info']; ?></p></span>";			<?php 
			} ?>

		</li>		<?php
	}

	/**
	 * Renders an HTML drop-down box
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function dropdown( $args = array() ) {

		$defaults = array(
			'name'         => 'select',
		);
		$args = $this->add_defaults( $args, $defaults );

		$id =  esc_attr( $args['name'] );		?>
		
		<li class="input_group <?php echo esc_html( $args['input_group_class'] )?>" id="<?php echo $id ?>_group">
			<label class="<?php echo esc_html( $args['label_class'] )?>" for="<?php echo $id ?>">
				<?php echo esc_html( $args['label'] )?>
			</label>

			<div class="input_container <?php echo esc_html( $args['input_class'] )?>" id="">

				<select name="<?php echo $id ?>" id="<?php echo $id ?>">     <?php
					foreach( $args['options'] as $key => $label ) {
						$selected = selected( $key, $args['current'], false );
						echo '<option value="' . esc_attr($key) . '"' . $selected . '>' . esc_html($label) . '</option>';
					}  ?>
				</select>
			</div>		<?php
			
			if ( ! empty( $args['info'] ) ) { ?>
				<span class='info-icon'><p class='hidden'><?php echo $args['info']; ?></p></span>			<?php 
			}	?>
			
		</li>		<?php 
	}

	/**
	 * Renders several HTML radio buttons in a row
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function radio_buttons_horizontal( $args = array() ) {

		$defaults = array(
			'id'                => 'radio',
			'name'              => 'radio-buttons',
			'main_label_class'  => '',
			'radio_class'       => '',
		);
		$args = $this->add_defaults( $args, $defaults );
		$id =  esc_attr( $args['name'] );
		$ix = 0;   		?>

		<li class="input_group <?php echo esc_html( $args['input_group_class'] )?>" id="<?php echo $id ?>_group" >

			<span class="main_label <?php echo esc_html( $args['main_label_class'] )?>"><?php echo esc_html( $args['label'] ); ?></span>

			<div class="radio-buttons-horizontal <?php echo esc_html( $args['input_class'] )?>" id="<?php echo $id ?>">
				<ul>					<?php
				
					foreach( $args['options'] as $key => $label ) {
						$checked = checked( $key, $args['current'], false );						?>

						<li class="<?php echo esc_html( $args['radio_class'] )?>">
							<div class="input_container">
								<input type="radio"
								       name="<?php echo esc_attr( $args['name'] ); ?>"
								       id="<?php echo $id.$ix; ?>"
								       value="<?php echo esc_attr( $key ); ?>"									<?php
									echo $checked				?> 
								/>
							</div>

							<label class="<?php echo esc_html( $args['label_class'] )?>" for="<?php echo $id.$ix ?>">
								<?php echo esc_html( $label )?>
							</label>
						</li>						<?php

						$ix++;
					} //foreach    	?>

				</ul>  <?php

				if ( ! empty( $args['info'] ) ) { ?>
					<span class="info-icon"><p class="hidden"><?php echo ( is_array($args['info']) ? $args['info'] : $args['info'] ); ?></p></span>
				<?php } ?>
			</div>

		</li>		<?php
	}

	/**
	 * Renders several HTML radio buttons in a column
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function radio_buttons_vertical( $args = array() ) {
		$output = '';
		$defaults = array(
			'id'           => 'radio',
			'name'         => 'radio-buttons',
			'class'        => '',
		);
		$args = $this->add_defaults( $args, $defaults );

		$ix = 0;
		$id =  esc_attr( $args['name'] );	?>
		
		<li class="input_group <?php echo esc_html( $args['input_group_class'] )?>" id="<?php echo $id ?>_group" >

			<span class="main_label <?php echo esc_html( $args['main_label_class'] )?>"><?php echo esc_html( $args['label'] ); ?></span>

			<div class="radio-buttons-vertical <?php echo esc_html( $args['input_class'] )?>" id="<?php echo $id ?>">
				<ul>
					<?php
					foreach( $args['options'] as $key => $label ) {
						$id = empty($args['name']) ? '' :  'id="' . esc_attr($args['name'] ).$ix . '"';
						$checked = checked( $label, $args['current'], false );
						?>
						<li class="<?php echo esc_html( $args['radio_class'] )?>">
							<div class="input_container">
								<input type="radio"
								       name="<?php echo esc_attr( $args['name'] ); ?>"
								       id="<?php echo $id.$ix; ?>"
								       value="<?php echo esc_attr( $key ); ?>"									<?php
									echo $checked;	?> 
								/>
							</div>
							<label class="<?php echo esc_html( $args['label_class'] )?>" for="<?php echo $id.$ix ?>">
								<?php echo esc_html( $label )?>
							</label>
						</li>						<?php

						$ix++;
					}//foreach					?>
					
				</ul>
			</div>  <?php

			if ( ! empty( $args['info'] ) ) { ?>
				<span class="info-icon"><p class="hidden"><?php ( is_array($args['info']) ? $args['info'][$ix] : $args['info'] ); ?></p></span>			<?php 
			} ?>

		</li>		<?php
	}

	/**
	 * Single Inputs for text_fields_horizontal function
	 * @param array $args
	 */
	public function horizontal_text_input( $args = array() ){

		$args = $this->add_defaults( $args );

		//Set Values
		$id             =  esc_attr( $args[ 'name' ] );
		$autocomplete   = ( $args[ 'autocomplete' ] ? 'on' : 'off' );
		$disabled       = $args[ 'disabled' ] ? ' disabled="disabled"' : '';

		$data = '';
		if ( ! empty( $args['data'] ) ) {
			foreach ( $args['data'] as $key => $value ) {
				$data .= 'data-' . $key . '="' . $value . '" ';
			}
		}		?>

		<li class="<?php echo esc_html( $args['text_class'] )?>">

			<label class="<?php echo esc_html( $args['label_class'] )?>" for="<?php echo $id; ?>">
				<?php echo esc_html( $args['label'] )?>
			</label>
			<div class="input_container">
				<input type="text"
				       name="<?php echo $id; ?>"
				       id="<?php echo $id; ?>"
				       autocomplete='<?php echo $autocomplete; ?>'
				       value="<?php echo esc_attr( $args['value'] ); ?>"
				       placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>"
				       maxlength="<?php echo $args['max']; ?>"					<?php
						echo $data . $disabled;	?>	/>
			</div>

		</li>	<?php 
	}

	/**
	 * Renders two text fields. The second text field depends in some way on the first one
	 *
	 * @param array $common - configuration for the main classes
	 * @param array $args1  - configuration for the first text field
	 * @param array $args2  - configuration for the second field
	 *
	 * @return string
	 */
	public function text_fields_horizontal( $common = array(), $args1 = array(), $args2 = array() ) {

		$defaults = array(
			'name'         => 'text',
			'class'        => '',
		);

		$common = $this->add_common_defaults( $common, $defaults );

		$args1 = $this->add_defaults( $args1, $defaults );
		$args2 = $this->add_defaults( $args2, $defaults );		?>
		
		<li class="input_group <?php echo esc_html( $common['input_group_class'] )?>" id="<?php echo $common['id']; ?>_group" >
			<span class="main_label <?php echo esc_html( $common['main_label_class'] )?>"><?php echo esc_html( $common['label'] ); ?></span>
			<div class="text-fields-horizontal <?php echo esc_html( $common['input_class'] )?>">
				<ul>   <?php

					$this->horizontal_text_input($args1);
					$this->horizontal_text_input($args2);

					// HELP
					$help_text = $args1['info'] . ' ' . $args2['info'];
					if ( ! empty( $help_text ) ) { ?>
						<span class='info-icon'><p class='hidden'><?php echo $help_text; ?></p></span>					<?php 
					}  ?>

				</ul>
			</div>
		</li>		<?php
	}

	/**
	 * Renders two text fields that related to each other. One field is text and other is select.
	 *
	 * @param array $common
	 * @param array $args1
	 * @param array $args2
	 *
	 * @return string
	 */
	public function text_and_select_fields_horizontal( $common = array(), $args1 = array(), $args2 = array() ) {

		$args1 = $this->add_defaults( $args1 );
		$args2 = $this->add_defaults( $args2 );
		$common = $this->add_common_defaults( $common );		?>

		<li class="input_group <?php echo esc_html( $common['input_group_class'] )?>" id="<?php echo $common['id']; ?>_group" >
			<span class="main_label <?php echo esc_html( $common['main_label_class'] )?>"><?php echo esc_html( $common['label'] ); ?></span>
			<div class="text-select-fields-horizontal <?php echo esc_html( $common['input_class'] )?>">
				<ul>  <?php

					$this->text($args1);
					$this->dropdown($args2);

					// HELP
					$help_text = $common['info'];
					if ( ! empty( $help_text ) ) { ?>
						<span class='info-icon'><p class='hidden'><?php echo $help_text; ?></p></span>					<?php 
					}	?>

				</ul>
			</div>
		</li>		<?php
	}

	/**
	 * Renders several HTML checkboxes in several columns
	 *
	 * @param array $args
	 * @param $is_multi_select_not
	 * @return string
	 */
	public function checkboxes_multi_select( $args = array(), $is_multi_select_not ) {

		$defaults = array(
			'id'           => 'checkbox',
			'name'         => 'checkbox',
			'value'        => array(),
			'class'        => '',
			'main_class'   => '',
		);
		$args = $this->add_defaults( $args, $defaults );
		$id =  esc_attr( $args['name'] );
		$ix = 0;    	?>

		<li class="input_group <?php echo esc_html( $args['input_group_class'] )?>" id="<?php echo $id ?>_group" >

			<span class="main_label <?php echo esc_html( $args['main_label_class'] )?>"><?php echo esc_html( $args['label'] ); ?></span>

			<div class="checkboxes-vertical <?php echo esc_html( $args['input_class'] )?>" id="<?php echo $id ?>">
				<ul>  		<?php

					foreach( $args['options'] as $key => $label ) {

						$tmp_value = is_array($args['value']) ? $args['value'] : array();

						if ( $is_multi_select_not ) {
							$checked = in_array($key, array_keys($tmp_value)) ? '' : 'checked';
						} else {
							$checked = in_array($key, array_keys($tmp_value)) ? 'checked' : '';
						}

						$label = str_replace(',', '', $label);   			?>

						<li class="input_group <?php echo esc_html( $args['input_group_class'] )?>" id="<?php echo $id; ?>_group">
							<?php
							if ( $is_multi_select_not ) { ?>
								<input type="hidden" value="<?php echo esc_attr( $key . '[[-HIDDEN-]]' . $label ); ?>" name="<?php echo esc_attr( $args['name'] ) . '_' . $ix; ?>">
							<?php }	?>

							<label class="<?php echo esc_html( $args['label_class'] )?>" for="<?php echo $id.$ix; ?>">
								<?php echo esc_html( $args['label'] ); ?>
							</label>

							<div class="input_container <?php echo esc_html( $args['input_class'] )?>" id="">
								<input type="checkbox"
								       name="<?php echo $id. '_' . $ix; ?>"
								       id="<?php echo $id.$ix; ?>"
								       value="<?php echo esc_attr( $key . '[[-,-]]' . $label ); ?>"
									<?php echo $checked; ?>
								/>
							</div>
						</li>   	<?php

						$ix++;
					} //foreach   	?>

				</ul>
			</div>
		</li>   <?php
	}

	/**
	 * Output submit button
	 *
	 * @param string $button_label
	 * @param string $action
	 * @param string $class
	 * @param string $html - any additional hidden fields
	 */
	public function submit_button( $button_label='Save', $action='epkb_save_settings', $class='save_settings', $html='' ) {   ?>
		<div class="submit <?php echo $class; ?>">
			<input type="hidden" id="_wpnonce_<?php echo $action; ?>" name="_wpnonce_<?php echo $action; ?>" value="<?php echo wp_create_nonce( "_wpnonce_$action" ); ?>"/>
			<input type="hidden" name="action" value="<?php echo $action; ?>"/>
			<input type="submit" id="<?php echo $action; ?>" class="primary-btn" value="<?php echo $button_label; ?>" />  <?php
			echo $html;  ?>
		</div>  <?php
	}

	/**
	 * Renders licence key field
	 *
	 * @param array $args Arguments for the licence key field
	 * @param $prefix - add-on plugin prefix
	 * @return string Text field
	 */
	public function license_key( $args = array(), $prefix ) {

		$args = $this->add_defaults( $args );

		$id =  esc_attr( $args['name'] );
		$autocomplete = ( $args['autocomplete'] ? 'on' : 'off' );
		$readonly = $args['readonly'] ? ' readonly' : 'unknown';

		$status = isset($args['data']['status']) ? $args['data']['status'] : '';
		$btn_label = $status == 'valid' ? __( 'Deactivate', 'echo-knowledge-base' ) : __( 'Activate', 'echo-knowledge-base' );
		$license_key = $args['value'];
		$hide_btn_class =  empty($license_key) ? 'style="display:none;"' : '';

		if ( empty($license_key) ) {
			$status_type = 'status_info';
			$status_msg = __( 'No license', 'echo-knowledge-base' );
		} else if ( $status == 'valid' ) {
			$status_type = 'status_success';
			$status_msg = __( 'License Saved and Active', 'echo-knowledge-base' );
		} else {
			$status_type = 'status_warning';
			$status_msg = __( 'License Inactive', 'echo-knowledge-base' );
		}

		$data = '';
		if ( ! empty( $args['data'] ) ) {
			foreach ( $args['data'] as $key => $value ) {
				$data .= 'data-' . $key . '="' . $value . '" ';
			}
		}
		$info = '';
		if ( ! empty( $args['info'] ) ) {
			$info = "<span class='info-icon'><p class='hidden'>" . esc_html($args['info']) . "</p></span>";
		}

		$output =
			'<li class="input_group license_key_group' . esc_html( $args['input_group_class'] ) . '" id="' . $id . '_group" >';

		$output .=
			'<div class="epkb_license_label col-3">
				<label class="' . esc_html( $args['label_class'] ) . '" for="' . $id . '">' . esc_html( $args['label'] ) . '</label>
			</div>';//epkb_license_label

		$output .=
			'<div class="' . esc_html( $args['input_class'] ) . ' col-6">
				<input type="text" name="' . $id . '" id="' . $id . '" autocomplete="' . $autocomplete . '" value="' . esc_attr( $license_key ) .'" placeholder="' . esc_attr( $args['placeholder'] ) . '" ' . $data . $readonly . 'maxlength="' . $args['max'] . '"/>
			</div>';//epkb_license_key

		$output .=
			'<div class="epkb_license_action col-2">
				<input id="' . $id . '_activate_btn" class="primary-btn" ' . $hide_btn_class . ' type="button" name="' . $id . '_activate_btn" value="' . $btn_label . '"/>
				<input type="hidden" id="_wpnonce_' . $prefix . '_license_key" name="_wpnonce_' . $prefix . '_license_key" value="' . wp_create_nonce( "_wpnonce_" . $prefix . "_license_key" ) . '"/>
			</div>';//epkb_license_action

		$output .=
			'<div class="epkb_license_status">
				<div class="add_on_status">
						<p id="' . $id . '_status" class="' . esc_attr( $status_type ) . '">' . esc_html( $status_msg ) . '</p>' .
				'</div>
			</div>';//epkb_license_status

		$output .=
			'<div class="epkb_license_info">
				'.$info.'
			</div>'
		;

		$output .= '</li>';
		
		return $output;
	}
}
