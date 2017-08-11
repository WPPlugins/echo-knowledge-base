<?php

/**
 * Elements of Config sidebar UI
 *
 * @copyright   Copyright (C) 2017, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPKB_KB_Config_Elements {

	public function option_group( $feature_specs, $args = array() ) {
        $defaults = array(
            'info' => '',
	        'option-heading' => '',
            'class' => ' '
        );
		$args = array_merge( $defaults, $args );

		// there might be multiple classes
		$classes = explode(' ', $args['class']);
		$class_string = '';
		foreach( $classes as $class ) {
			$class_string .= $class . '-content ';
		}		?>

        <div class="config-option-group <?php echo $class_string; ?>">	        <?php
	        
            if ( $args['option-heading'] ) {    ?>
                <div class="config-option-heading">
                    <h4><?php echo $args['option-heading']; ?></h4>
                    <span class="ep_icon_info option-info-icon"></span>
                </div>            <?php
            } else {     ?>
                <div class="config-option-info">
                    <span class="ep_icon_info option-info-icon"></span>
                </div>            <?php
            }           ?>

	            <div class="option-info-content hidden">
		            <h5 class="option-info-title">Help</h5>                    <?php

                    if ( $feature_specs ) {
	                    if ( is_array( $args['info']) ) {
		                    foreach( $args['info'] as $item ) {
			                    if ( empty($feature_specs[$item]) ) {
				                    continue;
			                    }

			                    echo '<h6 style="padding-top:20px;">' . $feature_specs[$item]['label'] . '</h6>';
			                    echo '<p>' . $feature_specs[$item]['info'] . '</p>';
		                    }
	                    } else {
		                    echo '<p>' .$args['info']. '</p>';
	                    }
                    }		            ?>

                </div>            <?php

            foreach ( $args['inputs'] as $input ) {
                echo $input;
            }   ?>

        </div><!-- config-option-group -->        <?php
	}

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
			'radio_class'       => '',
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
		}
		ob_start();		?>

		<div class="config-input-group <?php echo esc_html( $args['input_group_class'] )?>" id="<?php echo $id ?>_group" >

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
			</div>

		</div>		<?php

		return ob_get_clean();
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

		$id =  esc_attr( $args['name'] );

		ob_start();		?>

		<div class="config-input-group <?php echo esc_html( $args['input_group_class'] )?>" id="<?php echo $id ?>_group" >

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

		</div>		<?php

		return ob_get_clean();
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
		$checked = checked( "on", $args['value'], false );

		ob_start();		?>

		<div class="config-input-group <?php echo esc_html( $args['input_group_class'] )?>" id="<?php echo $id; ?>_group">

			<label class="<?php echo esc_html( $args['label_class'] )?>" for="<?php echo $id; ?>">
				<?php echo esc_html( $args['label'] ); ?>
			</label>

			<div class="input_container <?php echo esc_html( $args['input_class'] )?>" id="">
				<input type="checkbox"
				       name="<?php echo $id ?>"
				       id="<?php echo $id ?>"
				       value="on"
				       <?php echo $checked; ?> />
			</div>

		</div>		<?php

		return ob_get_clean();
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
		$checked = checked( 1, $args['value'], false );

		ob_start();		?>

		<div class="config-input-group <?php echo esc_html( $args['input_group_class'] )?>" id="<?php echo $id ?>_group">

			<label class="<?php echo esc_html( $args['label_class'] )?>" for="<?php echo $id ?>">
				<?php echo esc_html( $args['label'] )?>
			</label>

			<div class="input_container <?php echo esc_html( $args['input_class'] )?>" id="">
				<input type="radio"
				       name="<?php echo $id ?>"
				       id="<?php echo $id ?>"
				       value="<?php echo esc_attr( $args['value'] ); ?>"
					<?php echo $checked; ?> />
			</div>

		</div>		<?php

		return ob_get_clean();
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

		$id =  esc_attr( $args['name'] );
		ob_start();		?>

		<div class="config-input-group <?php echo esc_html( $args['input_group_class'] )?>" id="<?php echo $id ?>_group">
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
			</div>
		</div>		<?php

		return ob_get_clean();
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
		$ix = 0;
        ob_start();        ?>

		<div class="config-input-group <?php echo esc_html( $args['input_group_class'] )?>" id="<?php echo $id ?>_group" >

			<span class="main_label <?php echo esc_html( $args['main_label_class'] )?>">
				<?php echo esc_html( $args['label'] ); ?>
			</span>

			<div class="radio-buttons-horizontal <?php echo esc_html( $args['input_class'] )?>" id="<?php echo $id ?>">					<?php

				foreach( $args['options'] as $key => $label ) {
					$checked = checked( $key, $args['current'], false );					?>

					<div class="<?php echo esc_html( $args['radio_class'] )?>">						<?php

						$checked_class ='';
						if ($args['current'] == $key ) {
							$checked_class ='checked-radio';
						}       ?>

						<div class="input_container <?php echo $checked_class; ?>">
							<input type="radio"
							       name="<?php echo esc_attr( $args['name'] ); ?>"
							       id="<?php echo $id.$ix; ?>"
							       value="<?php echo esc_attr( $key ); ?>"  <?php
									echo $checked;			?>
							/>
						</div>

						<label class="<?php echo esc_html( $args['label_class'] )?>" for="<?php echo $id.$ix ?>">
							<?php echo esc_html( $label )?>
						</label>
					</div>						<?php

					$ix++;
				} //foreach    	?>

			</div>

		</div>		<?php

		return ob_get_clean();
	}

	/**
	 * Renders several HTML radio buttons in a column
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function radio_buttons_vertical( $args = array() ) {

		$defaults = array(
			'id'                => 'radio',
			'name'              => 'radio-buttons',
			'main_label_class'  => '',
			'radio_class'       => '',
		);
		$args = $this->add_defaults( $args, $defaults );
		$id =  esc_attr( $args['name'] );
		$ix = 0;
		
		ob_start();		?>

		<div class="config-input-group <?php echo esc_html( $args['input_group_class'] )?>" id="<?php echo $id ?>_group" >            <?php
		
        if( ! empty($args['label']) ) {     ?>
			<span class="main_label <?php echo esc_html( $args['main_label_class'] )?>">
				<?php echo esc_html( $args['label'] ); ?>
			</span>            <?php
        }                       ?>

			<div class="radio-buttons-vertical <?php echo esc_html( $args['input_class'] )?>" id="<?php echo $id ?>">
                <ul>	                <?php

	                foreach( $args['options'] as $key => $label ) {
		                $checked = checked( $key, $args['current'], false );		                ?>

                        <li class="<?php echo esc_html( $args['radio_class'] )?>">			                <?php

			                $checked_class ='';
			                if ($args['current'] == $key ) {
				                $checked_class ='checked-radio';
			                } ?>

                            <div class="input_container config-col-1 <?php echo $checked_class; ?>">
                                <input type="radio"
                                       name="<?php echo esc_attr( $args['name'] ); ?>"
                                       id="<?php echo $id . $ix; ?>"
                                       value="<?php echo esc_attr( $key ); ?>"					                <?php
                                       echo $checked; ?>
                                />
                            </div>
                            <label class="<?php echo esc_html( $args['label_class'] )?> config-col-10" for="<?php echo $id.$ix ?>">
				                <?php echo esc_html( $label )?>
                            </label>
                        </li>		                <?php

		                $ix++;
	                } //foreach	                ?>

                </ul>

			</div>

		</div>        <?php

		return ob_get_clean();
	}

	public function multiple_number_inputs ($common = array() , $inputs = array() ){
		ob_start();
		$defaults = array(
			'name'         => 'text',
			'class'        => '',
		);
		$common = $this->add_common_defaults( $common, $defaults );

		?>
        <div class="config-input-group epkb-multiple-number-group<?php echo esc_html( $common['input_group_class'] )?>" id="<?php echo $common['id']; ?>_group" >
            <span class="main_label <?php echo esc_html( $common['main_label_class'] )?>"><?php echo esc_html( $common['label'] ); ?></span>
            <div class="number-inputs-container">
                <?php
                foreach( $inputs as $input ){
                    echo '<div class="number-input">';
                        echo '<input type="number" name="'.esc_attr( $input['name'] ).'" id="'.esc_attr( $input['name'] ).'" value="'.esc_attr( $input['value'] ).'">';
                        echo '<label for="'.esc_attr( $input['name'] ).'">'.esc_html( $input['label'] ).'</label>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
		<?php return ob_get_clean();
	}

	/**
	 * Single Inputs for text_fields_horizontal function
	 *
	 * @param array $args
	 * @return string
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
		}

		ob_start();		?>

		<div class="<?php echo esc_html( $args['text_class'] )?>">

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

		</div>	<?php

		return ob_get_clean();
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
		$args2 = $this->add_defaults( $args2, $defaults );

		ob_start();		?>

		<div class="config-input-group <?php echo esc_html( $common['input_group_class'] )?>" id="<?php echo $common['id']; ?>_group" >
			<span class="main_label <?php echo esc_html( $common['main_label_class'] )?>"><?php echo esc_html( $common['label'] ); ?></span>
			<div class="text-fields-horizontal <?php echo esc_html( $common['input_class'] )?>">				  <?php

					echo $this->horizontal_text_input($args1);
					echo $this->horizontal_text_input($args2);			  ?>

			</div>
		</div>		<?php

		return ob_get_clean();
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
		$common = $this->add_common_defaults( $common );
		ob_start();		?>

		<div class="config-input-group <?php echo esc_html( $common['input_group_class'] )?>" id="<?php echo $common['id']; ?>_group" >
			<span class="main_label <?php echo esc_html( $common['main_label_class'] )?>"><?php echo esc_html( $common['label'] ); ?></span>
			<div class="text-select-fields-horizontal <?php echo esc_html( $common['input_class'] )?>">
				<ul>  <?php

					echo $this->text($args1);
					echo $this->dropdown($args2);						?>

				</ul>
			</div>
		</div>		<?php

		return ob_get_clean();
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
		$ix = 0;

		ob_start();		?>

		<div class="config-input-group <?php echo esc_html( $args['input_group_class'] )?>" id="<?php echo $id ?>_group" >

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

						<div class="config-input-group <?php echo esc_html( $args['input_group_class'] )?>" id="<?php echo $id; ?>_group">
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
						</div>   	<?php

						$ix++;
					} //foreach   	?>

				</ul>
			</div>
		</div>   <?php

		return ob_get_clean();
	}

	/**
	 * Output submit button
	 *
	 * @param array $args
	 */
	public function submit_button( $args = array() ) {
		$defaults = array(
			'label'        => 'Save',
			'id'           => '',
			'action'       => 'epkb_save_settings',
			'input_class'  => '',
			'main_class'   => '',
		);
		$args = $this->add_defaults( $args, $defaults );		?>

		<div class="config-input-group">
			<div class="submit <?php echo esc_html( $args['main_class'] )?>">
				<input type="hidden" id="_wpnonce_<?php echo esc_html( $args['action'] )?>" name="_wpnonce_<?php echo esc_html( $args['action'] )?>" value="<?php echo wp_create_nonce( "_wpnonce_".esc_html( $args['action'] ) ); ?>"/>
				<input type="hidden" name="action" value="<?php echo esc_html( $args['action'] )?>"/>
				<input type="submit" id="<?php echo esc_html( $args['id'] )?>" class="<?php echo esc_html( $args['input_class'] )?>" value="<?php echo esc_html( $args['label'] )?>" />
			</div>
		</div>		<?php
	}
}
