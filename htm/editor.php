<html>
    <head>
         <script language="JavaScript" type="text/javascript" src="rte/richtext_compressed.js"></script>
    </head>
    <body>
        <form name="RTEDemo" action="demo.htm" method="post" onsubmit="return submitForm();">
           
            <script language="JavaScript" type="text/javascript">
                
                function submitForm() {
                        updateRTE('rte1');
                        return false;
                }
                initRTE("rte/images/", "", "rte/");
            </script>
            <noscript><p><b>Javascript must be enabled to use this form.</b></p></noscript>
            
            <script language="JavaScript" type="text/javascript">
                writeRichText('rte1', '', 400, 200, true, false);
            </script>
            <input type="submit" name="submit" value="Submit">
            </form>
            
            </body>

    </body>
</html>