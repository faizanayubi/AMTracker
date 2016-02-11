<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta property="fb:app_id" content="1644257145856201">
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="article">
    <meta property="og:title" content="<?php echo $track->item->title;?>" />
    <meta property="og:description" content="<?php echo $track->item->description;?>">
    <meta property="og:url" content="<?php echo URL;?>">
    <meta property="og:image" content="<?php echo SITE;?>image.php?name=<?php echo $track->image_name();?>">
    <meta property="og:site_name" content="EarnBugs">
    <meta property="article:section" content="Pictures" />
    
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?php echo $track->item->title;?>">
    <meta name="twitter:description" content="<?php echo $track->item->description;?>">
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
        console.log("success");
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
}
</script>
</body>

</html>