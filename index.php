<?php
header('Content-Type: text/html; charset=UTF-8');

require 'config.php';
require 'vendor/autoload.php';
require 'tracker.php';
if (isset($_GET['item'])) {
    $track = new Tracker($_GET['item']);
}
?>
<?php if ($track): ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta property="fb:app_id" content="<?php echo FB_APPID;?>">
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="article">
    <meta property="og:title" content="<?php echo $track->item->title;?>" />
    <meta property="og:description" content="<?php echo $track->item->description;?>">
    <meta property="og:url" content="<?php echo URL;?>">
    <meta property="og:image" content="<?php echo $track->image();?>">
    <meta property="og:site_name" content="<?php echo ADNETWORK;?>">
    <meta property="article:section" content="Pictures" />
    
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?php echo $track->item->title;?>">
    <meta name="twitter:description" content="<?php echo $track->item->description;?>">
    <meta name="twitter:url" content="<?php echo URL;?>">
</head>

<body>
<script type="text/javascript">
redirect();
function loadDoc() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            redirect();
        }
    };
    xhttp.open("GET", "hit.php?item=<?php echo $_GET['item'];?>?", true);
    xhttp.send();
}
function redirect () {
    window.location.href = '<?php echo $track->redirectUrl();?>';
}
</script>
</body>

</html>
<?php else: ?>
    <p>Invalid Request Contact <a href="http://cloudstuff.tech">Admin</a></p>
<?php endif; ?>