<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $this->site_title(); ?></title>
    <link rel="stylesheet" href="<?= PROOT ?>css/bootstrap.min.css" media="screen" title="no title" charset="utf-8">
    <link rel="stylesheet" href="<?= PROOT ?>css/custom.css" media="screen" title="no title" charset="utf-8">
    <script src="<?= PROOT ?>js/jquery-2.2.4.min.js"></script>
    <script src="<?= PROOT ?>js/bootstrap.min.js"></script>
    <?= $this->content('head'); ?>
  </head>
  <body>
    <?= $this->content('body'); ?>
  </body>
</html>
