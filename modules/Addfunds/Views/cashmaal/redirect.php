<center><h1>Please do not refresh this page...</h1></center>

<center>
<div style="margin-top: 50px;height: 200px;width: 350px;" class="loading_image">
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin:auto;background:#fff;display:block;" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
  <circle cx="50" cy="50" r="32" stroke-width="8" stroke="#e15b64" stroke-dasharray="50.26548245743669 50.26548245743669" fill="none" stroke-linecap="round">
    <animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 50;360 50 50"></animateTransform>
  </circle>
  <circle cx="50" cy="50" r="23" stroke-width="8" stroke="#f8b26a" stroke-dasharray="36.12831551628262 36.12831551628262" stroke-dashoffset="36.12831551628262" fill="none" stroke-linecap="round">
    <animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 50;-360 50 50"></animateTransform>
  </circle>
</svg>
</div>
</center>

<form action="https://cdn.omise.co/pay.html/" method="POST" name="f1">
    
    <?php
        foreach($paramList as $name => $value) {
          echo '<input type="hidden" name="' . $name .'" value="' . $value . '">';
        }
    ?>

    <script type="text/javascript">
        document.f1.submit();
    </script>
</form>