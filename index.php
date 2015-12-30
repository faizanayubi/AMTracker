<?php
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
    <meta property="fb:app_id" content="<?php echo FB_APPID;?>">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="robots" content="noodp">
    <meta property="og:title" content="<?php echo $track->item->title;?>" />
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="article">
    <meta property="og:description" content="<?php echo $track->item->description;?>">
    <meta property="og:image" content="<?php echo $track->image();?>">
    <meta name="og:image-new" content="<?php echo $track->image();?>">
    <meta property="og:site_name" content="<?php echo ADNETWORK;?>">
    <meta property="og:url" content='<?php echo URL;?>'>
    <meta name="generator" content="SwiftMVC 1.1.1">
</head>

<body>
<script type="text/javascript">
    window.location.href = '<?php echo $track->item->url;?>?utm_source=<?php echo $track->item->user_id;?>&utm_medium=<?php echo ADNETWORK;?>&utm_campaign=<?php echo $track->item->title;?>';
</script>
</body>

</html>
<?php else: ?>
    <p>Invalid Request Contact <a href="http://cloudstuff.tech">Admin</a></p>
<?php endif; ?>