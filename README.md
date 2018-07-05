GitLab
------

RosarioSIS code is now hosted at [**GitLab**](https://gitlab.com/francoisjacquet/PDF_Header_Footer)!

# PDF Header Footer Plugin

![screenshot](https://raw.githubusercontent.com/francoisjacquet/PDF_Header_Footer/master/screenshot.png)

https://github.com/francoisjacquet/PDF_Header_Footer/

Version 1.1 - July, 2017

Author François Jacquet

Sponsored by Aquarelle private school

License Gnu GPL v2

## Description

This RosarioSIS plugin lets you define and add a custom, rich text header and / or footer to PDF documents generated by RosarioSIS.
Pages generated using the "Print" button can be excluded.

Translated in [French](https://www.rosariosis.org/fr/pdf-header-footer-plugin/) & [Spanish](https://www.rosariosis.org/es/pdf-header-footer-plugin/).

## Content

Plugin Configuration

- Add custom header.
- Add custom footer.
- Adjust bottom & top margins.
- Exclude PDF generated using the "Print" button (limit footer and header to school documents only).

## Install

Copy the `PDF_Header_Footer/` folder (if named `PDF_Header_Footer-master`, rename it) and its content inside the `plugins/` folder of RosarioSIS.

Go to _School Setup > School Configuration > Plugins_ and click "Activate".

Requires RosarioSIS 3.4+ and [wkhtmltopdf](https://wkhtmltopdf.org/)
