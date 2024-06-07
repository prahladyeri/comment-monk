<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" href="#aboutApp" data-toggle='tab'>About App</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#versionHistory" data-toggle='tab'>Version History</a>
  </li>
</ul>

<div class='tab-content'>
<div role='tabpanel' id='aboutApp' class='mt-4 tab-pane active' >
BUILD VERSION: <?= VERSION ?><br>
Timezone: <?= date_default_timezone_get(); ?><br><br>
<b><?=APP_NAME ?> is built on top of following open source technologies:</b>
<ul>
<li> <a href="https://github.com/twbs/bootstrap">Bootstrap version 4.6.2</a></li>
<li> <a href="https://github.com/jquery/jquery">jquery version 3.7.1</a></li>
<li> <a href="http://fontawesome.io/icons/">fontawesome version 4.7.0</a></li>
<li> <a href="https://fonts.google.com/about">Google Fonts</a></li>

</ul>
PHP VERSION: <?= phpversion(); ?><br>
SQLITE VERSION: <?=$sqlite_ver?><br>
</div>

<div role='tabpanel' id='versionHistory' class='mt-4 tab-pane'>
<pre><?= file_get_contents("CHANGE.log"); ?></pre>
</div>
</div>