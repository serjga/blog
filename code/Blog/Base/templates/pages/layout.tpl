<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- HEAD CONTENT -->
        {include file={baseRegistry->getTemplateFile template='blocks/block__head'}}
    </head>
    <body class="body-wrapper">

        <!-- HEADER CONTENT -->
        {$block__header}

        <!-- BODY CONTENT -->
        {$block_page_body_content}

        <!-- SECTION FOOTER -->
        {$block__footer_section}

        <!-- BOTTOM FOOTER -->
        {include file={baseRegistry->getTemplateFile template='blocks/block__footer_bottom'}}

        <!-- ALERTS -->
        {$block__alerts}

        <!-- JAVASCRIPTS -->
        {include file={baseRegistry->getTemplateFile template='blocks/block__scripts'}}

    </body>
</html>
