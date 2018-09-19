<?php
$info = db_getTextBlock('footer');
echo '<div style="text-align: center; margin-top:50px; padding-top:20px; color: #ababab !important;">'.$info.'</div>';
?>

<!-- Yandex.Metrika counter -->

        <script type="text/javascript">
            (function (d, w, c) {
                (w[c] = w[c] || []).push(function() {
                    try {
                        w.yaCounter23700652 = new Ya.Metrika({id:23700652,
                            accurateTrackBounce:true});
                    } catch(e) { }
                });

                var n = d.getElementsByTagName("script")[0],
                    s = d.createElement("script"),
                    f = function () { n.parentNode.insertBefore(s, n); };
                s.type = "text/javascript";
                s.async = true;
                //s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

                if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", f, false);
                } else { f(); }
            })(document, window, "yandex_metrika_callbacks");
        </script>
        <!--<noscript><div><img src="//mc.yandex.ru/watch/23700652" style="position:absolute; left:-9999px;" alt="" /></div></noscript>-->
        <!-- /Yandex.Metrika counter -->
        
        <!-- BEGIN JIVOSITE CODE {literal} -->
        <!--
        <script type='text/javascript'>            
            (function(){ 
                var widget_id = 'LsvANjk4FT';
                var d=document;
                var w=window;
                function l(){
                    var s = document.createElement('script'); 
                    s.type = 'text/javascript'; 
                    s.async = true; 
                    s.src = '//code.jivosite.com/script/widget/'+widget_id; 
                    var ss = document.getElementsByTagName('script')[0]; 
                    ss.parentNode.insertBefore(s, ss);
                }
                
                if(d.readyState=='complete'){
                    l();
                }
                else{
                    if(w.attachEvent){
                        w.attachEvent('onload',l);
                    }
                    else{
                        w.addEventListener('load',l,false);
                    }
                }
            })();
        </script>
        -->
        <!-- {/literal} END JIVOSITE CODE -->
    </body>
</html>
