/**
 * Install SQL
 * - Add program config options if any (to every schools)
 *
 * @package PDF Header Footer
 */

/**
 * program_config Table
 *
 * syear: school year (school may have various years in DB)
 * school_id: may exists various schools in DB
 * program: convention is plugin name, for ex.: 'pdf_header_footer'
 * title: for ex.: 'PDF_HEADER_FOOTER_[your_program_config]'
 * value: string
 */
--
-- Data for Name: program_config; Type: TABLE DATA; Schema: public; Owner: rosariosis
--

INSERT INTO program_config (syear, school_id, program, title, value)
SELECT sch.syear, sch.id, 'pdf_header_footer', 'PDF_HEADER_FOOTER_HEADER', ''
FROM schools sch
WHERE NOT EXISTS (SELECT title
    FROM program_config
    WHERE title='PDF_HEADER_FOOTER_HEADER');

INSERT INTO program_config (syear, school_id, program, title, value)
SELECT sch.syear, sch.id, 'pdf_header_footer', 'PDF_HEADER_FOOTER_FOOTER', ''
FROM schools sch
WHERE NOT EXISTS (SELECT title
    FROM program_config
    WHERE title='PDF_HEADER_FOOTER_FOOTER');

INSERT INTO program_config (syear, school_id, program, title, value)
SELECT sch.syear, sch.id, 'pdf_header_footer', 'PDF_HEADER_FOOTER_MARGIN_TOP', '20'
FROM schools sch
WHERE NOT EXISTS (SELECT title
    FROM program_config
    WHERE title='PDF_HEADER_FOOTER_MARGIN_TOP');

INSERT INTO program_config (syear, school_id, program, title, value)
SELECT sch.syear, sch.id, 'pdf_header_footer', 'PDF_HEADER_FOOTER_MARGIN_BOTTOM', '18'
FROM schools sch
WHERE NOT EXISTS (SELECT title
    FROM program_config
    WHERE title='PDF_HEADER_FOOTER_MARGIN_BOTTOM');

INSERT INTO program_config (syear, school_id, program, title, value)
SELECT sch.syear, sch.id, 'pdf_header_footer', 'PDF_HEADER_FOOTER_EXCLUDE_PRINT', NULL
FROM schools sch
WHERE NOT EXISTS (SELECT title
    FROM program_config
    WHERE title='PDF_HEADER_FOOTER_EXCLUDE_PRINT');
