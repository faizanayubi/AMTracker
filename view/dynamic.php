<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta property="fb:app_id" content="1644257145856201">
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="article">
    <meta property="og:title" content="<?php echo $track->link->title;?>" />
    <meta property="og:description" content="<?php echo $track->link->description;?>">
    <meta property="og:url" content="<?php echo URL;?>">
    <meta property="og:image" content="<?php echo SITE;?>image.php?file=<?php echo $track->link->image;?>">
    <meta property="og:site_name" content="EarnBugs">
    <meta property="article:section" content="Pictures" />
    
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?php echo $track->link->title;?>">
    <meta name="twitter:description" content="<?php echo $track->link->description;?>">
    <meta name="twitter:url" content="<?php echo URL;?>">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
</head>

<body>
<script type="text/javascript">
$(document).ready(function() {
    process();
});
function process() {
    $.ajax({
        url: 'includes/process.php',
        data: {id: '<?php echo $_GET["id"]?>'}
    })
    .done(function() {
        window.location.href = '<?php echo $track->redirectUrl();?>';
    });
}
</script>
</body>

</html>