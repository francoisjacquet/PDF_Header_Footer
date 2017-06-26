<?php
/**
 * Functions
 *
 * @package PDF Header Footer
 */

// Register plugin functions to be hooked.
add_action( 'functions/PDF.php|pdf_start', 'PDFHeaderFooterTriggered' );

// Triggered function.
function PDFHeaderFooterTriggered( $hook_tag, $arg1 = '' )
{
	// PDF options from PDFStart().
	global $wkhtmltopdfPath,
		$pdf_options;

	if ( empty( $wkhtmltopdfPath ) )
	{
		// Wkhtmltopdf path NOT set.
		return false;
	}

	$pdf_hf = ProgramConfig( 'pdf_header_footer' );

	// Check we have a header and / or footer defined first.
	$header = $footer = '';

	if ( isset( $pdf_hf['PDF_HEADER_FOOTER_HEADER'][1]['VALUE'] )
		&& $pdf_hf['PDF_HEADER_FOOTER_HEADER'][1]['VALUE']
		&& $pdf_hf['PDF_HEADER_FOOTER_HEADER'][1]['VALUE'] !== '<p></p>' )
	{
		$header = $pdf_hf['PDF_HEADER_FOOTER_HEADER'][1]['VALUE'];
	}

	if ( isset( $pdf_hf['PDF_HEADER_FOOTER_FOOTER'][1]['VALUE'] )
		&& $pdf_hf['PDF_HEADER_FOOTER_FOOTER'][1]['VALUE']
		&& $pdf_hf['PDF_HEADER_FOOTER_FOOTER'][1]['VALUE'] !== '<p></p>' )
	{
		$footer = $pdf_hf['PDF_HEADER_FOOTER_FOOTER'][1]['VALUE'];
	}

	if ( ! $header
		&& ! $footer )
	{
		return false;
	}

	$margin_top = $margin_bottom = 0;

	// Get margins.
	if ( isset( $pdf_hf['PDF_HEADER_FOOTER_MARGIN_TOP'][1]['VALUE'] )
		&& (int) $pdf_hf['PDF_HEADER_FOOTER_MARGIN_TOP'][1]['VALUE'] > 0 )
	{
		$margin_top = (int) $pdf_hf['PDF_HEADER_FOOTER_MARGIN_TOP'][1]['VALUE'];
	}

	if ( isset( $pdf_hf['PDF_HEADER_FOOTER_MARGIN_BOTTOM'][1]['VALUE'] )
		&& (int) $pdf_hf['PDF_HEADER_FOOTER_MARGIN_BOTTOM'][1]['VALUE'] > 0 )
	{
		$margin_bottom = (int) $pdf_hf['PDF_HEADER_FOOTER_MARGIN_BOTTOM'][1]['VALUE'];
	}


	// "Print" PDF excluded?
	$is_print_pdf = isset( $_REQUEST['bottomfunc'] ) && $_REQUEST['bottomfunc'] === 'print';

	$print_pdf_excluded = false;

	if ( isset( $pdf_hf['PDF_HEADER_FOOTER_EXCLUDE_PRINT'][1]['VALUE'] )
		&& $pdf_hf['PDF_HEADER_FOOTER_EXCLUDE_PRINT'][1]['VALUE'] === 'Y' )
	{
		$print_pdf_excluded = true;
	}

	if ( $is_print_pdf
		&& $print_pdf_excluded )
	{
		return false;
	}

	$html = array();

	/**
	 * Footer & header substitutions.
	 * Use an empty HTML tag with one of the following CSS classes:
	 *
	 * @link https://wkhtmltopdf.org/usage/wkhtmltopdf.txt
	 *
	 * @example Page <span class="page"></span> of <span class="topage"></span>x
	 *
	 * [page]       Replaced by the number of the pages currently being printed
	 * [frompage]   Replaced by the number of the first page to be printed
	 * [topage]     Replaced by the number of the last page to be printed
	 * [webpage]    Replaced by the URL of the page being printed
	 * [section]    Replaced by the name of the current section
	 * [subsection] Replaced by the name of the current subsection
	 * [date]       Replaced by the current date in system local format
	 * [isodate]    Replaced by the current date in ISO 8601 extended format
	 * [time]       Replaced by the current time in system local format
	 * [title]      Replaced by the title of the of the current page object
	 * [doctitle]   Replaced by the title of the output document
	 * [sitepage]   Replaced by the number of the page in the current site being converted
	 * [sitepages]  Replaced by the number of pages in the current site being converted
	 */


	// OK, lets add our custom header and / or footer to the PDF options.
	if ( $header )
	{
		$pdf_options['header_html'] = $header;
	}

	if ( $footer )
	{
		$pdf_options['footer_html'] = $footer;
	}

	// Add margins in millimeters.
	$pdf_options['margins']['top'] = $margin_top . 'mm';
	$pdf_options['margins']['bottom'] = $margin_bottom . 'mm';

	return true;
}
