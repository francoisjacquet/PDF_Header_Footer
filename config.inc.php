<?php
/**
 * Plugin configuration interface
 *
 * @package PDF Header Footer
 */

require_once 'ProgramFunctions/MarkDownHTML.fnc.php';

// Check the script is called by the right program & plugin is activated.
if ( $_REQUEST['modname'] !== 'School_Setup/Configuration.php'
	|| ! $RosarioPlugins['PDF_Header_Footer']
	|| $_REQUEST['modfunc'] !== 'config' )
{
	$error[] = _( 'You\'re not allowed to use this program!' );

	echo ErrorMessage( $error, 'fatal' );
}

// Note: no need to call ProgramTitle() here!

if ( $_REQUEST['save'] === 'true' )
{
	if ( $_REQUEST['values']['PROGRAM_CONFIG']
		&& $_POST['values']
		&& AllowEdit() )
	{
		// Update the PROGRAM_CONFIG table.
		$sql = '';

		if ( isset( $_REQUEST['values']['PROGRAM_CONFIG'] )
			&& is_array( $_REQUEST['values']['PROGRAM_CONFIG'] ) )
		{
			foreach ( (array) $_REQUEST['values']['PROGRAM_CONFIG'] as $column => $value )
			{
				if ( $column === 'PDF_HEADER_FOOTER_HEADER'
					|| $column === 'PDF_HEADER_FOOTER_FOOTER' )
				{
					// Sanitize HTML from TinyMCE & eventually upload images.
					$value = SanitizeHTML( $_POST['values']['PROGRAM_CONFIG'][ $column ] );
				}

				if ( $column === 'PDF_HEADER_FOOTER_MARGIN_TOP'
					|| $column === 'PDF_HEADER_FOOTER_MARGIN_BOTTOM' )
				{
					// Sanitize margin (positive int).
					$value = preg_replace( '/[^0-9]/', '', $value );
				}

				$sql .= "UPDATE PROGRAM_CONFIG
					SET VALUE='" . $value . "'
					WHERE TITLE='" . $column . "'
					AND SCHOOL_ID='" . UserSchool() . "'
					AND SYEAR='" . UserSyear() . "';";
			}
		}

		if ( $sql != '' )
		{
			DBQuery( $sql );

			$note[] = button( 'check' ) . '&nbsp;' . _( 'The plugin configuration has been modified.' );
		}

		unset( $_ROSARIO['ProgramConfig'] ); // Update ProgramConfig var.
	}

	if ( function_exists( 'RedirectURL' ) )
	{
		// Unset save & values & redirect URL.
		RedirectURL( 'save', 'values' );
	}
	else
	{
		// @deprecated.
		unset( $_REQUEST['save'] );
		unset( $_SESSION['_REQUEST_vars']['values'] );
		unset( $_SESSION['_REQUEST_vars']['save'] );
	}
}


if ( empty( $_REQUEST['save'] )
	&& empty( $_REQUEST['remove'] ) )
{
	echo '<form action="Modules.php?modname=' . $_REQUEST['modname'] . '&tab=plugins&modfunc=config&plugin=PDF_Header_Footer&save=true" method="POST">';

	DrawHeader( '', SubmitButton( _( 'Save' ) ) );

	echo ErrorMessage( $note, 'note' );

	echo ErrorMessage( $error, 'error' );

	echo '<br />';

	$school_title = '';

	// If more than 1 school, add its title to table title.
	if ( SchoolInfo( 'SCHOOLS_NB' ) > 1 )
	{
		$school_title = SchoolInfo( 'SHORT_NAME' );

		if ( ! $school_title )
		{
			// No short name, get full title.
			$school_title = SchoolInfo( 'TITLE' );
		}

		$school_title = '(' . $school_title . ')';
	}

	PopTable(
		'header',
		sprintf(
			dgettext( 'PDF_Header_Footer', 'PDF Header Footer %s' ),
			$school_title
		),
		'style="width:100%;"'
	);

	$pdf_header_footer = ProgramConfig( 'pdf_header_footer' );

	// Header.
	echo '<table class="width-100p"><tr><td>' . TinyMCEInput(
		$pdf_header_footer['PDF_HEADER_FOOTER_HEADER'][1]['VALUE'],
		'values[PROGRAM_CONFIG][PDF_HEADER_FOOTER_HEADER]',
		dgettext( 'PDF_Header_Footer', 'Header' ),
		' style="height:76px"'
	) . '</td></tr>';

	// Margin top.
	echo '<tr><td>' . TextInput(
		$pdf_header_footer['PDF_HEADER_FOOTER_MARGIN_TOP'][1]['VALUE'],
		'values[PROGRAM_CONFIG][PDF_HEADER_FOOTER_MARGIN_TOP]',
		dgettext( 'PDF_Header_Footer', 'Top Margin (mm)' ),
		' type="number" min="0" required placeholder="20"'
	) . '<hr/></td></tr>';

	// Footer.
	echo '<tr><td>' . TinyMCEInput(
		$pdf_header_footer['PDF_HEADER_FOOTER_FOOTER'][1]['VALUE'],
		'values[PROGRAM_CONFIG][PDF_HEADER_FOOTER_FOOTER]',
		dgettext( 'PDF_Header_Footer', 'Footer' ),
		' style="height:76px"'
	) . '</td></tr>';

	// Margin bottom.
	echo '<tr><td>' . TextInput(
		$pdf_header_footer['PDF_HEADER_FOOTER_MARGIN_BOTTOM'][1]['VALUE'],
		'values[PROGRAM_CONFIG][PDF_HEADER_FOOTER_MARGIN_BOTTOM]',
		dgettext( 'PDF_Header_Footer', 'Bottom Margin (mm)' ),
		' type="number" min="0" required placeholder="18"'
	) . '<hr/></td></tr>';

	// Exclude Print.
	echo '<tr><td>' . CheckboxInput(
		$pdf_header_footer['PDF_HEADER_FOOTER_EXCLUDE_PRINT'][1]['VALUE'],
		'values[PROGRAM_CONFIG][PDF_HEADER_FOOTER_EXCLUDE_PRINT]',
		dgettext( 'PDF_Header_Footer', 'Exclude PDF generated using the "Print" button' ),
		'',
		false,
		button( 'check' ),
		button( 'x' )
	) .	'</td></tr></table>';

	PopTable( 'footer' );

	echo '<br /><div class="center">' . SubmitButton( _( 'Save' ) ) . '</div></form>';
}
